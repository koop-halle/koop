<?php

namespace Application\Action;

use Application\Router;
use Zend\Session\Container as SessionContainer;
use Plasticbrain\FlashMessages\FlashMessages;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractAction
 *
 * @package Application\Action
 */
abstract class AbstractAction
{
    const ROUTE                     = \Application\Router::ROUTE_HOME;

    const ACL_ALLOWED_FOR_ANONYMOUS = false;

    const ACL_ADMIN_REQUIRED        = true;

    const SESSION_KEY_USER          = 'user';

    const SESSION_KEY_BEACON        = 'beacon';

    /**
     * @var \Plasticbrain\FlashMessages\FlashMessages
     */
    protected $flashMessenger;

    /**
     * @var Application
     */
    protected $application;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var \AdamWathan\Form\FormBuilder
     */
    protected $builder;

    /**
     * @var array
     */
    private $aclDefinitions;

    /**
     * @var int
     */
    protected $itemsPerPage = 15;


    /**
     * AbstractAction constructor.
     */
    public final function __construct()
    {
        new SessionContainer('foo');
        $this
            ->setFlashMessenger(new FlashMessages())
            ->setBuilder(new \AdamWathan\Form\FormBuilder())
        ;
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Silex\Application                        $app
     *
     * @return string | \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public final function init(\Symfony\Component\HttpFoundation\Request $request, \Silex\Application $app)
    {
        return $this
            ->setRequest($request)
            ->setApplication($app)
            ->checkAccess()
            ->prepare()
            ->run()
            ;
    }


    /**
     * @return string | \Symfony\Component\HttpFoundation\RedirectResponse
     */
    abstract public function run();


    /**
     * @return $this
     */
    protected function prepare(): AbstractAction
    {
        return $this;
    }


    /**
     * @return $this
     */
    protected function checkAccess()
    {
        if (false === $this->isAllowed()) {
            throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException('not allowed');
        }

        return $this;
    }


    /**
     * @param string $route
     *
     * @return bool
     */
    public function isAllowed($route = null)
    {
        if (null === $route) {
            if (true === static::ACL_ALLOWED_FOR_ANONYMOUS) {
                return true;
            }
            if (false === $this->isAuthenticated()) {
                return false;
            }
            if (false === static::ACL_ADMIN_REQUIRED) {
                return true;
            }
            if (true === $this->getCurrentUser()->isAdmin()) {
                return true;
            }

            return false;
        }
        if (null === $this->aclDefinitions) {
            $this->fetchAclDefinitions();
        }
        if (false === array_key_exists($route, $this->aclDefinitions)) {
            throw new \Exception('route "' . $route . '" has no acl!');
        }
        $definition = $this->aclDefinitions[$route];
        if (true === $definition['anonymous']) {
            return true;
        }
        if (false === $this->isAuthenticated()) {
            return false;
        }
        if (false === $definition['admin']) {
            return true;
        }
        if (true === $this->getCurrentUser()->isAdmin()) {
            return true;
        }

        return false;
    }


    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        return true === $this->getCurrentUser() instanceof \Application\Doctrine\Model\User;
    }


    /**
     * @return \Application\Doctrine\Model\User| null
     */
    public function getCurrentUser()
    {
        return $this->getAuthSession()->offsetGet(self::SESSION_KEY_USER);
    }


    /**
     * @return \Zend\Session\Container
     */
    protected function getAuthSession()
    {
        return new SessionContainer('auth');
    }


    /**
     * @return $this
     */
    private function fetchAclDefinitions()
    {
        $this->aclDefinitions = [];
        $directoryIterator    = new \RecursiveDirectoryIterator(ROOT_DIR . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Action');
        $iterator             = new \RecursiveIteratorIterator($directoryIterator);
        $regexIterator        = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);
        foreach ($regexIterator as $current) {
            $file          = $current[0];
            $classClearing = str_replace(ROOT_DIR, '', $file);
            $classClearing = str_replace('/app', '/Application', $classClearing);
            $classClearing = str_replace('/', '\\', $classClearing);
            $actionClass   = str_replace('.php', '', $classClearing);
            try {
                $reflection = new \ReflectionClass($actionClass);
                if (false === $reflection->isSubclassOf(AbstractAction::class)) {
                    continue;
                }
                $this->aclDefinitions[$actionClass::ROUTE] = [
                    'anonymous' => $actionClass::ACL_ALLOWED_FOR_ANONYMOUS,
                    'admin'     => $actionClass::ACL_ADMIN_REQUIRED,
                ];
            } catch (\Exception $exception) {
                \Application\Util\Debugger::debug($exception->getTraceAsString());
            }
        }

        return $this;
    }


    /**
     * @return $this
     */
    public function setup()
    {
        if (true === $this->isAuthenticated()) {
            $id   = $this->getCurrentUser()->getId();
            $user = \Application\Doctrine\RepositoryLocator::getUser()->find($id);
            if (null === $user) {
                $this->logout();

                return $this;
            }
            $this->login($user);
        }

        return $this;
    }


    /**
     * @return $this
     */
    public function logout()
    {
        $this
            ->getAuthSession()
            ->offsetUnset(self::SESSION_KEY_USER)
        ;

        return $this;
    }


    /**
     * @param \Application\Doctrine\Model\User $user
     *
     * @return $this
     */
    public function login(\Application\Doctrine\Model\User $user)
    {
        $this
            ->getAuthSession()
            ->offsetSet(self::SESSION_KEY_USER, $user)
        ;

        return $this;
    }


