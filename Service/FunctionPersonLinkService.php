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
use Team\Event\FunctionPersonLinkEvent;
use Team\Event\TeamEvents;
use Team\Model\PersonFunctionLink;
use Team\Model\PersonFunctionLinkQuery;
use Team\Service\Base\AbstractBaseService;
use Team\Service\Base\BaseServiceInterface;

/**
 * Class FunctionPersonLinkService
 * @package Team\Service
 */
class FunctionPersonLinkService extends AbstractBaseService implements BaseServiceInterface
{
    const EVENT_CREATE = TeamEvents::FUNCTION_PERSON_LINK_CREATE;
    const EVENT_CREATE_BEFORE = TeamEvents::FUNCTION_PERSON_LINK_CREATE_BEFORE;
    const EVENT_CREATE_AFTER = TeamEvents::FUNCTION_PERSON_LINK_CREATE_AFTER;
    const EVENT_UPDATE = TeamEvents::FUNCTION_PERSON_LINK_UPDATE;
    const EVENT_UPDATE_BEFORE = TeamEvents::FUNCTION_PERSON_LINK_UPDATE_BEFORE;
    const EVENT_UPDATE_AFTER = TeamEvents::FUNCTION_PERSON_LINK_UPDATE_AFTER;
    const EVENT_DELETE = TeamEvents::FUNCTION_PERSON_LINK_DELETE;
    const EVENT_DELETE_BEFORE = TeamEvents::FUNCTION_PERSON_LINK_DELETE_BEFORE;
    const EVENT_DELETE_AFTER = TeamEvents::FUNCTION_PERSON_LINK_DELETE_AFTER;

    protected function createProcess(Event $event)
    {
        /** @var FunctionPersonLinkEvent $event */
        $event->getPersonFunctionLink()->save();
    }

    protected function updateProcess(Event $event)
    {
        /** @var FunctionPersonLinkEvent $event */
        $event->getPersonFunctionLink()->save();
    }

    protected function deleteProcess(Event $event)
    {
        /** @var FunctionPersonLinkEvent $event */
        $event->getPersonFunctionLink()->delete();
    }

    public function createFromArray($data, $locale = null)
    {
        $link = $this->hydrateObjectArray($data);

        $event = new FunctionPersonLinkEvent();
        $event->setPersonFunctionLink($link);

        $this->create($event);

        return $event->getPersonFunctionLink();
    }

    public function updateFromArray($data, $locale = null)
    {
        $link = $this->hydrateObjectArray($data);

        $event = new FunctionPersonLinkEvent();
        $event->setPersonFunctionLink($link);

        $this->update($event);

        return $event->getPersonFunctionLink();
    }

    public function deleteFromId($id)
    {
        $link = PersonFunctionLinkQuery::create()->findOneById($id);
        if ($link) {
            $event = new FunctionPersonLinkEvent();
            $event->setPersonFunctionLink($link);

            $this->delete($event);
        }
    }

    protected function hydrateObjectArray($data)
    {
        $model = new PersonFunctionLink();

        if (isset($data['id'])) {
            $link = PersonFunctionLinkQuery::create()->findOneById($data['id']);
            if ($link) {
                $model = $link;
            }
        }

        if(isset($data["function"]) && isset($data["person"])){
            $link = PersonFunctionLinkQuery::create()->filterByPersonId($data["person"])->filterByFunctionId($data["function"])->findOne();
            if ($link) {
                throw new \Exception("A link already exist",403);
            }

            $model->setFunctionId($data["function"]);
            $model->setPersonId($data["person"]);
        }

        return $model;
    }
}