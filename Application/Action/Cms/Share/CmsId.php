<?php

namespace Application\Action\Cms\Share;

/**
 * Trait CmsId
 *
 * @package Application\Action\Cms\Share
 */
trait CmsId
{
    /**
     * @var int
     */
    protected $cmsId;


    /**
     * @return \Application\Doctrine\Model\Cms | \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function getCms()
    {
        if (null === $this->getCmsId()) {
            throw new \InvalidArgumentException('cms id not set');
        }
        $cms = \Application\Doctrine\RepositoryLocator::getCms()->find($this->getCmsId());
        if (true === $cms instanceof \Application\Doctrine\Model\Cms) {
            return $cms;
        }
        $this
            ->getFlashMessenger()
            ->error(\Application\Message::MESSAGE_ERROR_ENTITY_NOT_FOUND)
        ;

        return $this
            ->getApplication()
            ->redirect(\Application\Router::buildHome())
            ;
    }


    /**
     * @return int
     */
    public function getCmsId()
    {
        return $this->cmsId;
    }


    /**
     * @param int $cmsId
     *
     * @return $this
     */
    public function setCmsId($cmsId)
    {
        $this->cmsId = $cmsId;

        return $this;
    }
}