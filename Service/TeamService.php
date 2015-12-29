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
use Team\Event\TeamEvent;
use Team\Event\TeamEvents;
use Team\Model\Team;
use Team\Model\TeamQuery;
use Team\Service\Base\AbstractBaseService;
use Team\Service\Base\BaseServiceInterface;

/**
 * Class TeamService
 * @package Team\Service
 */
class TeamService extends AbstractBaseService implements BaseServiceInterface
{

    const EVENT_CREATE = TeamEvents::TEAM_CREATE;
    const EVENT_CREATE_BEFORE = TeamEvents::TEAM_CREATE_BEFORE;
    const EVENT_CREATE_AFTER = TeamEvents::TEAM_CREATE_AFTER;
    const EVENT_UPDATE = TeamEvents::TEAM_UPDATE;
    const EVENT_UPDATE_BEFORE = TeamEvents::TEAM_UPDATE_BEFORE;
    const EVENT_UPDATE_AFTER = TeamEvents::TEAM_UPDATE_AFTER;
    const EVENT_DELETE = TeamEvents::TEAM_DELETE;
    const EVENT_DELETE_BEFORE = TeamEvents::TEAM_DELETE_BEFORE;
    const EVENT_DELETE_AFTER = TeamEvents::TEAM_DELETE_AFTER;

    
    protected function createProcess(Event $event)
    {
        /** @var TeamEvent $event */
        $event->getTeam()->save();
    }

    protected function updateProcess(Event $event)
    {
        /** @var TeamEvent $event */
        $event->getTeam()->save();
    }

    protected function deleteProcess(Event $event)
    {
        /** @var TeamEvent $event */
        $event->getTeam()->delete();
    }

    public function createFromArray($data, $locale = null)
    {
        $team = $this->hydrateObjectArray($data, $locale);

        $event = new TeamEvent();
        $event->setTeam($team);

        $this->create($event);

        return $event->getTeam();
    }

    public function updateFromArray($data, $locale = null)
    {
        $team = $this->hydrateObjectArray($data, $locale);

        $event = new TeamEvent();
        $event->setTeam($team);

        $this->create($event);

        return $event->getTeam();
    }

    public function deleteFromId($id)
    {
        $team = TeamQuery::create()->findOneById($id);
        if ($team) {
            $event = new TeamEvent();
            $event->setTeam($team);

            $this->delete($event);
        }
    }

    protected function hydrateObjectArray($data, $locale = null)
    {
        $model = new Team();

        if (isset($data['id'])) {
            $team = TeamQuery::create()->findOneById($data['id']);
            if ($team) {
                $model = $team;
            }
        }

        if ($locale) {
            $model->setLocale($locale);
        }

        // Require Field
        if (isset($data['title'])) {
            $model->setTitle($data['title']);
        }

        //  Optionnal Field
        if (isset($data['description'])) {
            $model->setDescription($data['description']);
        }

        return $model;
    }
}