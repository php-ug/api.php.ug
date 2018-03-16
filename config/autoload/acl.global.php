<?php

/*  
 * Copyright (c) php.ug Contributors. All rights reserved. 
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root   
 * for full license information.  
 */

namespace Phpug\Api\Acl;

return [
    'acl' => [
        'roles' => [
            'guest' => null,
            'member' => 'guest',
            'admin'   => null,
        ],
        'routes' => [
            'allow' => [
                //'*' => 'admin',
                'api.ug.promote' => 'member',
                'api.ug.edit'    => [
                    'role'   => 'member',
                    'assert' => UsersGroupAssertion::class,
                ],
            ],
        ],
        'admins' => [
            'twitter' => [
                'heiglandreas',
            ],
            'github' => [
                'heiglandreas',
            ]
        ],
    ],
];
