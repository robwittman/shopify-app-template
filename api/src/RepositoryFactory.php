<?php

namespace App;

use App\Model\Shop;
use App\ORM\EntityManagerFactory;
use App\ORM\TenantAwareInterface;

class RepositoryFactory
{
    /**
     * @var EntityManagerFactory
     */
    private $entityManagerFactory;

    public function __construct(EntityManagerFactory $entityManagerFactory)
    {
        $this->entityManagerFactory = $entityManagerFactory;
    }

    /**
     * Create a repository, pointed at the expected tenant database, for the given repository
     * @param $class
     * @param Shop $shop
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws \Doctrine\ORM\ORMException
     */
    public function createRepository($class, Shop $shop = null)
    {
        $tenantAware = array_key_exists(
            TenantAwareInterface::class,
            class_implements($class)
        );
        if ($tenantAware) {
            $entityManager = $this->entityManagerFactory->getTenantEntityManager(
                $shop->getDatabaseName()
            );
        } else {
            $entityManager = $this->entityManagerFactory->getDefaultEntityManager();
        }
        return $entityManager->getRepository($class);
    }
}
