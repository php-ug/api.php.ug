<?php

/*  
 * Copyright (c) php.ug Contributors. All rights reserved. 
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root   
 * for full license information.  
 */

namespace Phpug\Api\Mapper;

use Phpug\Api\Entity\Tag;

class TagMapper
{
    public function __invoke(Tag $tag) : array
    {
        return [
            'name' => $tag->getTagname(),
            'description' => $tag->getDescription(),
        ];
    }
}
