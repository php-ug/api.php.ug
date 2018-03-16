<?php

/*
 * Copyright (c) php.ug Contributors. All rights reserved.
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root
 * for full license information.
 */

namespace Phpug\Generic;

use Zend\Expressive\Container\WhoopsErrorResponseGeneratorFactory;
use Zend\Expressive\Middleware\WhoopsErrorResponseGenerator;
use bitExpert\Disco\Annotations\Configuration;
use bitExpert\Disco\Annotations\Bean;
use bitExpert\Disco\Annotations\Alias;

/**
 * Class DevelopmentDependencies
 *
 * @Configuration
 */
class DevelopmentDependencies extends GlobalDependencies
{
    /**
     * Middleware\ErrorResponseGenerator::class => Container\ErrorResponseGeneratorFactory::class,
     *
     * @Bean({"aliases" = {@Alias({"name" = "Zend\Expressive\Middleware\ErrorResponseGenerator"})}})
     */
    public function getWhoopsErrorResponseGenerator() : WhoopsErrorResponseGenerator
    {
        return (new WhoopsErrorResponseGeneratorFactory())($this->getBeanFactory());
    }

}
