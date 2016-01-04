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

use Propel\Runtime\ActiveQuery\Criteria;
use Team\Model\PersonFunction;
use Team\Model\PersonFunctionQuery;
use Thelia\Core\Template\Element\BaseI18nLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

/**
 * Class PersonFunctionLoop
 * @package Team\Loop
 */
class PersonFunctionLoop extends BaseI18nLoop implements PropelSearchLoopInterface
{

    /**
     * @inheritDoc
     */
    public function parseResults(LoopResult $loopResult)
    {
        /** @var PersonFunction $personFunction */
        foreach ($loopResult->getResultDataCollection() as $personFunction) {
            $loopResultRow = new LoopResultRow($personFunction);

            $loopResultRow
                ->set('ID', $personFunction->getId())
                ->set("CODE", $personFunction->getCode())
            ;

            if ($personFunction->hasVirtualColumn('i18n_LABEL')) {
                $loopResultRow->set("LABEL", $personFunction->getVirtualColumn('i18n_LABEL'));
            }

            if ($personFunction->hasVirtualColumn('link_id')) {
                $loopResultRow->set("LINK_ID", $personFunction->getVirtualColumn('link_id'));
            }

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }

    /**
     * @inheritDoc
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument('id'),
            Argument::createIntListTypeArgument('person_id'),
            Argument::createAnyListTypeArgument('code'),
            Argument::createEnumListTypeArgument('order', [
                'id',
                'id-reverse',
                'code',
                'code-reverse'
            ], 'id')
        );
    }

    /**
     * @inheritDoc
     */
    public function buildModelCriteria()
    {
        $query = PersonFunctionQuery::create();

        // manage translations
        $this->configureI18nProcessing(
            $query,
            [
                'LABEL',
            ],
            null,
            'ID',
            $this->getForceReturn()
        );


        if($id = $this->getId()){
            $query->filterById($id);
        }

        if($code = $this->getCode()){
            $query->filterByCode($code);
        }

        if($person = $this->getPersonId()){
            $query->filterByPersonId($person);
        }

        foreach($this->getOrder() as $order){
            switch($order){
                case 'id' :
                    $query->orderById();
                    break;
                case 'id-reverse' :
                    $query->orderById(Criteria::DESC);
                    break;
                case 'code' :
                    $query->orderByCode();
                    break;
                case 'code-reverse' :
                    $query->orderByCode(Criteria::DESC);
                    break;
                default:
                    break;
            }
        }

        return $query;
    }
}