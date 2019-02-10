<?php

namespace Application\Doctrine\Model\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 *
 * @package Application\Doctrine\Model\Repository
 */
class UserRepository extends EntityRepository
{
    use \Application\Doctrine\Share\Timestampable, \Application\Doctrine\Share\Blameable;


    /**
     * @param string $email
     *
     * @return \Application\Doctrine\Model\User | null
     */
    public function findByEmail($email)
    {
        try {

            return $queryBuilder = $this
                ->createQueryBuilder('p')
                ->andWhere('p.email = :email')
                ->setParameter('email', $email)
                ->getQuery()
                ->getOneOrNullResult()
                ;
        } catch (\Exception $exception) {
            return null;
        }
    }


    /**
     * @param \Application\Doctrine\Model\User $entity
     *
     * @return $this
     */
    public function create(\Application\Doctrine\Model\User $entity)
    {
        $this
            ->onCreateTS($entity)
            ->onCreate($entity)
            ->getEntityManager()
            ->persist($entity)
        ;

        return $this;
    }


    /**
     * @param \Application\Doctrine\Model\User $entity
     *
     * @return $this
     * @throws \Doctrine\ORM\ORMException
     */
    public function update(\Application\Doctrine\Model\User $entity)
    {
        $this
            ->onUpdate($entity)
            ->onUpdateTS($entity)
            ->getEntityManager()
            ->persist($entity)
        ;

        return $this;
    }


    /**
     * @param \Application\Doctrine\Model\User $entity
     *
     * @return $this
     */
    public function delete(\Application\Doctrine\Model\User $entity)
    {
        $this
            ->getEntityManager()
            ->remove($entity)
        ;

        return $this;
    }


    /**
     * @param mixed          $id
     * @param integer | null $lockMode
     * @param integer | null $lockVersion
     *
     * @return \Application\Doctrine\Model\User | null
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        return parent::find($id, $lockMode, $lockVersion);
    }


    /**
     * @return \Application\Doctrine\Model\User[]
     */
    public function findAll()
    {
        return parent::findAll();
    }


    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param null       $limit
     * @param null       $offset
     *
     * @return \Application\Doctrine\Model\User[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }


    /**
     * @param array        $criteria
     * @param array | null $orderBy
     *
     * @return \Application\Doctrine\Model\User | null
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return parent::findOneBy($criteria, $orderBy);
    }
}
