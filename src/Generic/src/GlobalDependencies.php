<?php

/*
 * Copyright (c) php.ug Contributors. All rights reserved.
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root
 * for full license information.
 */

namespace Phpug\Generic;

use bitExpert\Disco\BeanFactoryRegistry;
use bitExpert\Disco\Annotations\Alias;
use bitExpert\Disco\Annotations\Bean;
use bitExpert\Disco\Annotations\Configuration;
use bitExpert\Disco\Annotations\Parameter;
use Phpug\Api\DependenciesTrait as ApiSlice;
use Psr\Container\ContainerInterface;
use Twig_Environment;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Container\ErrorHandlerFactory;
use Zend\Expressive\Container\NotFoundDelegateFactory;
use Zend\Expressive\Container\WhoopsFactory;
use Zend\Expressive\Container\WhoopsPageHandlerFactory;
use Zend\Expressive\Delegate\NotFoundDelegate;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\ServerUrlMiddleware;
use Zend\Expressive\Helper\ServerUrlMiddlewareFactory;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Helper\UrlHelperFactory;
use Zend\Expressive\Helper\UrlHelperMiddleware;
use Zend\Expressive\Helper\UrlHelperMiddlewareFactory;
use Zend\Expressive\Middleware\NotFoundHandler;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\Twig\TwigEnvironmentFactory;
use Zend\Expressive\Twig\TwigRendererFactory;
use Zend\Stratigility\Middleware\ErrorHandler;

/**
 * @Configuration
 */
class GlobalDependencies
{
    use ApiSlice;

    /**
     *'Zend\Expressive\Delegate\DefaultDelegate' => Delegate\NotFoundDelegate::class
     *
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Delegate\DefaultDelegate"}),
     *     @Alias({"type" = true})
     * }})
     */
    public function getZendExpressiveDelegateDefaultDelegate(): NotFoundDelegate
    {
        return (new NotFoundDelegateFactory())($this->getBeanFactory());
    }

    /**
     * ZendExpressive\Helper\ServerUrlHelper::class => ZendExpressive\Helper\ServerUrlHelper::class,
     *
     * @Bean({"aliases" = {@Alias({"type" = true})}})
     */
    public function getZendExpressiveHelperServerUrlHelper(): ServerUrlHelper
    {
        return new ServerUrlHelper();
    }

    /**
     *  Application::class => Container\ApplicationFactory::class,
     *
     * @Bean({"aliases" = {@Alias({"type" = true})}})
     */
    public function getZendExpressiveContainerApplication(): Application
    {
        $applicationFactory = new ApplicationFactory();

        return $applicationFactory($this->getBeanFactory());
    }

    /**
     * Helper\ServerUrlMiddleware::class => Helper\ServerUrlMiddlewareFactory::class,
     *
     * @Bean({"aliases" = {@Alias({"type" = true})}})
     */
    public function getZendExpressiveHelperServerUrlMiddleware(
    ): ServerUrlMiddleware
    {
        $factory = new ServerUrlMiddlewareFactory();

        return $factory($this->getBeanFactory());
    }

    /**
     * Helper\UrlHelper::class => Helper\UrlHelperFactory::class,
     *
     * @Bean({"aliases" = {@Alias({"type" = true})}})
     */
    public function getZendExpressiveHelperUrlHelper(): UrlHelper
    {
        return (new UrlHelperFactory())($this->getBeanFactory());
    }

    /**
     * Helper\UrlHelperMiddleware::class => Helper\UrlHelperMiddlewareFactory::class,
     *
     * @Bean({"aliases" = {@Alias({"type" = true})}})
     */
    public function getZendExpressiveHelperUrlHelperMiddleware(
    ): UrlHelperMiddleware
    {
        return (new UrlHelperMiddlewareFactory())($this->getBeanFactory());
    }

    /**
     * Zend\Stratigility\Middleware\ErrorHandler::class => Container\ErrorHandlerFactory::class,
     *
     * @Bean({"aliases" = {@Alias({"type" = true})}})
     */
    public function getZendStratigilityMiddlewareErrorHandler(): ErrorHandler
    {
        return (new ErrorHandlerFactory())($this->getBeanFactory());
    }

    /**
     * Middleware\NotFoundHandler::class => Container\NotFoundHandlerFactory::class,-
     *
     * @Bean({"aliases" = {@Alias({"type" = true})}})
     */
    public function getZendExpressiveMiddlewareNotFoundHandler(
    ): NotFoundHandler
    {
        return (new NotFoundDelegateFactory())($this->getBeanFactory());
    }

    /**
     * 'Zend\Expressive\Whoops' => Container\WhoopsFactory::class,
     *
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\Whoops"}),
     *     @Alias({"type" = true})
     * }})
     */
    public function getZendExpressiveWhoops(): Run
    {
        return (new WhoopsFactory())($this->getBeanFactory());
    }

