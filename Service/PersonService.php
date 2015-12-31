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
use Team\Event\PersonEvent;
use Team\Event\TeamEvents;
use Team\Model\Person;
use Team\Model\PersonQuery;
use Team\Service\Base\AbstractBaseService;
use Team\Service\Base\BaseServiceInterface;

/**
 * Class PersonService
 * @package Team\Service
 */
class PersonService extends AbstractBaseService implements BaseServiceInterface
{
    const EVENT_CREATE = TeamEvents::PERSON_CREATE;
    const EVENT_CREATE_BEFORE = TeamEvents::PERSON_CREATE_BEFORE;
    const EVENT_CREATE_AFTER = TeamEvents::PERSON_CREATE_AFTER;
    const EVENT_UPDATE = TeamEvents::PERSON_UPDATE;
    const EVENT_UPDATE_BEFORE = TeamEvents::PERSON_UPDATE_BEFORE;
    const EVENT_UPDATE_AFTER = TeamEvents::PERSON_UPDATE_AFTER;
    const EVENT_DELETE = TeamEvents::PERSON_DELETE;
    const EVENT_DELETE_BEFORE = TeamEvents::PERSON_DELETE_BEFORE;
    const EVENT_DELETE_AFTER = TeamEvents::PERSON_DELETE_AFTER;

    /**
     * @var PersonTeamLinkService $linkService
     */
    protected $linkService;

    protected function createProcess(Event $event)
    {
        /** @var PersonEvent $event */
        $event->getPerson()->save();
    }

    protected function updateProcess(Event $event)
    {
        /** @var PersonEvent $event */
        $event->getPerson()->save();
    }

    protected function deleteProcess(Event $event)
    {
        /** @var PersonEvent $event */
        $event->getPerson()->delete();
    }

    public function createFromArray($data, $locale = null)
    {
        $person = $this->hydrateObjectArray($data, $locale);

        $event = new PersonEvent();
        $event->setPerson($person);

        $this->create($event);

        if (isset($data["team_id"])) {
            $linkData = [
                "team_id" => $data["team_id"],
                "person_id" => $event->getPerson()->getId()
            ];

            $this->getLinkService()->createFromArray($linkData);
        }

        return $event->getPerson();
    }

    public function updateFromArray($data, $locale = null)
    {
        $person = $this->hydrateObjectArray($data, $locale);

        $event = new PersonEvent();
        $event->setPerson($person);

        $this->update($event);

        return $event->getPerson();
    }

    public function deleteFromId($id)
    {
        $person = PersonQuery::create()->findOneById($id);
        if ($person) {
            $event = new PersonEvent();
            $event->setPerson($person);

            $this->delete($event);
        }
    }

    protected function hydrateObjectArray($data, $locale = null)
    {
        $model = new Person();

        if (isset($data['id'])) {
            $person = PersonQuery::create()->findOneById($data['id']);
            if ($person) {
                $model = $person;
            }
        }

        if ($locale) {
            $model->setLocale($locale);
        }

        // Require Field
        if (isset($data['first_name'])) {
            $model->setFirstName($data['first_name']);
        }
        if (isset($data['last_name'])) {
            $model->setLastName($data['last_name']);
        }

        //  Optionnal Field
        if (isset($data['description'])) {
            $model->setDescription($data['description']);
        }

        return $model;
    }

    protected function getLinkService()
    {
        return $this->linkService;
    }

    public function setLinkService(PersonTeamLinkService $service)
    {
        $this->linkService = $service;
    }
}