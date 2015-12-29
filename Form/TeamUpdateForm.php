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

/**
 * Class TeamUpdateForm
 * @package Team\Form
 */
class TeamUpdateForm extends TeamCreateForm
{
    /**
     * @inheritDoc
     */
    protected function buildForm()
    {
        parent::buildForm();

        $this->formBuilder
            ->add('id', 'integer', array(
                "label" => $this->translator->trans("Id", [], Team::DOMAIN_NAME),
                "label_attr" => ["for" => "attr-team-id"],
                "required" => true,
                "constraints" => array(new NotBlank(),),
                "attr" => array()
            ))
        ;
    }

    public function getName()
    {
        return "team_update";
    }

}