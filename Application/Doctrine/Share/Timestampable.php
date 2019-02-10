<?php

namespace Application\Doctrine\Share;

/**
 * Trait Timestampable
 *
 * @package Application\Doctrine\Share
 */
trait Timestampable
{
    /**
     * @param $model
     *
     * @return $this
     */
    protected function onCreateTS($model)
    {
        if (true === method_exists($model, 'setCreatedAt')) {
            $model->setCreatedAt($this->getNow());
        }

        return $this;
    }


    /**
     * @return \DateTime
     */
    private function getNow()
    {
        return new \DateTime();
    }


    /**
     * @param $model
     *
     * @return $this
     */
    protected function onUpdateTS($model)
    {
        if (true === method_exists($model, 'setUpdatedAt')) {
            $model->setUpdatedAt($this->getNow());
        }

        return $this;
    }
}