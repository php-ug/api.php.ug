<?php

/*  
 * Copyright (c) php.ug Contributors. All rights reserved. 
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root   
 * for full license information.  
 */

namespace Phpug\Api\Mapper;

use Phpug\Api\Collection\UsergroupCollection;
use Phpug\Api\Validator\UsergroupValidable;

class UsergroupCollectionMapper
{
    private $usergroupMapper;

    private $validator;

    public function __construct(
        UsergroupMappable $usergroupMapper,
        UsergroupValidable $validator
    ) {
        $this->usergroupMapper = $usergroupMapper;
        $this->validator = $validator;
    }
    public function __invoke(UsergroupCollection $usergroups) : array
    {
        $content['error'] = null;
        $content['groups'] = [];
       // $content['list']  = $types[0]->toArray();
        /** @var \Phpug\Api\Entity\Usergroup $group */
        foreach ($usergroups as $group) {
            if (! $this->validator->isValid($group)) {
                continue;
            }

            $content['groups'][] = ($this->usergroupMapper)($group);
        }

        return $content;
    }
}
