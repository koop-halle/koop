<?php
/**
 * Created by PhpStorm.
 * User: Robert Rupf
 * Date: 10.02.2019
 * Time: 15:41
 */

namespace Application\Doctrine\Model;

/**
 * Class Group
 *
 * @package Application\Doctrine\Model
 * @Entity(repositoryClass="Application\Doctrine\Model\Repository\GroupRepository")
 * @Table(options={"collate"="utf8_general_ci", "charset"="utf8", "engine"="InnoDB"}, name="group", uniqueConstraints={@UniqueConstraint(name="name", columns={"name"})},)
 */
class Group
{
    use \Application\Doctrine\Share\Timestamp, \Application\Doctrine\Share\Blame;

    const TABLE_NAME = 'group';
    
    /**
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue *
     */
    protected $id;
    
    /**
     * @var string
     * @Column(type="string", name="name")
     */
    protected $name;
    
    /**
     * @var string
     * @Column(type="string", name="description")
     */
    protected $description;
    
    /**
     * @var string
     * @Column(type="string", name="image")
     */
    protected $image;
    
    /**
     * @return string
     */
    public function getCreatedBy(): string
    {
        return $this->createdBy;
    }
    
    /**
     * @param string $createdBy
     * @return Group
     */
    public function setCreatedBy(string $createdBy): Group
    {
        $this->createdBy = $createdBy;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getUpdatedBy(): string
    {
        return $this->updatedBy;
    }
    
    /**
     * @param string $updatedBy
     * @return Group
     */
    public function setUpdatedBy(string $updatedBy): Group
    {
        $this->updatedBy = $updatedBy;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     * @return Group
     */
    public function setName(string $name): Group
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    
    /**
     * @param string $description
     * @return Group
     */
    public function setDescription(string $description): Group
    {
        $this->description = $description;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }
    
    /**
     * @param string $image
     * @return Group
     */
    public function setImage(string $image): Group
    {
        $this->image = $image;
        
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    
    /**
     * @param \DateTime $createdAt
     * @return Group
     */
    public function setCreatedAt(\DateTime $createdAt): Group
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
    
    /**
     * @param \DateTime $updatedAt
     * @return Group
     */
    public function setUpdatedAt(\DateTime $updatedAt): Group
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }
}