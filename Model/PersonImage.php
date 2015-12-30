<?php

namespace Team\Model;

use Team\Model\Base\PersonImage as BasePersonImage;
use Thelia\Files\FileModelInterface;
use Thelia\Model\ConfigQuery;

class PersonImage extends BasePersonImage implements FileModelInterface
{

    /**
     * @inheritDoc
     */
    public function setParentId($parentId)
    {
        return $this->setPersonId($parentId);
    }

    /**
     * @inheritDoc
     */
    public function getParentId()
    {
        return $this->getPersonId();
    }

    /**
     * @inheritDoc
     */
    public function getParentFileModel()
    {
        return new Person();
    }

    /**
     * @inheritDoc
     */
    public function getUpdateFormId()
    {

    }

    /**
     * @inheritDoc
     */
    public function getUploadDir()
    {
        $uploadDir = ConfigQuery::read('images_library_path');
        if ($uploadDir === null) {
            $uploadDir = THELIA_LOCAL_DIR . 'media' . DS . 'images';
        } else {
            $uploadDir = THELIA_ROOT . $uploadDir;
        }

        return $uploadDir . DS . 'person';
    }

    /**
     * @inheritDoc
     */
    public function getRedirectionUrl()
    {
        return '/admin/Team/person/update?person_id=' . $this->getPersonId();
    }

    /**
     * @inheritDoc
     */
    public function getQueryInstance()
    {
        return PersonImageQuery::create();
    }

    public function setDispatcher($dispatcher)
    {
        return $this;
    }

}
