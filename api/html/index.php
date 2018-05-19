<?php

define("BASEPATH", dirname(__FILE__, 2));

require_once BASEPATH.'/bootstrap.php';

$app = new Slim\App($container);

App\Route::registerRoutes($app);

$app->run();