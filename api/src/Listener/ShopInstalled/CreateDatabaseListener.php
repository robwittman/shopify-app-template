<?php

namespace App\Listener\ShopInstalled;

use App\Command\Database\Migrate;
use App\Event\ShopInstalledEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class CreateDatabaseListener
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Migrate
     */
    protected $migrateCommand;

    public function __construct(EntityManager $entityManager, Migrate $migrateCommand)
    {
        $this->entityManager = $entityManager;
        $this->migrateCommand = $migrateCommand;
    }

    /**
     * @param ShopInstalledEvent $event
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    public function __invoke(ShopInstalledEvent $event)
    {
        $shop = $event->getShop();
        $this->createDatabase($shop->getDatabaseName());
        $this->createDatabaseUser(
            $shop->getDatabaseName(),
            $shop->getDatabaseUserName(),
            $shop->getDatabasePassword()
        );
        $this->migrateDatabase($shop->getMyshopifyDomain());
    }

    /**
     * @param $dbname
     * @param $username
     * @param $password
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function createDatabaseUser($dbname, $username, $password)
    {
        $this->execute("CREATE USER '{$username}'");
        $this->execute("GRANT ALL ON `{$dbname}`.* to '{$username}'@'%' identified by '{$password}'");
    }

    /**
     * @param $databaseName
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function createDatabase($databaseName)
    {
        $sql = "CREATE DATABASE {$databaseName}";
        $this->execute($sql);
    }

    /**
     * @param $myshopifyDomain
     * @return int
     * @throws \Exception
     */
    protected function migrateDatabase($myshopifyDomain)
    {
        $arguments = [
            '--type'  => 'tenant',
            '--tenant-id' => $myshopifyDomain
        ];
        $greetInput = new ArrayInput($arguments);
        $returnCode = $this->migrateCommand->run(
            $greetInput,
            new NullOutput()
        );
        return $returnCode;
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
