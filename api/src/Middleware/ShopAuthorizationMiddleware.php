<?php

namespace App\Middleware;

use App\Repository\ShopRepository;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ShopAuthorizationMiddleware
{
    private $shopRepository;

    public function __construct(ShopRepository $shopRepository)
    {
        $this->shopRepository = $shopRepository;
    }

    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next)
    {
        $jwt = $request->getAttribute('jwt');
        if (!$jwt) {
            return $next($request, $response);
        }
        $shop = $this->shopRepository->findOneBy([
            'id' => $jwt->id
        ]);

        if (is_null($shop)) {
            throw new \Exception("Invalid authorization");
        }
        $request = $request->withAttribute('shop', $shop);
        return $next($request, $response);
    }
}
