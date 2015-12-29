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

namespace Team\Controller\Base;

use Team\Team;
use Propel\Runtime\Propel;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Form\Exception\FormValidationException;

/**
 * Class BaseController
 * @package Team\Controller\Base
 */
abstract class BaseController extends BaseAdminController
{
    protected $useFallbackTemplate = true;
    /**
     * Name of entity associated with controller
     */
    const CONTROLLER_ENTITY_NAME = null;

    /**
     * Current Service Associated to controller
     */
    protected $service;


    // ABSTRACT FUNCTIONS

    /**
     * Use to get render of list
     * @return mixed
     */
    abstract protected function getListRenderTemplate();

    /**
     * Must return a RedirectResponse instance
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    abstract protected function redirectToListTemplate();

    /**
     * Use to get Edit render
     * @return mixed
     */
    abstract protected function getEditRenderTemplate();

    /**
     * Use to get Create render
     * @return mixed
     */
    abstract protected function getCreateRenderTemplate();

    /**
     * @return mixed
     */
    abstract protected function getObjectId($object);

    /**
     * Load an existing object from the database
     */
    abstract protected function getExistingObject();

    /**
     * Hydrate the update form for this object, before passing it to the update template
     *
     * @param mixed $object
     */
    abstract protected function hydrateObjectForm($object);


    // PUBLIC METHODS

