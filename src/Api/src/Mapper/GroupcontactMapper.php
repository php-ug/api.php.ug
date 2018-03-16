<?php

/*  
 * Copyright (c) php.ug Contributors. All rights reserved. 
 *
 * Licensed under the MIT License. See LICENSE.md file in the project root   
 * for full license information.  
 */

namespace Phpug\Api\Mapper;

use Phpug\Api\Entity\Groupcontact;

class GroupcontactMapper
{
    public function __invoke(Groupcontact $contact) : array
    {
        return [
            'url'      => $contact->getUrl(),
            'name'     => $contact->getName(),
            'type'     => $contact->service->getName(),
            'cssClass' => $contact->service->cssclass,
        ];
    }
}
