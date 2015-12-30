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
use Team\Model\PersonQuery;
use Thelia\Tools\URL;
use Team\Model\Person;

/**
 * Class PersonController
 * @package Team\Controller
 */
class PersonController extends BaseController
{

    const CONTROLLER_ENTITY_NAME = "person";

    /**
     * @inheritDoc
     */
    protected function getListRenderTemplate()
    {
        return $this->render("team");
    }

    /**
     * @inheritDoc
     */
    protected function redirectToListTemplate()
    {
        return new RedirectResponse(URL::getInstance()->absoluteUrl("/admin/module/Team/team"));
    }

    /**
     * @inheritDoc
     */
    protected function getEditRenderTemplate()
    {
        return $this->render("person-edit");
    }

    /**
     * @inheritDoc
     */
    protected function getCreateRenderTemplate()
    {
        return $this->render("team");
    }

    /**
     * @var Person object
     * @inheritDoc
     */
    protected function getObjectId($object)
    {
        return $object->getId();
    }

    /**
     * @inheritDoc
     */
    protected function getExistingObject()
    {
        return PersonQuery::create()->findOneById($this->getRequest()->query->get("person_id"));
    }

    /**
     * @var Person object
     * @inheritDoc
     */
    protected function hydrateObjectForm($object)
    {
        $data = [
            "id" => $object->getId(),
            "first_name" => $object->getFirstName(),
            "last_name" => $object->getLastName(),
            "description" => $object->getDescription()
        ];

        return $this->getUpdateForm($data);
    }

    protected function redirectToEditionTemplate($request){
        $id = $this->getRequest()->query->get("person_id");
        return new RedirectResponse(URL::getInstance()->absoluteUrl("/admin/module/Team/person/update",["person_id" => $id]));
    }
}