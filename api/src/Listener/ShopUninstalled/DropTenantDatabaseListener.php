<?php

namespace App\Listener\ShopUninstalled;

use App\Event\ShopUninstalledEvent;
use App\Repository\ShopRepository;
use Doctrine\ORM\EntityManager;

class DropTenantDatabaseListener
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var ShopRepository
     */
    protected $shopRepo;

    public function __construct(EntityManager $entityManager, ShopRepository $shopRepo)
    {
        $this->entityManager = $entityManager;
        $this->shopRepo = $shopRepo;
    }

    /**
     * @param ShopUninstalledEvent $event
     * @throws \Doctrine\DBAL\DBALException
     */
    public function __invoke(ShopUninstalledEvent $event)
    {
        $shop = $event->getShop();
        $this->execute(
            "DROP DATABASE IF EXISTS {$shop->getDatabaseName()}"
        );
        $this->execute(
            "DROP USER IF EXISTS '{$shop->getDatabaseUserName()}'"
        );
        $this->shopRepo->remove($shop);
    }

    /**
     * @param $sql
     * @return int
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function execute($sql)
    {
        return $this->entityManager->getConnection()->exec($sql);
    }
}
