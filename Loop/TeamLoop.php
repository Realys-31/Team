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
use Team\Model\TeamQuery;
use Team\Model\Team;
use Thelia\Core\Template\Element\BaseI18nLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

/**
 * Class TeamLoop
 * @package Team\Loop
 */
class TeamLoop extends BaseI18nLoop implements PropelSearchLoopInterface
{

    /**
     * @inheritDoc
     */
    public function parseResults(LoopResult $loopResult)
    {
        /** @var Team $team */
        foreach ($loopResult->getResultDataCollection() as $team) {
            $loopResultRow = new LoopResultRow($team);

            $loopResultRow
                ->set('ID', $team->getId())
            ;

            if ($team->hasVirtualColumn('i18n_TITLE')) {
                $loopResultRow->set("TITLE", $team->getVirtualColumn('i18n_TITLE'));
            }

            if ($team->hasVirtualColumn('i18n_DESCRIPTION')) {
                $loopResultRow->set("DESCRIPTION", $team->getVirtualColumn('i18n_DESCRIPTION'));
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
            Argument::createEnumListTypeArgument('order', [
                'id',
                'id-reverse',
            ], 'id')
        );
    }

    /**
     * @inheritDoc
     */
    public function buildModelCriteria()
    {
        $query = TeamQuery::create();

        $this->configureI18nProcessing(
            $query,
            [
                'TITLE',
                'DESCRIPTION'
            ],
            null,
            'ID',
            $this->getForceReturn()
        );

        if ($id = $this->getId()) {
            $query->filterById();
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