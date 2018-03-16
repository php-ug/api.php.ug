<?php

/*  
 * Copyright (c) php.ug Contributors. All rights reserved. 
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root   
 * for full license information.  
 */

namespace Phpug\Api\Mapper;

use Phpug\Api\Entity\Usergroup;

interface UsergroupMappable
{
    public function __invoke(Usergroup $usergroup) : array;
}
