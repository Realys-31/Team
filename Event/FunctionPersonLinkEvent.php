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
use Team\Model\PersonFunctionLink;

/**
 * Class FunctionPersonLinkEvent
 * @package Team\Event
 */
class FunctionPersonLinkEvent extends Event
{

    /**
     * @var PersonFunctionLink $personFunctionLink
     */
    protected $personFunctionLink;

    /**
     * @return mixed
     */
    public function getPersonFunctionLink()
    {
        return $this->personFunctionLink;
    }

    /**
     * @param mixed $personFunctionLink
     */
    public function setPersonFunctionLink($personFunctionLink)
    {
        $this->personFunctionLink = $personFunctionLink;
    }


}