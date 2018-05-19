<?php

namespace App\Controller;

use App\JwtHelper;
use App\Repository\ShopRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shopify\Api;

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
        $shop = new Shop();
        // Get our shop data, and store in database
        return $response;
    }

    public function token(ServerRequestInterface $request, ResponseInterface $response, array $arguments) : ResponseInterface
    {
        return $response;
    }
}