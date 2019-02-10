<?php

namespace Application\Doctrine;

/**
 * Class RepositoryLocator
 *
 * @package Application\Doctrine
 */
class RepositoryLocator
{

    /**
     * @return \Application\Doctrine\Model\Repository\UserRepository
     */
    public static function getUser()
    {
        return \Application\Util\DependencyContainer::getInstance()
                                                    ->getEntityManager()
                                                    ->getRepository(
                                                        \Application\Doctrine\Model\User::class
                                                    )
            ;
    }

}
