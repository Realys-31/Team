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

namespace Team\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Team\Controller\Base\BaseController;
use Team\Model\PersonFunction;
use Team\Model\PersonFunctionQuery;
use Thelia\Tools\URL;

/**
 * Class FunctionController
 * @package Team\Controller
 */
class FunctionController extends BaseController
{
    const CONTROLLER_ENTITY_NAME = "person_function";

    /**
     * @inheritDoc
     */
    protected function getListRenderTemplate()
    {
        return $this->render("person-edit",["person_id" => $this->getRequest()->query->get("person_id")]);
    }

    /**
     * @inheritDoc
     */
    protected function redirectToListTemplate()
    {
        return new RedirectResponse(URL::getInstance()->absoluteUrl("/admin/module/Team/person/update#function",["person_id" => $this->getRequest()->query->get("person_id")]));
    }

    /**
     * @inheritDoc
     */
    protected function getEditRenderTemplate()
    {
        return $this->getListRenderTemplate();
    }

    /**
     * @inheritDoc
     */
    protected function getCreateRenderTemplate()
    {
        return $this->render("person-edit",["person_id" => $this->getRequest()->query->get("person_id")]);
    }

    /**
     * @inheritDoc
     */
    protected function getObjectId($object)
    {
        /**
         * @var PersonFunction $object
         */
        return $object->getId();
    }

    /**
     * @inheritDoc
     */
    protected function getExistingObject()
    {
        return PersonFunctionQuery::create()->findOneById($this->getRequest()->query->get("function_id"));
    }

    /**
     * @inheritDoc
     */
    protected function hydrateObjectForm($object)
    {
        return null;
    }
}