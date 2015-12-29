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

namespace Team\Service\Base;

/**
 * Interface BaseServiceInterface
 * @package Team\Service\Base
 */
interface BaseServiceInterface
{
    public function createFromArray($data, $locale = null);
    public function updateFromArray($data, $locale = null);
    public function deleteFromId($id);
}