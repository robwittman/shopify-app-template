<?php

namespace App\Command\Database;

use App\ClassRegister;
use App\Command;
use App\Model\Shop;
use App\ORM\EntityManagerFactory;
use App\Repository\ShopRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Migrate extends Command
{
    const TYPE_TENANT = 'tenant';
    const TYPE_SHARED = 'shared';

    private $shopRepo;

    private $entityManagerFactory;

    public function __construct(ShopRepository $shopRepo, EntityManagerFactory $entityManagerFactory)
    {
        parent::__construct();
        $this->shopRepo = $shopRepo;
        $this->entityManagerFactory = $entityManagerFactory;
    }

    protected function configure()
    {
        $this
            ->setName('database:migrate')
            ->setDescription('Run all required database migrations')
            ->addOption('type', null, InputOption::VALUE_OPTIONAL, 'Which groups should we run migrations on?', null)
            ->addOption('tenant-id', null, InputOption::VALUE_OPTIONAL, 'The tenant we want to run for', null);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tenantId = $input->getOption('tenant-id');
        switch($input->getOption('type')) {
            case self::TYPE_TENANT:
                is_null($tenantId) ?
                    $this->migrateTenants() :
                    $this->migrateTenant($tenantId);
                break;
            case self::TYPE_SHARED:
                $this->migrateShared();
                break;
            default:
                $this->migrateShared();
                $this->migrateTenants();
        }
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    protected function migrateTenants()
    {
        $shops = $this->shopRepo->findAll();
        foreach ($shops as $shop) {
            $this->_migrateTenant($shop);

        }
    }

    /**
     * @param $tenantId
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    protected function migrateTenant($tenantId)
    {
        $shop = $this->shopRepo->findOneBy([
            'myshopify_domain' => $tenantId
        ]);
        $this->_migrateTenant($shop);
    }

    /**
     * @param Shop $shop
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    protected function _migrateTenant(Shop $shop)
    {
        $models = ClassRegister::getTenantModels();
        $entityManager = $this->entityManagerFactory->getTenantEntityManager($shop);
        $schemaTool = new SchemaTool($entityManager);
        $schemaDetails = $this->getSchemaDetails($entityManager, $models);
        $this->logger->debug("Running migrations for {$shop->getMyshopifyDomain()}");
        foreach ($schemaTool->getUpdateSchemaSql($schemaDetails) as $sql) {
            $this->logger->debug($sql);
            $entityManager->getConnection()->exec($sql);
        }

    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    protected function migrateShared()
    {
        $models = ClassRegister::getSharedModels();
        $defaultEntityManager = $this->entityManagerFactory->getDefaultEntityManager();
        $schemaTool = new SchemaTool($defaultEntityManager);
        $schemaDetails = $this->getSchemaDetails($defaultEntityManager, $models);
        $this->logger->debug('Running shared migrations');
        foreach ($schemaTool->getUpdateSchemaSql($schemaDetails) as $sql) {
            $this->logger->debug($sql);
            $defaultEntityManager->getConnection()->exec($sql);
        }
    }

    /**
     * @param EntityManager $entityManager
     * @param array $classes
     * @return array
     */
    protected function getSchemaDetails(EntityManager $entityManager, array $classes)
    {
        return array_map(function($class) use ($entityManager) {
            return $entityManager->getClassMetadata($class);
        }, $classes);
    }
}
