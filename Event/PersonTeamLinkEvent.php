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

use Symfony\Component\EventDispatcher\Event;

/**
 * Class PersonTeamLink
 * @package Team\Event
 */
class PersonTeamLinkEvent extends Event
{
    /**
     * @var \Team\Model\PersonTeamLink $personTeamLink
     */
    protected $personTeamLink;

    /**
     * @return \Team\Model\PersonTeamLink
     */
    public function getPersonTeamLink()
    {
        return $this->personTeamLink;
    }

    /**
     * @param \Team\Model\PersonTeamLink $personTeamLink
     */
    public function setPersonTeamLink($personTeamLink)
    {
        $this->personTeamLink = $personTeamLink;
    }

}