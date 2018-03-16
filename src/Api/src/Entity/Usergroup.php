<?php

/*
 * Copyright (c) php.ug Contributors. All rights reserved.
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root
 * for full license information.
 */

namespace Phpug\Api\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * The Persistent-Storage Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="usergroup")
 * @property string $name
 * @property string $shortname
 * @property string $url
 * @property string $icalendar_url
 * @property double $latitude
 * @property double $longitude
 * @property int    $ugtype
 * @property int    $state
 * @property string $adminMail
 */
class Usergroup
{

    const PROMOTED = 0;
    const ACTIVE   = 1;
    const OBSOLETE = 2;

    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
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
    protected $shortname;

    /**
     * @ORM\Column(type="string")
     */
    protected $url;

    /**
     * @ORM\Column(type="string")
     */
    protected $icalendar_url;

    /**
     * @ORM\Column(type="float")
     */
    protected $latitude;

    /**
     * @ORM\Column(type="float")
     */
    protected $longitude;
    
    /**
     * @ORM\ManyToOne(targetEntity="Grouptype", inversedBy="usergroups")
     */
    protected $ugtype;

    /**
     * @ORM\OneToMany(targetEntity="Groupcontact", mappedBy="group", cascade={"persist"})
     * @var Collection
     */
    protected $contacts;

    /**
     * @ORM\OneToMany(targetEntity="Cache", mappedBy="usergroup", cascade={"persist"})
     * @var Collection
     */
    protected $caches;

    /**
     * @ORM\Column(type="integer")
     */
    protected $state = 0;

    protected $inputFilter;

