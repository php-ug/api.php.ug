<?php

/*
 * Copyright (c) php.ug Contributors. All rights reserved.
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root
 * for full license information.
 */
return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'params' => [
                    'url' => 'mysql://username:password@localhost/database',
                    'charset' => 'utf8',
                    'driverOptions' => [
                        1002 => 'SET NAMES utf8'
                    ]
                ],
            ],
        ],
        'driver' => [
            'orm_default' => [
                'class' => \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain::class,
                'drivers' => [
                    'Phpug\Api\Entity' => 'phpug_entity',
                ],
            ],
            'phpug_entity' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => __DIR__ . '/../../src/Api/Entity',
            ],
        ],
    ],
];
