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

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Team\Team;
use Thelia\Controller\Admin\FileController;
use Thelia\Core\Event\File\FileCreateOrUpdateEvent;
use Thelia\Core\Event\File\FileDeleteEvent;
use Thelia\Core\Event\File\FileToggleVisibilityEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\UpdateFilePositionEvent;
use Thelia\Core\HttpFoundation\Response;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Files\Exception\ProcessFileException;
use Thelia\Model\Lang;
use Thelia\Tools\Rest\ResponseRest;

/**
 * Class PersonImageController
 * @package Team\Controller
 */
class PersonImageController extends FileController
{
    /**
     * Manage how a file collection has to be saved
     *
     * @param  int      $parentId       Parent id owning files being saved
     * @param  string   $parentType     Parent Type owning files being saved (product, category, content, etc.)
     * @param  string   $objectType     Object type, e.g. image or document
     * @param  array    $validMimeTypes an array of valid mime types. If empty, any mime type is allowed.
     * @param  array    $extBlackList   an array of blacklisted extensions.
     * @return Response
     */
    public function saveFileAjaxAction(
        $parentId,
        $parentType,
        $objectType,
        $validMimeTypes = array(),
        $extBlackList = array()
    ) {
        if (null !== $response = $this->checkAuth(AdminResources::MODULE,Team::getModuleCode(), AccessManager::UPDATE)) {
            return $response;
        }

        $this->checkXmlHttpRequest();


        if ($this->getRequest()->isMethod('POST')) {
            /** @var UploadedFile $fileBeingUploaded */
            $fileBeingUploaded = $this->getRequest()->files->get('file');
            try {
                $this->processImage(
                    $fileBeingUploaded,
                    $parentId,
                    $parentType,
                    $objectType,
                    $validMimeTypes,
                    $extBlackList
                );
            } catch (ProcessFileException $e) {
                return new ResponseRest($e->getMessage(), 'text', $e->getCode());
            }

            return new ResponseRest(array('status' => true, 'message' => ''));
        }

        return new Response('', 404);
    }

