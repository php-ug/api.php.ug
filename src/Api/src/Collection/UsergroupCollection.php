<?php

/*  
 * Copyright (c) php.ug Contributors. All rights reserved. 
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root   
 * for full license information.  
 */

namespace Phpug\Api\Collection;

use Countable;
use Iterator;
use Org_Heigl\IteratorTrait\IteratorTrait;
use Phpug\Api\Entity\Usergroup;

class UsergroupCollection implements Iterator, Countable
{
    private $groups;

    use IteratorTrait;

    public function __construct()
    {
        $this->groups = [];
    }

    /**
     * Get the array the iterator shall iterate over.
     *
     * @return array
     */
    protected function & getIterableElement() : array
    {
        return $this->groups;
    }

    /**
     * Get the array the iterator shall iterate over.
     *
     * @return array
     */
    protected function & getIteratorArray() : array
    {
        return $this->groups;
    }

    public function addGroup(Usergroup $usergroup)
    {
        $this->groups[] = $usergroup;
    }

    public function count() : int
    {
        return count($this->groups);
    }
}
