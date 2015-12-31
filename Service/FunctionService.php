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
use Team\Event\FunctionEvent;
use Team\Event\TeamEvents;
use Team\Model\PersonFunction;
use Team\Model\PersonFunctionQuery;
use Team\Service\Base\AbstractBaseService;
use Team\Service\Base\BaseServiceInterface;

/**
 * Class FunctionService
 * @package Team\Service
 */
class FunctionService extends AbstractBaseService implements BaseServiceInterface
{
    const EVENT_CREATE = TeamEvents::FUNCTION_CREATE;
    const EVENT_CREATE_BEFORE = TeamEvents::FUNCTION_CREATE_BEFORE;
    const EVENT_CREATE_AFTER = TeamEvents::FUNCTION_CREATE_AFTER;
    const EVENT_UPDATE = TeamEvents::FUNCTION_UPDATE;
    const EVENT_UPDATE_BEFORE = TeamEvents::FUNCTION_UPDATE_BEFORE;
    const EVENT_UPDATE_AFTER = TeamEvents::FUNCTION_UPDATE_AFTER;
    const EVENT_DELETE = TeamEvents::FUNCTION_DELETE;
    const EVENT_DELETE_BEFORE = TeamEvents::FUNCTION_DELETE_BEFORE;
    const EVENT_DELETE_AFTER = TeamEvents::FUNCTION_DELETE_AFTER;

    protected function createProcess(Event $event)
    {
        /** @var FunctionEvent $event */
        $event->getPersonFunction()->save();
    }

    protected function updateProcess(Event $event)
    {
        /** @var FunctionEvent $event */
        $event->getPersonFunction()->save();
    }

    protected function deleteProcess(Event $event)
    {
        /** @var FunctionEvent $event */
        $event->getPersonFunction()->delete();
    }

    public function createFromArray($data, $locale = null)
    {
        $personFunction = $this->hydrateObjectArray($data, $locale);

        $event = new FunctionEvent();
        $event->setPersonFunction($personFunction);

        $this->create($event);

        return $event->getPersonFunction();
    }

    public function updateFromArray($data, $locale = null)
    {
        $personFunction = $this->hydrateObjectArray($data, $locale);

        $event = new FunctionEvent();
        $event->setPersonFunction($personFunction);

        $this->update($event);

        return $event->getPersonFunction();
    }

    public function deleteFromId($id)
    {
        $personFunction = PersonFunctionQuery::create()->findOneById($id);
        if ($personFunction) {
            $event = new FunctionEvent();
            $event->setPersonFunction($personFunction);

            $this->delete($event);
        }
    }

    protected function hydrateObjectArray($data, $locale = null)
    {
        $model = new PersonFunction();

        if (isset($data['id'])) {
            $personFunction = PersonFunctionQuery::create()->findOneById($data['id']);
            if ($personFunction) {
                $model = $personFunction;
            }
        }

        if ($locale) {
            $model->setLocale($locale);
        }

        // Require Field
        if (isset($data['code'])) {
            $model->setCode(strtoupper($data['code']));
        }
        if (isset($data['label'])) {
            $model->setLabel($data['label']);
        }

        return $model;
    }


}