    /**
     *
     * Process file uploaded
     *
     * @param UploadedFile $fileBeingUploaded
     * @param int $parentId
     * @param string $parentType
     * @param string $objectType
     * @param array $validMimeTypes
     * @param array $extBlackList
     * @return ResponseRest
     */
    public function processImage(
        $fileBeingUploaded,
        $parentId,
        $parentType,
        $objectType,
        $validMimeTypes = array(),
        $extBlackList = array()
    ) {
        $fileManager = $this->getFileManager();

        // Validate if file is too big
        if ($fileBeingUploaded->getError() == 1) {
            $message = $this->getTranslator()
                ->trans(
                    'File is too large, please retry with a file having a size less than %size%.',
                    array('%size%' => ini_get('upload_max_filesize')),
                    'core'
                );

            throw new ProcessFileException($message, 403);
        }

        $message = null;
        $realFileName = $fileBeingUploaded->getClientOriginalName();

        if (! empty($validMimeTypes)) {
            $mimeType = $fileBeingUploaded->getMimeType();

            if (!isset($validMimeTypes[$mimeType])) {
                $message = $this->getTranslator()
                    ->trans(
                        'Only files having the following mime type are allowed: %types%',
                        [ '%types%' => implode(', ', array_keys($validMimeTypes))]
                    );
            } else {
                $regex = "#^(.+)\.(".implode("|", $validMimeTypes[$mimeType]).")$#i";

                if (!preg_match($regex, $realFileName)) {
                    $message = $this->getTranslator()
                        ->trans(
                            "There's a conflict between your file extension \"%ext\" and the mime type \"%mime\"",
                            [
                                '%mime' => $mimeType,
                                '%ext' => $fileBeingUploaded->getClientOriginalExtension()
                            ]
                        );
                }
            }
        }

        if (!empty($extBlackList)) {
            $regex = "#^(.+)\.(".implode("|", $extBlackList).")$#i";

            if (preg_match($regex, $realFileName)) {
                $message = $this->getTranslator()
                    ->trans(
                        'Files with the following extension are not allowed: %extension, please do an archive of the file if you want to upload it',
                        [
                            '%extension' => $fileBeingUploaded->getClientOriginalExtension(),
                        ]
                    );
            }
        }

        if ($message !== null) {
            throw new ProcessFileException($message, 415);
        }

        $fileModel = $fileManager->getModelInstance($objectType, $parentType);

        $parentModel = $fileModel->getParentFileModel();

        if ($parentModel === null || $fileModel === null || $fileBeingUploaded === null) {
            throw new ProcessFileException('', 404);
        }

        $defaultTitle = $parentModel->getTitle();

        if (empty($defaultTitle)) {
            $defaultTitle = $fileBeingUploaded->getClientOriginalName();
        }

        $fileModel
            ->setParentId($parentId)
            ->setLocale(Lang::getDefaultLanguage()->getLocale())
            ->setTitle($defaultTitle)
        ;

        $fileCreateOrUpdateEvent = new FileCreateOrUpdateEvent($parentId);
        $fileCreateOrUpdateEvent->setModel($fileModel);
        $fileCreateOrUpdateEvent->setUploadedFile($fileBeingUploaded);
        $fileCreateOrUpdateEvent->setParentName($parentModel->getTitle());

        // Dispatch Event to the Action
        $this->dispatch(
            TheliaEvents::IMAGE_SAVE,
            $fileCreateOrUpdateEvent
        );

        $this->adminLogAppend(
            AdminResources::MODULE,
            AccessManager::UPDATE,
            $this->getTranslator()->trans(
                'Saving %obj% for %parentName% parent id %parentId%',
                array(
                    '%parentName%' => $fileCreateOrUpdateEvent->getParentName(),
                    '%parentId%' => $fileCreateOrUpdateEvent->getParentId(),
                    '%obj%' => $objectType
                )
            )
        );

        //return new ResponseRest(array('status' => true, 'message' => ''));
        return $fileCreateOrUpdateEvent;
    }

    /**
     * Manage how a image list will be displayed in AJAX
     *
     * @param int    $parentId   Parent id owning images being saved
     * @param string $parentType Parent Type owning images being saved
     *
     * @return Response
     */
    public function getImageListAjaxAction($parentId, $parentType)
    {
        $this->checkAuth(AdminResources::MODULE,Team::getModuleCode(), AccessManager::UPDATE);
        $this->checkXmlHttpRequest();
        $args = array('imageType' => $parentType, 'parentId' => $parentId);

        return $this->render('custom/image-upload-list-ajax', $args);
    }

    public function toggleVisibilityFileAction($documentId, $parentType, $objectType, $eventName)
    {
        $message = null;

        //$position = $this->getRequest()->request->get('position');

        $this->checkAuth(AdminResources::MODULE,Team::getModuleCode(), AccessManager::UPDATE);
        $this->checkXmlHttpRequest();

        $fileManager = $this->getFileManager();
        $modelInstance = $fileManager->getModelInstance($objectType, $parentType);

        $model = $modelInstance->getQueryInstance()->findPk($documentId);

        if ($model === null) {
            return $this->pageNotFound();
        }

        // Feed event
        $event = new FileToggleVisibilityEvent(
            $modelInstance->getQueryInstance(),
            $documentId
        );

        // Dispatch Event to the Action
        try {
            $this->dispatch($eventName, $event);
        } catch (\Exception $e) {
            $message = $this->getTranslator()->trans(
                'Fail to update %type% visibility: %err%',
                [ '%type%' => $objectType, '%err%' => $e->getMessage() ]
            );
        }

        if (null === $message) {
            $message = $this->getTranslator()->trans(
                '%type% visibility updated',
                [ '%type%' => ucfirst($objectType) ]
            );
        }

        return new Response($message);
    }

