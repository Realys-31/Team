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
 * Class FunctionCreateForm
 * @package Team\Form
 */
class FunctionCreateForm extends BaseForm
{

    /**
     * @inheritDoc
     */
    protected function buildForm()
    {
        $this->formBuilder
            ->add("label", "text", array(
                "label" => $this->translator->trans("Label", [], Team::DOMAIN_NAME),
                "label_attr" => ["for" => "attr-function-label"],
                "required" => true,
                "constraints" => array(new NotBlank(),),
                "attr" => array()
            ))
            ->add("code", "text", array(
                "label" => $this->translator->trans("Code", [], Team::DOMAIN_NAME),
                "label_attr" => ["for" => "attr-function-code"],
                "required" => true,
                "constraints" => array(new NotBlank(),),
                "attr" => array()
            ))
            ->add("locale", "text", array(
                "constraints" => [
                    new NotBlank(),
                ],
                "label_attr" => array("for" => "locale_create"),
            ))
            ;
    }

    public function getName()
    {
        return "person_function_create";
    }
}