    /**
     * @ORM\Column(type="string")
     */
    protected $adminMail;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="usergroups")
     * @ORM\JoinTable(name="usergroups_tags")
     */
    protected $tags;

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
        $return = array(
            'id'            => $this->getId(),
            'name'          => $this->getName(),
            'shortname'     => $this->getShortname(),
            'url'           => $this->getUrl(),
            'icalendar_url' => $this->getIcalendar_url(),
            'latitude'      => $this->getLatitude(),
            'longitude'     => $this->getLongitude(),
            'state'         => $this->getState(),
            'contacts'      => array(),
            'tags'          => array(),
            'ugtype'        => array(
                'id'          => $this->ugtype->getId(),
                'name'        => $this->ugtype->getName(),
                'description' => $this->ugtype->getDescription(),
            ),
        );

        foreach ($this->contacts as $contact){
            $return['contacts'][] = array(
                'url'      => $contact->getUrl(),
                'name'     => $contact->getName(),
                'type'     => $contact->service->getName(),
                'cssClass' => $contact->service->cssclass,
            );
        }

        foreach ($this->tags as $tag) {
            $return['tags'][] = array(
                'name' => $tag->getTagname(),
                'description' => $tag->getDescription(),
            );
        }

        if (! $return['tags']) {
            $return['tags'][] = array(
                'name' => 'PHP',
                'description' => '',
            );
        }

        return $return;
    }
    
    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->caches   = new ArrayCollection();
        $this->tags     = new ArrayCollection();
    }

    public function getContacts() : Collection
    {
        return $this->contacts;
    }

    public function getCaches() : Collection
    {
        return $this->caches;
    }

    public function setName(string $name) : self
    {
        $this->name = $name;

        return $this;
    }

    public function setShortname(string $acronym) : self
    {
        $this->shortname = $acronym;

        return $this;
    }

    public function setContacts(Collection $contacts) : self
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * @param mixed $icalendar_url
     */
    public function setIcalendar_Url($icalendar_url)
    {
        $this->icalendar_url = $icalendar_url;

        return $this;
    }

    /**
     * @param mixed $icalendar_url
     */
    public function setIcalendarUrl($icalendar_url)
    {
        $this->icalendar_url = $icalendar_url;

        return $this;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $loc = preg_split('/[^\d\.\-]+/', $location);
        $this->setLatitude($loc[0]);
        $this->setLongitude($loc[1]);

        return $this;
    }

    /**
     * @param mixed $ugtype
     */
    public function setUgtype($ugtype)
    {
        $this->ugtype = $ugtype;

        return $this;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function addContacts(Collection $contacts)
    {
        foreach ($contacts as $contact) {
            $contact->setGroup($this);
            $this->contacts->add($contact);
        }

        return $this;
    }

    /**
     * Add a single groupcontact to the contagslist
     *
     * @param Groupcontact $contact
     *
     * @return self
     */
    public function addContact(Groupcontact $contact)
    {
        $this->contacts->add($contact);

        return $this;
    }

    public function removeContacts(Collection $contacts)
    {
        foreach ($contacts as $contact) {
            $contact->setGroup(null);
            $this->contacts->removeElement($contact);
        }

        return $this;
    }

    /**
     * Set the state of the UG
     *
     * @param int $date
     *
     * @return self
     */
    public function setState($state)
    {

        $this->state = $state;

        return $this;
    }

    /**
     * Get the state of this ug
     *
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Get the name of the entity
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the shortname of the entity
     *
     * @return string
     */
    public function getShortname()
    {
        return $this->shortname;
    }

    /**
     * Get the ID of the entity
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the Location of the entity
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->latitude . ' ' . $this->longitude;
    }

    /**
     * Get the latitude of the entity
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Get the longitude of the entity
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Get the url of the entity
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get the iCalendar-URL of this entity
     *
     * @return string
     */
    public function getIcalendar_url()
    {
        return $this->icalendar_url;
    }

    /**
     * Get the iCalendar-URL of this entity
     *
     * @return string
     */
    public function getIcalendarUrl()
    {
        return $this->icalendar_url;
    }

    /**
     * Get the UG-Type of this entity
     *
     * @return mixed
     */
    public function getUgtype()
    {
        if (! $this->ugtype instanceof Grouptype) {
            return 0;
        }
        return $this->ugtype->getId();
    }

    /**
     * Set the caches for this object
     *
     * @param ArrayCollection $contacts
     *
     * @return $this
     */
    public function addCaches(ArrayCollection $caches)
    {
        foreach ($caches as $cache) {
            $cache->setGroup($this);
            $this->caches->add($cache);
        }

        return $this;
    }

    /**
     * remove all caches from this object
     *
     * @param ArrayCollection $contacts
     *
     * @return $this
     */
    public function removeCaches(ArrayCollection $caches)
    {
        foreach ($caches as $cache) {
            $cache->removeGroup();
            $this->caches->removeElement($cache);
        }

        return $this;
    }

    /**
     * Check whether this usergroup has any contacts
     *
     * @return bool
     */
    public function hasContacts()
    {
        if ($this->contacts->count() < 1) {
            return false;
        }

        return true;
    }

    /**
     * Set an administrative contact
     *
     * @param string $contact
     *
     * @return self
     */
    public function setAdminMail($contact)
    {
        $this->adminMail = $contact;

        return $this;
    }

    /**
     * Get the administrative contact
     *
     * @return string
     */
    public function getAdminMail()
    {
        return $this->adminMail;
    }

    /**
     * Add a tag
     *
     * @param Tag $tag
     *
     * @return self
     */
    public function addTag(Tag $tag)
    {
        $tag->addGroup($this);
        $this->tags->add($tag);

        return $this;
    }

    /**
     * Remove a tag
     *
     * @param Tag $tag
     *
     * @return self
     */
    public function removeTag(Tag $tag)
    {
        $tag->removeGroup($this);
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * Get a list of all Tags
     *
     * @return Tag[]
     */
    public function getTags()
    {
        return $this->tags->toArray();
    }

    /**
     * Set the given tags
     *
     * @param ArrayCollection $tags
     *
     * @return self
     */
    public function addTags(ArrayCollection $tags)
    {
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }

        return $this;
    }

    /**
     * Remove a set of tags
     * 
     * @param ArrayCollection $tags
     *
     * @return self
     */
    public function removeTags(ArrayCollection $tags)
    {
        foreach ($tags as $tag) {
            $this->removeTag($tags);
        }

        return $this;
    }


}
