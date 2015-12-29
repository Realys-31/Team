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
/*************************************************************************************/

namespace Team\Event;



/**
 * Class TeamEvents
 * @package Team\Event
 */
class TeamEvents
{
    const TEAM_CREATE = "team.create";
    const TEAM_CREATE_BEFORE = "team.create.before";
    const TEAM_CREATE_AFTER = "team.create.after";
    const TEAM_UPDATE = "team.update";
    const TEAM_UPDATE_BEFORE = "team.update.before";
    const TEAM_UPDATE_AFTER = "team.update.after";
    const TEAM_DELETE = "team.delete";
    const TEAM_DELETE_BEFORE = "team.delete.before";
    const TEAM_DELETE_AFTER = "team.delete.after";

    const PERSON_CREATE = "person.create";
    const PERSON_CREATE_BEFORE = "person.create.before";
    const PERSON_CREATE_AFTER = "person.create.after";
    const PERSON_UPDATE = "person.update";
    const PERSON_UPDATE_BEFORE = "person.update.before";
    const PERSON_UPDATE_AFTER = "person.update.after";
    const PERSON_DELETE = "person.delete";
    const PERSON_DELETE_BEFORE = "person.delete.before";
    const PERSON_DELETE_AFTER = "person.delete.after";

    const PERSON_TEAM_LINK_CREATE = "person.team.link.create";
    const PERSON_TEAM_LINK_CREATE_BEFORE = "person.team.link.create.before";
    const PERSON_TEAM_LINK_CREATE_AFTER = "person.team.link.create.after";
    const PERSON_TEAM_LINK_UPDATE = "person.team.link.update";
    const PERSON_TEAM_LINK_UPDATE_BEFORE = "person.team.link.update.before";
    const PERSON_TEAM_LINK_UPDATE_AFTER = "person.team.link.update.after";
    const PERSON_TEAM_LINK_DELETE = "person.team.link.delete";
    const PERSON_TEAM_LINK_DELETE_BEFORE = "person.team.link.delete.before";
    const PERSON_TEAM_LINK_DELETE_AFTER = "person.team.link.delete.after";
}