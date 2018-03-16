<?php

/*
 * Copyright (c) php.ug Contributors. All rights reserved.
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root
 * for full license information.
 */

namespace Phpug\Api\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;
use DateTimeImmutable;

/**
 * The Persistent-Storage Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="cache")
 * @property \DateTime $lastChangeDate
 * @property text $cache
 * @property string $type
 * @property Usergroup $usergroup
 */
class Cache
{
    /**
    * @ORM\Id
    * @ORM\Column(type="integer");
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $lastChangeDate;

    /**
     * @ORM\Column(type="text")
     */
    protected $cache;

    /**
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="Usergroup", inversedBy="caches")
     */
    protected $usergroup;

    /**
    * Magic getter to expose protected properties.
    *
    * @param string $property
    * @return mixed
    */
    public function __get($property) {
        return $this->$property;
    }

    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value) {
        $this->$property = $value;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function toArray() {
        return array();
    }

    /**
     * Set the cache for this Object
     *
     * @param string $cache
     *
     * @return self
     */
    public function setCache($cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * Get the content of this cache-entry
     *
     * @return string
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * Set the datetime of this cache-entry
     *
     * @param DateTimeInterface
     *
     * @return self
     */
    public function setLastChangeDate(DateTimeInterface $lastChangeDate)
    {
        $this->lastChangeDate = $lastChangeDate;

        return $this;
    }

    /**
     * Get the last change date
     *
     * @return \DateTime
     */
    public function getLastChangeDate()
    {
        if (! $this->lastChangeDate instanceof DateTimeInterface) {
            return new DateTimeImmutable('@0');
        }
        return $this->lastChangeDate;
    }

    /**
     * Set the cache-type
     *
     * @param string type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = (string) $type;

        return $this;
    }

    /**
     * Get the cache-type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the id
     *
     * @param int $id
     *
     * @return Groupcontact
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the ID of this cache
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the group
     *
     * @param Usergroup $group
     *
     * @return self
     */
    public function setGroup(Usergroup $group)
    {
        $this->usergroup = $group;

        return $this;
    }

    /**
     * Remove the usergroup from this entry
     *
     * @return self
     */
    public function removeGroup()
    {
        $this->usergroup = null;

        return $this;
    }

    /**
     * Get the group
     *
     * @return Usergroup
     */
    public function getGroup()
    {
        return $this->usergroup;
    }

}
