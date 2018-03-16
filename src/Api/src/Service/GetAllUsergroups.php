<?php

/*  
 * Copyright (c) php.ug Contributors. All rights reserved. 
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root   
 * for full license information.  
 */

namespace Phpug\Api\Service;

use Doctrine\ORM\EntityManager;
use Phpug\Api\Collection\UsergroupCollection;

class GetAllUsergroups
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function __invoke() : UsergroupCollection
    {
        $groupList = new UsergroupCollection();

        $groups = $this->em->getRepository('Phpug\Api\Entity\Usergroup')->findAll();

        foreach ($groups as $group) {
            $groupList->addGroup($group);
        }

        return $groupList;
    }
}
