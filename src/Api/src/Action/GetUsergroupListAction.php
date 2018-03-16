<?php

/*  
 * Copyright (c) php.ug Contributors. All rights reserved. 
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root   
 * for full license information.  
 */

namespace Phpug\Api\Action;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface as ServerMiddlewareInterface;
use Phpug\Api\Mapper\UsergroupCollectionMapper;
use Phpug\Api\Service\GetAllUsergroups;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Zend\Diactoros\Response\JsonResponse;

class GetUsergroupListAction implements ServerMiddlewareInterface
{
    private $service;

    private $mapper;

    public function __construct(
        GetAllUsergroups $service,
        UsergroupCollectionMapper $mapper
    ) {
        $this->service = $service;
        $this->mapper  = $mapper;
    }

    public function process(
        ServerRequestInterface $request,
        DelegateInterface $delegate
    ) {
        $groups = ($this->service)();

        return new JsonResponse(($this->mapper)($groups));
    }
}
