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

namespace Team\Loop;

use Thelia\Core\Template\Loop\Image;

/**
 * Class PersonImageLoop
 * @package Team\Loop
 */
class PersonImageLoop extends Image
{
    /**
     * @var array Possible standard image sources
     */
    protected $possible_sources = array('category', 'product', 'folder', 'content', 'module', 'brand', 'person');

}