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
 * Class PersonUpdateForm
 * @package Team\Form
 */
class PersonUpdateForm extends PersonCreateForm
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
                "label_attr" => ["for" => "attr-person-id"],
                "required" => true,
                "constraints" => array(new NotBlank(),),
                "attr" => array()
            ))
            ->remove('team_id')
        ;
    }

    public function getName()
    {
        return "person_update";
    }
}