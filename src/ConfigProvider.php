<?php

declare(strict_types = 1);

namespace Zend\Expressive;

use Zend\Expressive\Helper;
use Zend\Expressive\Delegate;
use Zend\Expressive\Container;
use Zend\Expressive\Middleware;
use Zend\Expressive\Application;
use Zend\Expressive\PipelineConfig;

final class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            "dependencies"  => $this->getServiceConfig()
        ];
    }

    /**
     * Return dependencies mapping for this module.
     * We recommend using fully-qualified class names whenever possible as service names.
     *
     * @return array
     */
    public function getServiceConfig() : array
    {
        return [
            // Use 'aliases' to alias a service name to another service. The
            // key is the alias name, the value is the service to which it points.
            'aliases' => [
                "Zend\Expressive\Delegate\DefaultDelegate" => Delegate\NotFoundDelegate::class,
            ],
            // Use 'invokables' for constructor-less services,
            // or services that do not require arguments to the constructor.
            //
            // Map a service name to the class name.
            // Fully\Qualified\InterfaceName::class => Fully\Qualified\ClassName::class,
            'invokables'    => [
                Helper\ServerUrlHelper::class => Helper\ServerUrlHelper::class,
            ],

            // Use 'factories' for services provided by callbacks/factory classes.
            'factories'     => [
                Application::class                => Container\ApplicationFactory::class,
                Delegate\NotFoundDelegate::class  => Container\NotFoundDelegateFactory::class,
                Helper\ServerUrlMiddleware::class => Helper\ServerUrlMiddlewareFactory::class,
                Helper\UrlHelper::class           => Helper\UrlHelperFactory::class,
                Helper\UrlHelperMiddleware::class => Helper\UrlHelperMiddlewareFactory::class,

                Zend\Stratigility\Middleware\ErrorHandler::class => Container\ErrorHandlerFactory::class,
                Middleware\ErrorResponseGenerator::class         => Container\ErrorResponseGeneratorFactory::class,
                Middleware\NotFoundHandler::class                => Container\NotFoundHandlerFactory::class,
            ],
            'delegators'    => [
                Application::class => [
                    PipelineConfig::class,
                ]
            ],
        ];
    }
}
