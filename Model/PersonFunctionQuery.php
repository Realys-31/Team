<?php

namespace Team\Model;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Team\Model\Base\PersonFunctionQuery as BasePersonFunctionQuery;
use Team\Model\Map\PersonFunctionLinkTableMap;
use Team\Model\Map\PersonFunctionTableMap;


/**
 * Skeleton subclass for performing query and update operations on the 'person_function' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class PersonFunctionQuery extends BasePersonFunctionQuery
{
    /**
     * @param $id
     * @return $this
     */
    public function filterByPersonId($id)
    {
        if (is_array($id)) {
            $id = implode(",", $id);
        }

        $personJoin = new Join(PersonFunctionTableMap::ID, PersonFunctionLinkTableMap::PERSON_ID, Criteria::LEFT_JOIN);
        $this
            ->addJoinObject($personJoin, "teamJoin")
            ->where(PersonFunctionLinkTableMap::PERSON_ID . " " . Criteria::IN . " (" . $id . ")");

        return $this;
    }
} // PersonFunctionQuery
