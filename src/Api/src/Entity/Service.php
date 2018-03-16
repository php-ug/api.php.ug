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
 * @ORM\Table(name="service")
 * @property string $name
 * @property string $baseurl
 */
class Service
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
    protected $baseurl;

    /**
     * @ORM\OneToMany(targetEntity="Service", mappedBy="service")
     * @var Groupcontact[]
     */
    protected $contacts;

    /**
     * @ORM\Column(type="string")
     */
    protected $cssclass;

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
        $array = get_object_vars($this);
        foreach($array as $key => $item) {
            if (is_object($item)) {
                $array[$key] = $item->toArray();
            }
        }

        return $array;
    }
    
    public function __construct()
    {
        $this->contacts = new ArrayCollection();
    }

    /**
     * Get the name of this instance
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}
