<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace Team;

use Propel\Runtime\Connection\ConnectionInterface;
use Team\Model\PersonFunctionQuery;
use Team\Model\PersonImageQuery;
use Team\Model\PersonQuery;
use Team\Model\TeamQuery;
use Thelia\Install\Database;
use Thelia\Module\BaseModule;

class Team extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'team';

    /**
     * @inheritDoc
     */
    public function postActivation(ConnectionInterface $con = null)
    {
        try{
            TeamQuery::create()->findOne();
            PersonQuery::create()->findOne();
            PersonImageQuery::create()->findOne();
            PersonFunctionQuery::create()->findOne();
        }catch(\Exception $e){
            $database = new Database($con);
            $database->insertSql(null, [__DIR__ . "/Config/thelia.sql"]);
        }
    }

}
