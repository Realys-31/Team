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
use Team\Model\Team;
use Thelia\Tools\URL;

/**
 * Class TeamController
 * @package Team\Controller
 */
class TeamController extends BaseController
{
    const CONTROLLER_ENTITY_NAME = "team";

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
        // TODO: Implement getEditRenderTemplate() method.
    }

    /**
     * @inheritDoc
     */
    protected function getCreateRenderTemplate()
    {
        // TODO: Implement getCreateRenderTemplate() method.
    }

    /**
     * @inheritDoc
     * @var Team $object
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
        // TODO: Implement getExistingObject() method.
    }

    /**
     * @inheritDoc
     */
    protected function hydrateObjectForm($object)
    {
        // TODO: Implement hydrateObjectForm() method.
    }
}