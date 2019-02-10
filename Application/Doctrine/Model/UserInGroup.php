<?php
/**
 * Created by PhpStorm.
 * User: Robert Rupf
 * Date: 10.02.2019
 * Time: 16:03
 */

namespace Application\Doctrine\Model;

/**
 * Class UserInGroup
 *
 * @package Application\Doctrine\Model
 * @Entity(repositoryClass="Application\Doctrine\Model\Repository\UserInGroupRepository")
 * @Table(options={"collate"="utf8_general_ci", "charset"="utf8", "engine"="InnoDB"}, name="user_x_group",)
 *
 * uniqueConstraints={@UniqueConstraint(name="name", columns={"name"})}
 */
class UserInGroup
{
    use \Application\Doctrine\Share\Timestamp, \Application\Doctrine\Share\Blame;
    
    const TABLE_NAME = 'user_x_group';
    
    /**
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue *
     */
    protected $id;
    
    /**
     * @var \Application\Doctrine\Model\User
     * @ManyToOne(targetEntity="Application\Doctrine\Model\User")
     */
    protected $user;
    
    /**
     * @var \Application\Doctrine\Model\Group
     * @ManyToOne(targetEntity="Application\Doctrine\Model\Group")
     */
    protected $group;
}