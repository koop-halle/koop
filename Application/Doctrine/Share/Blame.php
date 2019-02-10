<?php

namespace Application\Doctrine\Share;

#use Doctrine\ORM\Mapping as ORM;
#use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Trait Blame
 *
 * @package Application\Doctrine\Share
 */
trait Blame
{
    /**
     * @var string
     * @Gedmo\Blameable(on="create")
     * @Column(name="created_by", type="string", nullable=true)
     */
    protected $createdBy;

    /**
     * @var string
     * @Gedmo\Blameable(on="update")
     * @Column(name="updated_by",type="string", nullable=true)
     */
    protected $updatedBy;


    /**
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }


    /**
     * @param string $createdBy
     *
     * @return $this
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }


    /**
     * @return string
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }


    /**
     * @param string $updatedBy
     *
     * @return $this
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }
}