<?php

namespace Application\Action\Cms;

use Application\Doctrine\RepositoryLocator;

/**
 * Class HomeAction
 *
 * @package Application\Action\Cms
 */
class HomeAction extends \Application\Action\AbstractAction
{
    const ROUTE                     = \Application\Router::ROUTE_HOME;

    const ACL_ALLOWED_FOR_ANONYMOUS = true;

    const ACL_ADMIN_REQUIRED        = false;


    /**
     * @inheritdoc
     */
    public function run()
    {
        if (null === $this->getCurrentUser()) {
            return $this->render(
                \Application\View\PathMap::CMS_HOME, [
                    'foo'  => 'barXXX',
                    'yeah' => 'barfoos',
                ]
            );
        }

        return $this->render(
            \Application\View\PathMap::CMS_HOME, [
                'foo' => 'bar',
            ]
        );
    }
}