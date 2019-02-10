<?php

namespace Application\Doctrine\Share;

/**
 * Trait Blameable
 *
 * @package Application\Doctrine\Share
 */
trait Blameable
{
    /**
     * @param $model
     *
     * @return $this
     */
    protected function onCreate($model)
    {
        if (true === method_exists($model, 'setCreatedBy')) {
            $model->setCreatedBy($this->getUser());
        }

        return $this;
    }


    /**
     * @return string
     */
    private function getUser()
    {
        $user= \Application\Util\DependencyContainer::getInstance()->getApplication()->getCurrentUser();
        if(null === $user) {
            return 'anonymous';
        }
        return $user->getName();
    }


    /**
     * @param $model
     *
     * @return $this
     */
    protected function onUpdate($model)
    {
        if (true === method_exists($model, 'setUpdatedBy')) {
            $model->setUpdatedBy($this->getUser());
        }

        return $this;
    }
}