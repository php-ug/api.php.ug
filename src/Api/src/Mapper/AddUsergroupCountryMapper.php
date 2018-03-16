<?php

/*  
 * Copyright (c) php.ug Contributors. All rights reserved. 
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root   
 * for full license information.  
 */

namespace Phpug\Api\Mapper;

use Phpug\Api\Entity\Usergroup;
use Phpug\Api\Cache\Cache;

class AddUsergroupCountryMapper implements UsergroupMappable
{
    private $cache;

    private $mapper;

    public function __construct(UsergroupMappable $mapper, Cache $countryCode)
    {
        $this->mapper = $mapper;
        $this->cache = $countryCode;
    }

    public function __invoke(Usergroup $usergroup): array
    {
        $map = ($this->mapper)($usergroup);
        $this->cache->setUserGroup($usergroup);

        unset($map['caches']);

        $map['country'] = $this->cache->getCache()->getCache();

        return $map;
    }

}