    /**
     * The default action is displaying the list.
     *
     * @return \Thelia\Core\HttpFoundation\Response the response
     */
    public function defaultAction()
    {
        // Check current user authorization
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, Team::getModuleCode(),
                AccessManager::VIEW)
        ) {
            return $response;
        }

        return $this->getListRenderTemplate();
    }

    /**
     * Create an object
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        // Check current user authorization
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, Team::getModuleCode(),
                AccessManager::CREATE)
        ) {
            return $response;
        }

        // Create the Creation Form
        $creationForm = $this->getCreationForm($this->getRequest());

        $con = Propel::getConnection();
        $con->beginTransaction();

        try {
            // Check the form against constraints violations
            $form = $this->validateForm($creationForm, "POST");
            // Get the form field values
            $data = $form->getData();

            $createdObject = $this->getService()->createFromArray($data, $this->getCurrentEditionLocale());


            // Substitute _ID_ in the URL with the ID of the created object
            $successUrl = str_replace('_ID_', $this->getObjectId($createdObject), $creationForm->getSuccessUrl());

            $con->commit();

            // Redirect to the success URL
            return $this->generateRedirect($successUrl);

        } catch (FormValidationException $ex) {
            $con->rollBack();
            // Form cannot be validated
            $error_msg = $this->createStandardFormValidationErrorMessage($ex);
        } catch (\Exception $ex) {
            $con->rollBack();
            // Any other error
            $error_msg = $ex->getMessage();
        }
        if (false !== $error_msg) {
            $this->setupFormErrorContext(
                $this->getTranslator()->trans("%obj creation", ['%obj' => static::CONTROLLER_ENTITY_NAME]),
                $error_msg,
                $creationForm,
                $ex
            );

            // At this point, the form has error, and should be redisplayed.
            return $this->getListRenderTemplate();
        }

    }

    /**
     * Load a object for modification, and display the edit template.
     *
     * @return \Thelia\Core\HttpFoundation\Response the response
     */
    public function updateAction()
    {
        // Check current user authorization
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, Team::getModuleCode(),
                AccessManager::UPDATE)
        ) {
            return $response;
        }

        // Load object if exist
        if (null !== $object = $this->getExistingObject()) {
            // Hydrate the form abd pass it to the parser
            $changeForm = $this->hydrateObjectForm($object);

            // Pass it to the parser
            $this->getParserContext()->addForm($changeForm);
        }

        // Render the edition template.
        return $this->getEditRenderTemplate();
    }

    /**
     * Save changes on a modified object, and either go back to the object list, or stay on the edition page.
     *
     * @return \Thelia\Core\HttpFoundation\Response the response
     */
    public function processUpdateAction()
    {
        // Check current user authorization
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, Team::getModuleCode(),
                AccessManager::UPDATE)
        ) {
            return $response;
        }

        // Error (Default: false)
        $error_msg = false;

        // Create the Form from the request
        $changeForm = $this->getUpdateForm($this->getRequest());


        $con = Propel::getConnection();
        $con->beginTransaction();

        try {
            // Check the form against constraints violations
            $form = $this->validateForm($changeForm, "POST");

            // Get the form field values
            $data = $form->getData();

            $updatedObject = $this->getService()->updateFromArray($data, $this->getCurrentEditionLocale());

            // Check if object exist
            if (!$updatedObject) {
                throw new \LogicException(
                    $this->getTranslator()->trans("No %obj was updated.", ['%obj' => static::CONTROLLER_ENTITY_NAME])
                );
            }

            $con->commit();
            // If we have to stay on the same page, do not redirect to the successUrl,
            // just redirect to the edit page again.
            if ($this->getRequest()->get('save_mode') == 'stay') {
                return $this->redirectToEditionTemplate($this->getRequest());
            }

            // Redirect to the success URL
            return $this->generateSuccessRedirect($changeForm);

        } catch (FormValidationException $ex) {
            $con->rollBack();
            // Form cannot be validated
            $error_msg = $this->createStandardFormValidationErrorMessage($ex);
        } catch (\Exception $ex) {
            $con->rollBack();
            // Any other error
            $error_msg = $ex->getMessage();
        }

        if (false !== $error_msg) {
            // At this point, the form has errors, and should be redisplayed.
            $this->setupFormErrorContext(
                $this->getTranslator()->trans("%obj modification", ['%obj' => static::CONTROLLER_ENTITY_NAME]),
                $error_msg,
                $changeForm,
                $ex
            );

            return $this->getEditRenderTemplate();
        }
    }

    /**
     * Delete an object
     *
     * @return \Thelia\Core\HttpFoundation\Response the response
     */
    public function deleteAction()
    {
        // Check current user authorization
        if (null !== $response = $this->checkAuth(AdminResources::MODULE, Team::getModuleCode(),
                AccessManager::DELETE)
        ) {
            return $response;
        }

        $con = Propel::getConnection();
        $con->beginTransaction();
        try {
            // Check token
            $this->getTokenProvider()->checkToken(
                $this->getRequest()->query->get("_token")
            );

            $this->getService()->deleteFromId($this->getRequest()->request->get(static::CONTROLLER_ENTITY_NAME . "_id"));
            $con->commit();
            if ($response == null) {
                return $this->redirectToListTemplate();
            } else {
                return $response;
            }
        } catch (\Exception $e) {
            $con->rollBack();

            return $this->renderAfterDeleteError($e);
        }
    }

    // HELPERS
    /**
     * Method to get current controller associated service
     * @return object
     */
    protected function getService()
    {
        if (!$this->service) {
            $this->service = $this->getContainer()->get(static::CONTROLLER_ENTITY_NAME . "_service");
        }

        return $this->service;
    }

    /**
     * Method to get Base Creation Form
     * @return \Thelia\Form\BaseForm
     */
    protected function getCreationForm()
    {
        return $this->createForm(static::CONTROLLER_ENTITY_NAME . ".create");
    }

    /**
     * Method to get Base Update Form
     * @param array $data
     * @return \Thelia\Form\BaseForm
     */
    protected function getUpdateForm($data = [])
    {
        if (!is_array($data)) {
            $data = [];
        }

        return $this->createForm(static::CONTROLLER_ENTITY_NAME . ".update", "form", $data);
    }

    /**
     * @param \Exception $e
     * @return \Thelia\Core\HttpFoundation\Response
     */
    protected function renderAfterDeleteError(\Exception $e)
    {
        $errorMessage = sprintf(
            "Unable to delete '%s'. Error message: %s",
            static::CONTROLLER_ENTITY_NAME,
            $e->getMessage()
        );

        $this->getParserContext()
            ->setGeneralError($errorMessage);

        return $this->defaultAction();
    }


}