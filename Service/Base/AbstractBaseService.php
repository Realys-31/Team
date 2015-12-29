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

namespace Team\Service\Base;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Thelia\Core\Event\ActionEvent;

/**
 * Class BaseService
 * @package Team\Service\Base
 */
abstract class AbstractBaseService
{
    const EVENT_CREATE = null;
    const EVENT_CREATE_BEFORE = null;
    const EVENT_CREATE_AFTER = null;
    const EVENT_UPDATE = null;
    const EVENT_UPDATE_BEFORE = null;
    const EVENT_UPDATE_AFTER = null;
    const EVENT_DELETE = null;
    const EVENT_DELETE_BEFORE = null;
    const EVENT_DELETE_AFTER = null;

    protected $dispatcher;

    //CREATION

    /**
     * Allow to proccess Creation Even Sender
     * @param Event $event
     */
    protected function create(Event $event)
    {
        $this->dispatch(static::EVENT_CREATE_BEFORE, $event);
        if (!$event->isPropagationStopped()) {
            $this->createProcess($event);
            if (!$event->isPropagationStopped()) {
                $this->dispatch(static::EVENT_CREATE_AFTER, $event);
            }
        }
    }

    /**
     * Allow to create an object from an Event
     * @param Event $event
     */
    protected function createProcess(Event $event)
    {
        $this->dispatch(static::EVENT_CREATE, $event);
    }

    // UPDATE

    /**
     * Allow to send Update Events
     * @param Event $event
     */
    protected function update(Event $event)
    {
        $this->dispatch(static::EVENT_UPDATE_BEFORE, $event);
        if (!$event->isPropagationStopped()) {
            $this->updateProcess($event);
            if (!$event->isPropagationStopped()) {
                $this->dispatch(static::EVENT_UPDATE_AFTER, $event);
            }
        }
    }


    /**
     * Allow to update an object from an event
     * @param Event $event
     */
    protected function updateProcess(Event $event)
    {
        $this->dispatch(static::EVENT_UPDATE, $event);
    }

    // DELETE

    protected function delete(Event $event)
    {
        $this->dispatch(static::EVENT_DELETE_BEFORE, $event);
        if (!$event->isPropagationStopped()) {
            $this->deleteProcess($event);
            if (!$event->isPropagationStopped()) {
                $this->dispatch(static::EVENT_DELETE_AFTER, $event);
            }
        }
    }

    protected function deleteProcess(Event $event)
    {
        $this->dispatch(static::EVENT_DELETE, $event);
    }


    // DEPENDENCIES

    /**
     * Dispatch a Thelia event
     *
     * @param string $eventName a TheliaEvent name, as defined in TheliaEvents class
     * @param ActionEvent $event the action event, or null (a DefaultActionEvent will be dispatched)
     */
    protected function dispatch($eventName, Event $event = null)
    {
        if ($event == null) {
            $event = new DefaultActionEvent();
        }

        $this->getDispatcher()->dispatch($eventName, $event);
    }

    /**
     * Return the event dispatcher,
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    public function setDispatcher(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }


}