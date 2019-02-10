<?php

namespace Application\Doctrine\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Player
 *
 * @package Application\Doctrine\Model
 * @Entity(repositoryClass="Application\Doctrine\Model\Repository\UserRepository")
 * @Table(options={"collate"="utf8_general_ci", "charset"="utf8", "engine"="InnoDB"}, name="user", uniqueConstraints={@UniqueConstraint(name="email", columns={"email"})},)
 */
class User
{
    use \Application\Doctrine\Share\Timestamp, \Application\Doctrine\Share\Blame;

    const TABLE_NAME = 'user';

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
     * @Column(type="string", name="email", nullable=true)
     */
    protected $email;

    /**
     * @var string
     * @Column(type="string", name="password", nullable=true)
     */
    protected $password;

    /**
     * @var boolean
     * @Column(name="is_admin", type="boolean", options={"default"="0"})
     */
    protected $isAdmin = false;

    /**
     * @var \DateTime
     * @Column(name="last_login", type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @var \DateTime
     * @Column(name="last_seen", type="datetime", nullable=true)
     */
    protected $lastSeen;


    /**
     * User constructor.
     */
    public function __construct()
    {
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }


    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }


    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }


    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->isAdmin;
    }


    /**
     * @param bool $isAdmin
     *
     * @return $this
     */
    public function setIsAdmin($isAdmin = true)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }


    /**
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }


    /**
     * @param \DateTime $lastLogin
     *
     * @return $this
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }


    /**
     * @return \DateTime
     */
    public function getLastSeen()
    {
        return $this->lastSeen;
    }


    /**
     * @param \DateTime $lastSeen
     *
     * @return $this
     */
    public function setLastSeen($lastSeen)
    {
        $this->lastSeen = $lastSeen;

        return $this;
    }
}
