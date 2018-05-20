<?php

namespace App\Controller;

use App\JwtHelper;
use App\Repository\ShopRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shopify\Api;
use Shopify\Object\Shop as ShopData;
use Shopify\Service\ShopService;

class AuthController
{
    protected $api;

    protected $shopRepo;

    protected $jwtHelper;

    public function __construct(ShopRepository $shopRepo, Api $api, JwtHelper $jwtHelper)
    {
        $this->api = $api;
        $this->shopRepo = $shopRepo;
        $this->jwtHelper = $jwtHelper;
    }

    public function install(ServerRequestInterface $request, ResponseInterface $response, array $arguments) : ResponseInterface
    {
        var_dump($request->getQueryParams());
        exit;
        $this->api->setMyshopifyDomain("https://{$request->getParam('shop')}");
        $helper = $this->api->getOAuthHelper();
        $token = $helper->getAccessToken($request->getParam('code'));
        var_dump($token);
        exit;
        $service = new ShopService($this->api);
        $data = $service->get();
        $this->persist($data);

    }

    public function token(ServerRequestInterface $request, ResponseInterface $response, array $arguments) : ResponseInterface
    {
        $shop = $this->shopRepo->findOneBy([
            'myshopify_domain' => $request->getHeader('X-SHOP-DOMAIN')
        ]);
        if (is_null($shop)) {
            return $response
                ->withStatus(401)
                ->withJson([
                    'error' => 'UNAUTHORIZED'
                ]);
        }
        $token = $this->jwtHelper->createJwtToken($shop);
        return $response->withJson([
            'token' => $token
        ]);
    }

    protected function persist(ShopData $shop)
    {

    }
}
