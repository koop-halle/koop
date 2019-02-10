<?php

namespace Application;

use Zend\Session\Container as SessionContainer;
use Plasticbrain\FlashMessages\FlashMessages;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

trait ManagerBaseTrait
{
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
     * Manager constructor.
     */
    public function __construct()
    {
        new SessionContainer('foo');
        $this
            ->setFlashMessenger(new FlashMessages())
            ->setBuilder(new \AdamWathan\Form\FormBuilder())
        ;
    }



    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        return true === $this->getCurrentUser() instanceof \Application\Doctrine\Model\User;
    }


    /**
     * @return \Application\Doctrine\Model\User | null
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
     * @param string $view
     * @param mixed  $data
     *
     * @return string
     */
    protected function render($view, $data = [])
    {
        $data = array_merge($data, [
            'application'       => $this,
            'currentUser'       => $this->getCurrentUser(),
            'flashMessenger'    => $this->getFlashMessenger(),
            'formBuilder'       => $this->getBuilder(),
            'isAjax'            => $this->getRequest()->isXmlHttpRequest(),
            'dateFormatter'     => $this->getDateFormatter(),
            'timeFormatter'     => $this->getTimeFormatter(),
            'dateTimeFormatter' => $this->getDateTimeFormatter(),
            'translator'        => \Application\Util\DependencyContainer::getInstance()->getTranslator(),
        ]);

        return Util\DependencyContainer::getInstance()
                                       ->getRenderer()
                                       ->make(
                                           $view,
                                           $data
                                       )
                                       ->render()
            ;
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
    protected function getTimeFormatter()
    {
        return \IntlDateFormatter::create($this->getLocale(), \IntlDateFormatter::NONE, \IntlDateFormatter::MEDIUM);
    }


    /**
     * @return \IntlDateFormatter
     */
    protected function getDateTimeFormatter()
    {
        return \IntlDateFormatter::create($this->getLocale(), \IntlDateFormatter::MEDIUM, \IntlDateFormatter::MEDIUM);
    }


    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return Util\DependencyContainer::getInstance()->getEntityManager();
    }


    /**
     * @param \Doctrine\ORM\QueryBuilder | array $data
     *
     * @return \Knp\Component\Pager\Pagination\SlidingPagination | null
     */
    protected function paginate($data)
    {
        $page       = $this->getPageNumber();
        $pagination = $this
            ->getPaginator()
            ->paginate($data, $page)
        ;
        if ($page < 1 || $page > $pagination->getPaginationData()['lastPageInRange']) {
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
     * @return \DateTime
     */
    protected function getNow()
    {
        return new \DateTime();
    }
}
