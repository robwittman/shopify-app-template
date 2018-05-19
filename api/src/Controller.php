<?php

namespace App;

use App\Model\Shop;

abstract class Controller
{
    protected $repoFactory;

    public function __construct(RepositoryFactory $repoFactory)
    {
        $this->repoFactory = $repoFactory;
        $this->init();
    }

    /**
     * @param $class
     * @param Shop $shop
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     * @throws \Doctrine\ORM\ORMException
     */
    public function getRepository($class, Shop $shop = null)
    {
        return $this->repoFactory->createRepository($class, $shop);
    }
}
