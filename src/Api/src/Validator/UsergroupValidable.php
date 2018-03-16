<?php

/*  
 * Copyright (c) php.ug Contributors. All rights reserved. 
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root   
 * for full license information.  
 */

namespace Phpug\Api\Validator;

use Phpug\Api\Entity\Usergroup;

interface UsergroupValidable
{
    public function isValid(Usergroup $usergroup) : bool;
}
