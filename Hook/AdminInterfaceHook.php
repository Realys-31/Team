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

namespace Team\Hook;

use Symfony\Component\Routing\Router;
use Team\Team;
use Thelia\Core\Event\Hook\HookRenderBlockEvent;
use Thelia\Core\Hook\BaseHook;
use Thelia\Core\Translation\Translator;

/**
 * Class AdminInterfaceHook
 * @package Team\Hook
 */
class AdminInterfaceHook extends BaseHook
{
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    protected function transQuick($id, $locale, $parameters = [])
    {
        if ($this->translator === null) {
            $this->translator = Translator::getInstance();
        }

        return $this->trans($id, $parameters, Team::DOMAIN_NAME, $locale);
    }

    public function onMenuModule(HookRenderBlockEvent $event)
    {
        $url = $this->router->generate("team.list");
        $lang = $this->getSession()->getLang();
        $title = $this->transQuick("Team", $lang->getLocale());

        $event->add(
            [
                "id" => "team",
                "class" => "",
                "title" => $title,
                "url" => $url

            ]
        );
    }

}