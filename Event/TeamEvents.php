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
}