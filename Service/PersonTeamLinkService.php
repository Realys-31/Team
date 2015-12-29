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

namespace Team\Service;

use Symfony\Component\EventDispatcher\Event;
use Team\Event\PersonTeamLinkEvent;
use Team\Event\TeamEvents;
use Team\Model\PersonTeamLink;
use Team\Model\PersonTeamLinkQuery;
use Team\Service\Base\AbstractBaseService;
use Team\Service\Base\BaseServiceInterface;

/**
 * Class PersonTeamLinkService
 * @package Team\Service
 */
class PersonTeamLinkService extends AbstractBaseService implements BaseServiceInterface
{

    const EVENT_CREATE = TeamEvents::PERSON_TEAM_LINK_CREATE;
    const EVENT_CREATE_BEFORE = TeamEvents::PERSON_TEAM_LINK_CREATE_BEFORE;
    const EVENT_CREATE_AFTER = TeamEvents::PERSON_TEAM_LINK_CREATE_AFTER;
    const EVENT_UPDATE = TeamEvents::PERSON_TEAM_LINK_UPDATE;
    const EVENT_UPDATE_BEFORE = TeamEvents::PERSON_TEAM_LINK_UPDATE_BEFORE;
    const EVENT_UPDATE_AFTER = TeamEvents::PERSON_TEAM_LINK_UPDATE_AFTER;
    const EVENT_DELETE = TeamEvents::PERSON_TEAM_LINK_DELETE;
    const EVENT_DELETE_BEFORE = TeamEvents::PERSON_TEAM_LINK_DELETE_BEFORE;
    const EVENT_DELETE_AFTER = TeamEvents::PERSON_TEAM_LINK_DELETE_AFTER;

    protected function createProcess(Event $event)
    {
        /** @var PersonTeamLinkEvent $event */
        $event->getPersonTeamLink()->save();
    }

    protected function updateProcess(Event $event)
    {
        /** @var PersonTeamLinkEvent $event */
        $event->getPersonTeamLink()->save();
    }

    protected function deleteProcess(Event $event)
    {
        /** @var PersonTeamLinkEvent $event */
        $event->getPersonTeamLink()->delete();
    }

    public function createFromArray($data, $locale = null)
    {
        $link = $this->hydrateObjectArray($data, $locale);

        $event = new PersonTeamLinkEvent();
        $event->setPersonTeamLink($link);

        $this->create($event);

        return $event->getPersonTeamLink();
    }

    public function updateFromArray($data, $locale = null)
    {
        $link = $this->hydrateObjectArray($data, $locale);

        $event = new PersonTeamLinkEvent();
        $event->setPersonTeamLink($link);

        $this->create($event);

        return $event->getPersonTeamLink();
    }

    public function deleteFromId($id)
    {
        $link = PersonTeamLinkQuery::create()->findOneById($id);
        if ($link) {
            $event = new PersonTeamLinkEvent();
            $event->setPersonTeamLink($link);

            $this->delete($event);
        }
    }

    protected function hydrateObjectArray($data, $locale = null)
    {
        $model = new PersonTeamLink();

        if (isset($data['id'])) {
            $link = PersonTeamLinkQuery::create()->findOneById($data['id']);
            if ($link) {
                $model = $link;
            }
        }

        if(isset($data["team_id"]) && isset($data["person_id"])){
            $link = PersonTeamLinkQuery::create()->filterByPersonId($data["person_id"])->filterByTeamId($data["team_id"])->findOne();
            if ($link) {
                throw new \Exception("A link already exist",403);
            }

            $model->setTeamId($data["team_id"]);
            $model->setPersonId($data["person_id"]);
        }

        return $model;
    }
}