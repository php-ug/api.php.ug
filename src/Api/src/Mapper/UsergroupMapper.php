<?php

/*  
 * Copyright (c) php.ug Contributors. All rights reserved. 
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root   
 * for full license information.  
 */

namespace Phpug\Api\Mapper;

use Phpug\Api\Entity\Usergroup;

class UsergroupMapper implements UsergroupMappable
{
    private $contactMapper;

    private $grouptypeMapper;

    private $tagMapper;

    public function __construct(
        GroupcontactMapper $groupcontactMapper,
        GrouptypeMapper $grouptypeMapper,
        TagMapper $tagMapper
    ) {
        $this->contactMapper = $groupcontactMapper;
        $this->grouptypeMapper = $grouptypeMapper;
        $this->tagMapper = $tagMapper;
    }
    public function __invoke(Usergroup $usergroup) : array
    {
        $return = [
            'id'            => $usergroup->getId(),
            'name'          => $usergroup->getName(),
            'shortname'     => $usergroup->getShortname(),
            'url'           => $usergroup->getUrl(),
            'icalendar_url' => $usergroup->getIcalendar_url(),
            'latitude'      => $usergroup->getLatitude(),
            'longitude'     => $usergroup->getLongitude(),
            'state'         => $usergroup->getState(),
            'contacts'      => [],
            'tags'          => [],
            'ugtype'        => ($this->grouptypeMapper)($usergroup->ugtype)
        ];

        foreach ($usergroup->getContacts() as $contact){
            $return['contacts'][] = ($this->contactMapper)($contact);
        }

        foreach ($usergroup->getTags() as $tag) {
            $return['tags'][] = ($this->tagMapper)($tag);
        }

        if (! $return['tags']) {
            $return['tags'][] = [
                'name' => 'PHP',
                'description' => '',
            ];
        }

        return $return;

    }
}