    /**
     * @return \Application\Translator
     */
    protected function getTranslator()
    {
        return \Application\Util\DependencyContainer::getInstance()->getTranslator();
    }


    /**
     * @return null | string
     */
    protected function getReferrer()
    {
        return $this
            ->getRequest()
            ->headers
            ->get('referer', null, true);
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }


    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return $this
     */
    public function setRequest(\Symfony\Component\HttpFoundation\Request $request)
    {
        $this->request = $request;

        return $this;
    }


    /**
     * @return $this
     */
    protected function beacon()
    {
        if (null === $this->getCurrentUser()) {
            return $this;
        }
        $this
            ->getAuthSession()
            ->offsetSet(self::SESSION_KEY_BEACON, $this->getTimestamp())
        ;
        $user = $this
            ->getCurrentUser()
            ->setLastSeen($this->getNow())
        ;
        \Application\Doctrine\RepositoryLocator::getUser()->update($user);
        $this
            ->getEntityManager()
            ->flush($user)
        ;

        return $this;
    }


    /**
     * @return int
     */
    protected function getTimestamp()
    {
        return time();
    }


    /**
     * @return \DateTime
     */
    protected function getNow()
    {
        return new \DateTime();
    }


    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return \Application\Util\DependencyContainer::getInstance()->getEntityManager();
    }


    /**
     * @param \Doctrine\ORM\QueryBuilder | array $data
     * @param bool                               $nullOnEmpty
     *
     * @return \Knp\Component\Pager\Pagination\SlidingPagination | null
     */
    protected function paginate($data, $nullOnEmpty = true)
    {
        $page       = $this->getPageNumber();
        $pagination = $this
            ->getPaginator()
            ->paginate($data, $page, $this->getItemsPerPage())
        ;
        if (($page < 1 || $page > $pagination->getPaginationData()['lastPageInRange']) && true === $nullOnEmpty) {
            return null;
        }

        return $pagination;
    }


    /**
     * @return int
     */
    protected function getPageNumber()
    {
        return $this->getRequest()->get('page', 1);
    }


    /**
     * @return \Knp\Component\Pager\Paginator
     */
    protected function getPaginator()
    {
        return $this->getApplication()['knp_paginator'];
    }


    /**
     * @return \Symfony\Component\Validator\Validator\RecursiveValidator
     */
    protected function getValidator()
    {
        return $this->getApplication()['validator'];
    }


    /**
     * @return \Silex\Application
     */
    public function getApplication()
    {
        return $this->application;
    }


    /**
     * @param \Silex\Application $application
     *
     * @return $this
     */
    public function setApplication(\Silex\Application $application)
    {
        $this->application = $application;

        return $this->setup();
    }


    /**
     * @param string $view
     * @param mixed  $data
     *
     * @return string
     */
    protected function render($view, $data = [])
    {
        $data = array_merge(
            $data,
            [
                'application'       => $this,
                'currentUser'       => $this->getCurrentUser(),
                'dateFormatter'     => $this->getDateFormatter(),
                'dateTimeFormatter' => $this->getDateTimeFormatter(),
                'flashMessenger'    => $this->getFlashMessenger(),
                'formBuilder'       => $this->getBuilder(),
                'isAjax'            => $this->getRequest()->isXmlHttpRequest(),
                'timeFormatter'     => $this->getTimeFormatter(),
                'translator'        => \Application\Util\DependencyContainer::getInstance()->getTranslator(),
            ]);

        return \Application\Util\DependencyContainer::getInstance()
                                                    ->getRenderer()
                                                    ->make(
                                                        $view,
                                                        $data
                                                    )
                                                    ->render()
            ;
    }


    /**
     * @return \IntlDateFormatter
     */
    protected function getDateFormatter()
    {
        return \IntlDateFormatter::create($this->getLocale(), \IntlDateFormatter::MEDIUM, \IntlDateFormatter::NONE);
    }


    /**
     * @return string
     */
    protected function getLocale()
    {
        return 'de';
    }


    /**
     * @return \IntlDateFormatter
     */
    protected function getDateTimeFormatter()
    {
        return \IntlDateFormatter::create($this->getLocale(), \IntlDateFormatter::MEDIUM, \IntlDateFormatter::MEDIUM);
    }


    /**
     * @return \Plasticbrain\FlashMessages\FlashMessages
     */
    public function getFlashMessenger()
    {
        return $this->flashMessenger;
    }


    /**
     * @param \Plasticbrain\FlashMessages\FlashMessages $flashMessenger
     *
     * @return $this
     */
    public function setFlashMessenger(\Plasticbrain\FlashMessages\FlashMessages $flashMessenger)
    {
        $this->flashMessenger = $flashMessenger;

        return $this;
    }


    /**
     * @return \AdamWathan\Form\FormBuilder
     */
    public function getBuilder()
    {
        return $this->builder;
    }


    /**
     * @param \AdamWathan\Form\FormBuilder $builder
     *
     * @return $this
     */
    public function setBuilder(\AdamWathan\Form\FormBuilder $builder)
    {
        $this->builder = $builder;

        return $this;
    }


    /**
     * @return \IntlDateFormatter
     */
    protected function getTimeFormatter()
    {
        return \IntlDateFormatter::create($this->getLocale(), \IntlDateFormatter::NONE, \IntlDateFormatter::MEDIUM);
    }


    /**
     * @return int
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }


    /**
     * @param int $itemsPerPage
     *
     * @return $this
     */
    public function setItemsPerPage($itemsPerPage): AbstractAction
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }
}