    public function toggleVisibilityImageAction($parentType, $documentId)
    {
        return $this->toggleVisibilityFileAction($documentId, $parentType, 'image', TheliaEvents::IMAGE_TOGGLE_VISIBILITY);
    }

    /**
     * Manage how a file has to be deleted
     *
     * @param int    $fileId     Parent id owning file being deleted
     * @param string $parentType Parent Type owning file being deleted
     * @param string $objectType the type of the file, image or document
     * @param string $eventName  the event type.
     *
     * @return Response
     */
    public function deleteFileAction($fileId, $parentType, $objectType, $eventName)
    {
        $message = null;

        $this->checkAuth(AdminResources::MODULE,Team::getModuleCode(), AccessManager::UPDATE);
        $this->checkXmlHttpRequest();

        $fileManager = $this->getFileManager();
        $modelInstance = $fileManager->getModelInstance($objectType, $parentType);

        $model = $modelInstance->getQueryInstance()->findPk($fileId);

        if ($model == null) {
            return $this->pageNotFound();
        }

        // Feed event
        $fileDeleteEvent = new FileDeleteEvent($model);

        // Dispatch Event to the Action
        try {
            $this->dispatch($eventName, $fileDeleteEvent);

            $this->adminLogAppend(
                AdminResources::MODULE,
                AccessManager::UPDATE,
                $this->getTranslator()->trans(
                    'Deleting %obj% for %id% with parent id %parentId%',
                    array(
                        '%obj%' => $objectType,
                        '%id%' => $fileDeleteEvent->getFileToDelete()->getId(),
                        '%parentId%' => $fileDeleteEvent->getFileToDelete()->getParentId(),
                    )
                ),
                $fileDeleteEvent->getFileToDelete()->getId()
            );
        } catch (\Exception $e) {
            $message = $this->getTranslator()->trans(
                'Fail to delete  %obj% for %id% with parent id %parentId% (Exception : %e%)',
                array(
                    '%obj%' => $objectType,
                    '%id%' => $fileDeleteEvent->getFileToDelete()->getId(),
                    '%parentId%' => $fileDeleteEvent->getFileToDelete()->getParentId(),
                    '%e%' => $e->getMessage()
                )
            );

            $this->adminLogAppend(
                AdminResources::MODULE,
                AccessManager::UPDATE,
                $message,
                $fileDeleteEvent->getFileToDelete()->getId()
            );
        }

        if (null === $message) {
            $message = $this->getTranslator()->trans(
                '%obj%s deleted successfully',
                ['%obj%' => ucfirst($objectType)],
                'image'
            );
        }

        return new Response($message);
    }

    public function updateFilePositionAction($parentType, $parentId, $objectType, $eventName)
    {
        $message = null;

        $position = $this->getRequest()->request->get('position');

        $this->checkAuth(AdminResources::MODULE,Team::getModuleCode(), AccessManager::UPDATE);
        $this->checkXmlHttpRequest();

        $fileManager = $this->getFileManager();
        $modelInstance = $fileManager->getModelInstance($objectType, $parentType);
        $model = $modelInstance->getQueryInstance()->findPk($parentId);

        if ($model === null || $position === null) {
            return $this->pageNotFound();
        }

        // Feed event
        $event = new UpdateFilePositionEvent(
            $modelInstance->getQueryInstance($parentType),
            $parentId,
            UpdateFilePositionEvent::POSITION_ABSOLUTE,
            $position
        );

        // Dispatch Event to the Action
        try {
            $this->dispatch($eventName, $event);
        } catch (\Exception $e) {
            $message = $this->getTranslator()->trans(
                'Fail to update %type% position: %err%',
                [ '%type%' => $objectType, '%err%' => $e->getMessage() ]
            );
        }

        if (null === $message) {
            $message = $this->getTranslator()->trans(
                '%type% position updated',
                [ '%type%' => ucfirst($objectType) ]
            );
        }

        return new Response($message);
    }

}