<?php

namespace App;

use App\Controller\AuthController;
use App\Controller\ShopController;
use Slim\App;

class Route
{
    public static function registerRoutes(App $app)
    {
        $app->get('/', function($request, $response) {
            return $response->withJson([
                'success' => true
            ]);
        });
        $app->options('/{routes:.+}', function($request, $response) {
            return $response->withStatus(200);
        });

        $app->map(['GET', 'POST'], '/auth/install', 'controller.auth:install');
        $app->post('/auth/token', 'controller.auth:token');

        $app->get('/shops', 'controller.shops:index');

        $app->add(function ($req, $res, $next) {
            $response = $next($req, $res);
            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
        });
    }
}
