<?php

/*  
 * Copyright (c) php.ug Contributors. All rights reserved. 
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root   
 * for full license information.  
 */

namespace Phpug\Api\Mapper;

use Phpug\Api\Entity\Grouptype;

class GrouptypeMapper
{
    public function __invoke(Grouptype $grouptype) : array
    {
        return [
            'id'          => $grouptype->getId(),
            'name'        => $grouptype->getName(),
            'description' => $grouptype->getDescription(),

        ];
    }
}
