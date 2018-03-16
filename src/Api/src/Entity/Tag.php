<?php

/*
 * Copyright (c) php.ug Contributors. All rights reserved.
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root
 * for full license information.
 */

namespace Phpug\Api\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * The Persistent-Storage Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="tag")
 * @property string $tagname
 * @property text $description
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $tagname;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\ManyToMany(targetEntity="Usergroup", mappedBy="tags")
     * @var \Doctrine\Common\Collections\ArrayCollection $usergroups
     */
    protected $usergroups;

    public function __construct()
    {
        $this->usergroups = new \Doctrine\Common\Collections\ArrayCollection();
    }
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
     * Set the tagname for this Object
     *
     * @param string $tagname
     *
     * @return self
     */
    public function setTagname($tagname)
    {
        $this->tagname = $tagname;

        return $this;
    }

    /**
     * Get the name of this tag
     *
     * @return string
     */
    public function getTagname()
    {
        return $this->tagname;
    }

    /**
     * Set the description of this tag
     *
     * @param string $description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * add a group to this tag
     *
     * @param Usergroup $group
     *
     * @return self
     */
    public function addGroup(Usergroup $group)
    {
        $this->usergroups->add($group);

        return $this;
    }

    /**
     * Remove the usergroup from this entry
     *
     * @param Usergroup $group
     *
     * @return self
     */
    public function removeGroup(Usergroup $group)
    {

        $this->usergroups->removeElement($group);

        return $this;
    }

    /**
     * Get all groups for this tag
     *
     * @return Usergroup[]
     */
    public function getGroups()
    {
        return $this->usergroups->toArray();
    }

}
