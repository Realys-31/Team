<?php

namespace Team\Model;

use Team\Model\Base\Person as BasePerson;

class Person extends BasePerson
{
    public function getTitle()
    {
        return $this->getFirstName() . " " . $this->getLastName();
    }
}
