<?php

namespace Application\Util;

/**
 * Class Language
 *
 * @package Application\Util
 */
class Language
{
    const DEFAULT = 'de';


    /**
     * @return string
     */
    public function getCurrent()
    {
        $currentUser = DependencyContainer::getInstance()->getApplication()->getCurrentUser();
        if (null === $currentUser) {
            return $this->getFromSession();
        }

        return $currentUser->getLanguage();
    }


    /**
     * @return string
     */
    public function getFromSession()
    {
        $selected = $this
            ->getSession()
            ->offsetGet('language')
        ;
        if (null === $selected) {
            return $this
                ->setToSession(self::DEFAULT)
                ->getFromSession()
                ;
        }

        return $selected;
    }


    /**
     * @param string $language
     *
     * @return $this
     */
    public function setToSession($language)
    {
        $this
            ->getSession()
            ->offsetSet('language', $language)
        ;

        return $this;
    }


    /**
     * @return \Zend\Session\Container
     */
    protected function getSession()
    {
        return new \Zend\Session\Container('locale');
    }
}