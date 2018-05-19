<?php

namespace App\ORM;

use App\Model\Shop;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;

class EntityManagerFactory
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var array
     */
    private $credentials;

    /**
     * @var EntityManager[]
     */
    private $entityManagers = [];

    /**
     * @var
     */
    private $defaultEntityManager;

    /**
     * @var string
     */
    private $defaultDbName = 'shopify';

    public function __construct(array $credentials, Configuration $configuration)
    {
        $this->credentials = $credentials;
        $this->configuration = $configuration;
    }

    /**
     * @return EntityManager
     * @throws \Doctrine\ORM\ORMException
     */
    public function getDefaultEntityManager()
    {
        if (!is_null($this->defaultEntityManager)) {
            return $this->defaultEntityManager;
        }
        $credentials = $this->credentials;
        $credentials['dbname'] = $this->defaultDbName;
        return $this->defaultEntityManager = EntityManager::create(
            $credentials,
            $this->configuration
        );
    }

    /**
     * @param $dbname
     * @return EntityManager
     * @throws \Doctrine\ORM\ORMException
     */
    public function getTenantEntityManager(Shop $shop)
    {
        $domain = $shop->getMyshopifyDomain();
        if (array_key_exists($domain, $this->entityManagers)) {
            return $this->entityManagers[$domain];
        }
        $credentials = $this->credentials;
        $credentials['dbname'] = $shop->getDatabaseName();
        $credentials['user'] = $shop->getDatabaseUserName();
        $credentials['password'] = $shop->getDatabasePassword();
        $credentials['port'] = $shop->getDatabasePort();

        if (!is_null($shop->getDatabaseHost())) {
            $credentials['host'] = $shop->getDatabaseHost();
        }

         $entityManager = EntityManager::create(
            $credentials,
            $this->configuration
        );
        $this->entityManagers[$domain] = $entityManager;
        return $entityManager;
    }
}
