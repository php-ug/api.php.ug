<?php

/*
 * Copyright (c) php.ug Contributors. All rights reserved.
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root
 * for full license information.
 */

namespace Phpug\Api\Entity;

use Doctrine\ORM\Mapping as ORM
;
use Zend\Validator\IsInstanceOf;

/**
 * The Persistent-Storage Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="groupcontact")
 * @property string $name
 * @property integer $service
 * @property integer $group 
 */
class Groupcontact
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
     * @ORM\ManyToOne(targetEntity="Service", inversedBy="contacts")
     */
    protected $service;

    /**
     * @ORM\ManyToOne(targetEntity="Usergroup", inversedBy="contacts")
     */
    protected $group;

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

        $array['url'] = $this->getUrl();
        $array['type'] = $this->service->name;
        $array['cssClass'] = $this->service->cssclass;
        return $array;
    }
    
    public function getUrl() 
    {
        $baseUrl = $this->service->baseurl;
        return sprintf($baseUrl, $this->name);
    }

    public function getService()
    {
        if (! $this->service instanceof Service) {
            return 0;
        }
        return $this->service->id;
    }

    public function getServiceName()
    {
        if (! $this->service instanceof Service) {
            return '';
        }
        return $this->service->getName();

    }

    /**
     * Set the name
     *
     * @param string $name The name of the contact
     *
     * @return Groupcontact
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the service
     *
     * @param mixed $service
     *
     * @return Groupcontact
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
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

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the group
     *
     * @param int $group
     *
     * @return Groupcontact
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    public function getGroup()
    {
        return $this->group;
    }

}
