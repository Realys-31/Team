<?php

namespace Team\Model;

use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Team\Model\Base\PersonQuery as BasePersonQuery;
use Team\Model\Map\PersonTableMap;
use Team\Model\Map\PersonTeamLinkTableMap;


/**
 * Skeleton subclass for performing query and update operations on the 'person' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class PersonQuery extends BasePersonQuery
{
    /**
     * @param $id
     * @return $this
     */
    public function filterByTeamId($id)
    {
        if (is_array($id)) {
            $id = implode(",", $id);
        }

        $teamJoin = new Join(PersonTableMap::ID, PersonTeamLinkTableMap::PERSON_ID, Criteria::LEFT_JOIN);
        $this
            ->addJoinObject($teamJoin, "teamJoin")
            ->where(PersonTeamLinkTableMap::TEAM_ID . " " . Criteria::IN . " (" . $id . ")");

        return $this;
    }
} // PersonQuery
