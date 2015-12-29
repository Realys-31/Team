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

namespace Team\Form;

use Symfony\Component\Validator\Constraints\NotBlank;
use Team\Team;
use Thelia\Form\BaseForm;

/**
 * Class PersonCreateForm
 * @package Team\Form
 */
class PersonCreateForm extends BaseForm
{

    /**
     * @inheritDoc
     */
    protected function buildForm()
    {
        $this->formBuilder
            ->add("first_name", "text", array(
                "label" => $this->translator->trans("First Name", [], Team::DOMAIN_NAME),
                "label_attr" => ["for" => "attr-person-first-name"],
                "required" => true,
                "constraints" => array(new NotBlank(),),
                "attr" => array()
            ))
            ->add("last_name", "text", array(
                "label" => $this->translator->trans("Last Name", [], Team::DOMAIN_NAME),
                "label_attr" => ["for" => "attr-person-last-name"],
                "required" => true,
                "constraints" => array(new NotBlank(),),
                "attr" => array()
            ))
            ->add("description", "text", array(
                "label" => $this->translator->trans("Description", [], Team::DOMAIN_NAME),
                "label_attr" => ["for" => "attr-person-descp"],
                "required" => false,
                "attr" => array()
            ))
            ->add("locale", "text", array(
                "constraints" => [
                    new NotBlank(),
                ],
                "label_attr" => array("for" => "locale_create"),
            ))
            ->add('team_id', 'integer', array(
                "label" => $this->translator->trans("Team Id", [], Team::DOMAIN_NAME),
                "label_attr" => ["for" => "attr-person-team-id"],
                "required" => true,
                "constraints" => array(new NotBlank(),),
                "attr" => array()
            ))
        ;
    }

    public function getName()
    {
        return "person_create";
    }
}