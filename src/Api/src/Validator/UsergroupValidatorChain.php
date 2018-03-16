<?php

/*  
 * Copyright (c) php.ug Contributors. All rights reserved. 
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root   
 * for full license information.  
 */

namespace Phpug\Api\Validator;

use Phpug\Api\Entity\Usergroup;

class UsergroupValidatorChain implements UsergroupValidable
{
    /** @var \Phpug\Api\Validator\UsergroupValidable[] */
    private $chain;

    public function __construct()
    {
        $this->chain = [];
    }

    public function add(UsergroupValidable $validator)
    {
        $this->chain[] = $validator;
    }

    public function isValid(Usergroup $usergroup): bool
    {
        foreach ($this->chain as $validator) {
            if (! $validator->isValid($usergroup)) {
                return false;
            }
        }

        return true;
    }
}
