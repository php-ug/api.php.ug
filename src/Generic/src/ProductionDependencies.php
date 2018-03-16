<?php

/*
 * Copyright (c) php.ug Contributors. All rights reserved.
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root
 * for full license information.
 */

namespace Phpug\Generic;

use Zend\Expressive\Container\ErrorResponseGeneratorFactory;
use Zend\Expressive\Middleware\ErrorResponseGenerator;
use bitExpert\Disco\Annotations\Configuration;
use bitExpert\Disco\Annotations\Bean;
use bitExpert\Disco\Annotations\Alias;

/**
 * Class DevelopmentDependencies
 *
 * @Configuration
 */
class ProductionDependencies extends GlobalDependencies
{
    /**
     * Middleware\ErrorResponseGenerator::class => Container\ErrorResponseGeneratorFactory::class,
     *
     * @Bean({"aliases" = {@Alias({"name" = "Zend\Expressive\Middleware\ErrorResponseGenerator"})}})
     */
    public function getZendExpressiveMiddlewareErrorResponseGenerator() : ErrorResponseGenerator
    {
        return (new ErrorResponseGeneratorFactory())($this->getBeanFactory());
    }

}
