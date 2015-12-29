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
use Team\Model\PersonQuery;
use Team\Model\Person;
use Thelia\Core\Template\Element\BaseI18nLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

/**
 * Class PersonLoop
 * @package Team\Loop
 */
class PersonLoop extends BaseI18nLoop implements PropelSearchLoopInterface
{

    /**
     * @inheritDoc
     */
    public function parseResults(LoopResult $loopResult)
    {
        /** @var Person $person */
        foreach ($loopResult->getResultDataCollection() as $person) {
            $loopResultRow = new LoopResultRow($person);

            $loopResultRow
                ->set('ID', $person->getId())
                ->set('FIRST_NAME', $person->getFirstName())
                ->set('LAST_NAME', $person->getLastName())
            ;

            if ($person->hasVirtualColumn('i18n_DESCRIPTION')) {
                $loopResultRow->set("DESCRIPTION", $person->getVirtualColumn('i18n_DESCRIPTION'));
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
            Argument::createIntListTypeArgument('team_id'),
            Argument::createEnumListTypeArgument('order', [
                'id',
                'id-reverse'
            ], 'id')
        );
    }

    /**
     * @inheritDoc
     */
    public function buildModelCriteria()
    {
        $query = PersonQuery::create();

        $this->configureI18nProcessing(
            $query,
            [
                'DESCRIPTION'
            ],
            null,
            'ID',
            $this->getForceReturn()
        );

        if($id = $this->getId()){
            $query->filterById($id);
        }

        if($team_id = $this->getTeamId()){
            $query->filterByTeamId($team_id);
        }

        foreach ($this->getOrder() as $order) {
            switch ($order) {
                case 'id' :
                    $query->orderById();
                    break;
                case 'id-reverse':
                    $query->orderById(Criteria::DESC);
                    break;
                default:
                    break;
            }
        }

        return $query;
    }
}