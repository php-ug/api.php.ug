<?php

/*  
 * Copyright (c) php.ug Contributors. All rights reserved. 
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root   
 * for full license information.  
 */

namespace Phpug\Api\Mapper;

use Phpug\Api\Acl\RoleManager;
use Phpug\Api\Acl\UsersGroupAssertion;
use Phpug\Api\Entity\Usergroup;
use Zend\Permissions\Acl\Acl;

class AddAccessRightsForUsergroupMapper implements UsergroupMappable
{
    private $mappable;

    private $usergroupAssertion;

    private $acl;

    private $role;

    public function __construct(
        UsergroupMappable $mappable,
        UsersGroupAssertion $assertion,
        Acl $acl,
        RoleManager $role
    ) {
        $this->mappable = $mappable;
        $this->usergroupAssertion = $assertion;
        $this->acl = $acl;
        $this->role = $role;
    }

    public function __invoke(Usergroup $usergroup): array
    {
        $map = ($this->mappable)($usergroup);

        $this->usergroupAssertion->setGroup($usergroup);
        try {
            if ($this->acl->isAllowed($this->role, 'ug', 'edit')) {
                $map['edit'] = true;
            }
        } catch (\Exception $e)
        {
            // Doing nothing on purpose…
        }

        return $map;

    }
}
