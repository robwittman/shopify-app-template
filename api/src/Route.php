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

        $app->map(['POST', 'GET'], '/auth/install', AuthController::class.':install');
        $app->get('/shops', 'controller.shops:index');
    }
}
