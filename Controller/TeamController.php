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

use Thelia\Controller\Admin\BaseAdminController;

/**
 * Class TeamController
 * @package Team\Controller
 */
class TeamController extends BaseAdminController
{
    public function listAction()
    {
        return $this->render("team");
    }
}