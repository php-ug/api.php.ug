<?php

/*
 * Copyright (c) php.ug Contributors. All rights reserved.
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root
 * for full license information.
 */

namespace Phpug\Api\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM
;

/**
 * The Persistent-Storage Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="grouptype")
 * @property string $name
 * @property string $description 
 */
class Grouptype
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
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $description;
    
    /**
     * @ORM\OneToMany(targetEntity="Usergroup", mappedBy="ugtype")
     * @var Usergroup[]
     */
    protected $usergroups;

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
     * Get the name of the Entity
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Get the ID of the Entity
     * 
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Get the description of the entity
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    } 
    
    /**
     * Get the usergropus for this groputype
     * 
     * @return Usergroup[]
     */
    public function getUsergroups()
    {
        return $this->usergroups;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function toArray() {
        return array(
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'id'          => $this->getId()
        );

        return $array;
    }
    
    public function __construct()
    {
        $this->usergroups = new ArrayCollection();
    }

}
