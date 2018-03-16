<?php

/*  
 * Copyright (c) php.ug Contributors. All rights reserved. 
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root   
 * for full license information.  
 */

namespace Phpug\Api\Validator;

use Phpug\Api\Acl\RoleManager;
use Phpug\Api\Entity\Usergroup;
use Zend\Permissions\Acl\Acl;

class IsRoleAllowedToSeeGroup implements UsergroupValidable
{
    private $role;

    private $acl;

    public function __construct(Acl $acl, RoleManager $role)
    {
        $this->acl = $acl;
        $this->role = $role;
    }
    public function isValid(Usergroup $usergroup): bool
    {
        return $this->acl->isAllowed((string) $this->role, 'api.ug.edit', 'edit');
    }
}
