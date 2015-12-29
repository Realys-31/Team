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
 * Class TeamCreateForm
 * @package Team\Form
 */
class TeamCreateForm extends BaseForm
{

    /**
     * @inheritDoc
     */
    protected function buildForm()
    {
        $this->formBuilder
            ->add("title", "text", array(
                "label" => $this->translator->trans("Label", [], Team::DOMAIN_NAME),
                "label_attr" => ["for" => "attr-team-title"],
                "required" => true,
                "constraints" => array(new NotBlank(),),
                "attr" => array()
            ))
            ->add("locale", "text", array(
                "constraints" => array(
                    new NotBlank(),
                ),
                "label_attr" => array("for" => "locale_create"),
            ))
        ;
    }

    public function getName()
    {
        return "team_create";
    }
}