    /**
     * 'Zend\Expressive\WhoopsPageHandler' => Container\WhoopsPageHandlerFactory::class
     *
     * @Bean({"aliases" = {
     *     @Alias({"name" = "Zend\Expressive\WhoopsPageHandler"}),
     *     @Alias({"type" = true})
     * }})
     */
    public function getZendExpressiveWhoopsPageHandler(): PrettyPageHandler
    {
        return (new WhoopsPageHandlerFactory())($this->getBeanFactory());
    }

    /**
     * RouterInterface::class => FastRouteRouter::class,
     *
     * @Bean({"aliases" = {@Alias({"type" = true})}})
     */
    public function getZendExpressiveRouterRouterInterface(): RouterInterface
    {
        return new FastRouteRouter();
    }

    /**
     * Twig_Environment::class => TwigEnvironmentFactory::class,
     *
     * @Bean({"aliases" = {@Alias({"type" = true})}})
     */
    public function getTwigEnvironment(): Twig_Environment
    {
        return (new TwigEnvironmentFactory())($this->getBeanFactory());
    }

    /**
     * TemplateRendererInterface::class => TwigRendererFactory::class
     *
     * @Bean({"aliases" = {@Alias({"type" = true})}})
     */
    public function getZendExpressiveTemplateTemplateRendererInterface(
    ): TemplateRendererInterface
    {
        return (new TwigRendererFactory())($this->getBeanFactory());
    }

    /**
     * @return ContainerInterface
     */
    protected function getBeanFactory(): ContainerInterface
    {
        return BeanFactoryRegistry::getInstance();
    }

    /**
     * @Bean({"parameters" = {
     *    @Parameter({"name" = "expressive"})
     * }})
     */
    public function config(array $expressiveConfig = []): array
    {
        $defaultConfig = [
            'debug'                => true,
            'config_cache_enabled' => false,
            'zend-expressive'      => [
                'error_handler' => [
                    'template_404'   => 'error::404',
                    'template_error' => 'error::error',
                ],
            ],
            // This can be used to seed pre- and/or post-routing middleware
            'middleware_pipeline'  => [
                // An array of middleware to register. Each item is of the following
                // specification:
                //
                // [
                //  Required:
                //     'middleware' => 'Name or array of names of middleware services and/or callables',
                //  Optional:
                //     'path'     => '/path/to/match', // string; literal path prefix to match
                //                                     // middleware will not execute
                //                                     // if path does not match!
                //     'error'    => true, // boolean; true for error middleware
                //     'priority' => 1, // int; higher values == register early;
                //                      // lower/negative == register last;
                //                      // default is 1, if none is provided.
                // ],
                //
                // While the ApplicationFactory ignores the keys associated with
                // specifications, they can be used to allow merging related values
                // defined in multiple configuration files/locations. This file defines
                // some conventional keys for middleware to execute early, routing
                // middleware, and error middleware.
                'always'  => [
                    'middleware' => [
                        // Add more middleware here that you want to execute on
                        // every request:
                        // - bootstrapping
                        // - pre-conditions
                        // - modifications to outgoing responses
                        ServerUrlMiddleware::class,
                    ],
                    'priority'   => 10000,
                ],
                'routing' => [
                    'middleware' => [
                        ApplicationFactory::ROUTING_MIDDLEWARE,
                        UrlHelperMiddleware::class,
                        // Add more middleware here that needs to introspect the routing
                        // results; this might include:
                        // - route-based authentication
                        // - route-based validation
                        // - etc.
                        ApplicationFactory::DISPATCH_MIDDLEWARE,
                    ],
                    'priority'   => 1,
                ],
                'error'   => [
                    'middleware' => [
                        // Add error middleware here.
                    ],
                    'error'      => true,
                    'priority'   => - 10000,
                ],
            ],
            'routes'               => [
                [
                    'name'            => 'home',
                    'path'            => '/',
                    'middleware'      => HomePageAction::class,
                    'allowed_methods' => ['GET']
                ],
                [
                    'name'            => 'api.ping',
                    'path'            => '/api/ping',
                    'middleware'      => PingAction::class,
                    'allowed_methods' => ['GET'],
                ],
            ],
            'templates'            => [
                'extension' => 'html.twig',
                'paths'     => [
                    'app'    => ['templates/app'],
                    'layout' => ['templates/layout'],
                    'error'  => ['templates/error'],
                ],
            ],
            'twig'                 => [
                'extensions' => [
                    // extension service names or instances
                ],
            ]
        ];

        return array_merge($defaultConfig, $expressiveConfig);
    }
}
