<?php

namespace App\Controller;

use App\Controller;
use App\Model\Shop;
use App\Repository\ShopRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ShopController extends Controller
{
    private $shopRepo;

    public function init()
    {
        $this->getShopRepository();
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response)
    {
        $shops = $this->shopRepo->findAll();
        return $response->withJson([
            'shops' => $shops
        ]);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    private function getShopRepository()
    {
        $this->shopRepo = $this->repoFactory->createRepository(Shop::class);
    }

    /**
     * @param ShopRepository $shopRepo
     * @return $this
     */
    public function setShopRepository(ShopRepository $shopRepo)
    {
        $this->shopRepo = $shopRepo;
        return $this;
    }
}
