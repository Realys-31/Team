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

namespace Team\Event;

use Symfony\Component\EventDispatcher\Event;
use Team\Model\PersonFunction;

/**
 * Class FunctionEvent
 * @package Team\Event
 */
class FunctionEvent extends Event
{
    /**
     * @var PersonFunction $personFunction
     */
    protected $personFunction;

    /**
     * @return PersonFunction
     */
    public function getPersonFunction()
    {
        return $this->personFunction;
    }

    /**
     * @param PersonFunction $personFunction
     */
    public function setPersonFunction($personFunction)
    {
        $this->personFunction = $personFunction;
    }

}