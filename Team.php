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
use Thelia\Core\Template\TemplateDefinition;
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

    /**
     * @inheritDoc
     */
    public function update($currentVersion, $newVersion, ConnectionInterface $con = null)
    {
        if($currentVersion == "1.0" && $currentVersion != $newVersion){
            $database = new Database($con);
            $database->insertSql(null, [__DIR__ . "/Setup/update/sql/update-1.0-to-1.0.3.sql"]);
        }
    }


    public function getHooks(){
        return [
            array(
                "type" => TemplateDefinition::BACK_OFFICE,
                "code" => "team.extra.content.edit",
                "title" => "Team Extra Content",
                "description" => [
                    "en_US" =>"Allow you to insert element in modules tab on Team edit page",
                    "fr_FR" =>"Permet l'ajout de contenu sur la partie module de l'edition",
                ],
                "active" => true,
            ),
            array(
                "type" => TemplateDefinition::BACK_OFFICE,
                "code" => "team.edit.js",
                "title" => "Team Extra Js",
                "description" => [
                    "en_US" =>"Allow you to insert js on Team edit page",
                    "fr_FR" =>"Permet l'ajout de js sur l'edition",
                ],
                "active" => true,
            ),
            array(
                "type" => TemplateDefinition::BACK_OFFICE,
                "code" => "team.additional",
                "title" => "Team Extra Tab",
                "description" => [
                    "en_US" =>"Allow you to insert a tab on Team edit page",
                    "fr_FR" =>"Permet l'ajout d'une page sur l'edition d'une personne",
                ],
                "active" => true,
                "block" => true,
            ),
            array(
                "type" => TemplateDefinition::BACK_OFFICE,
                "code" => "team.edit.nav.bar",
                "title" => "Team Nav Tab",
                "description" => [
                    "en_US" =>"Allow you to insert link in navigation bar",
                    "fr_FR" =>"Permet l'ajout d'un lien sur la barre de navigation",
                ],
                "active" => true,
            ),
        ];
    }

}
