<?php

/*
 * Copyright (c) php.ug Contributors. All rights reserved.
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root
 * for full license information.
 */

namespace Phpug\ApiTest\Action;

use Phpug\Api\Action\PingAction;
use Interop\Http\ServerMiddleware\DelegateInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

class PingActionTest extends TestCase
{
    public function testResponse()
    {
        $pingAction = new PingAction();
        $response = $pingAction->process(
            $this->prophesize(ServerRequestInterface::class)->reveal(),
            $this->prophesize(DelegateInterface::class)->reveal()
        );

        $json = json_decode((string) $response->getBody());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue(isset($json->ack));
    }
}
