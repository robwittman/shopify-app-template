<?php

namespace App\Command\Database;

use App\Command;
use App\Repository\ShopRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDatabaseCommand extends Command
{
    protected $entityManager;

    protected $shopRepository;

    protected $migrateCommand;

    public function __construct(ShopRepository $shopRepository, EntityManager $entityManager, Migrate $migrateCommand)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->shopRepository = $shopRepository;
        $this->migrateCommand = $migrateCommand;
    }

    protected function configure()
    {
        $this
            ->setName('database:create')
            ->setDescription("Create a database for a newly installed shop")
            ->addOption('domain', null, InputOption::VALUE_REQUIRED, 'What store do we need to create a DB for?', null);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $domain = $input->getOption('domain');
        $shop = $this->shopRepository->findOneBy([
            'myshopify_domain' => $domain
        ]);
        if (is_null($shop)) {
            throw new \Exception("Shop {$domain} does not exist");
        }
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
        $this->executeSql("CREATE USER IF NOT EXISTS '{$username}'");
        $this->executeSql("GRANT ALL ON `{$dbname}`.* to '{$username}'@'%' identified by '{$password}'");
    }

    /**
     * @param $databaseName
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function createDatabase($databaseName)
    {
        $sql = "CREATE DATABASE IF NOT EXISTS {$databaseName}";
        $this->executeSql($sql);
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
    protected function executeSql($sql)
    {
        error_log($sql);
        $query = $this->entityManager->getConnection()->prepare($sql);
        return $query->execute();
    }
}
