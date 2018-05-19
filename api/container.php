<?php

use \Symfony\Component\DependencyInjection\Reference;
use \Symfony\Component\DependencyInjection\Parameter;

$machineName = 'local';
$env = getenv("ENVIRONMENT");

$container->setParameter('environment', getenv("ENVIRONMENT") ?: 'production');
$container->setParameter('domain', getenv("DOMAIN"));

$container->setParameter('db.connection', [
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => 'root',
    'dbname'   => 'shopify',
    'host'     => 'database'
]);

$container->register('log.stream_handler', \Monolog\Handler\StreamHandler::class)
    ->addArgument('php://stdout')
    ->addArgument(\Monolog\Logger::DEBUG);
$container->register('console.logger', \Monolog\Logger::class)
    ->addArgument('app')
    ->addMethodCall('pushHandler', [
        new Reference('log.stream_handler')
    ]);

$container->register('db.configuration', \Doctrine\ORM\Configuration::class)
    ->addArgument([__DIR__.'/src'])
    ->addArgument($container->getParameter('environment') === 'development')
    ->setFactory([
        \Doctrine\ORM\Tools\Setup::class,
        'createAnnotationMetadataConfiguration'
    ]);
$container->register('db.entity_manager', \Doctrine\ORM\EntityManager::class)
    ->addArgument($container->getParameter('db.connection'))
    ->addArgument(new Reference('db.configuration'))
    ->setFactory([
        \Doctrine\ORM\EntityManager::class,
        'create'
    ]);

$container->register('db.console_runner', \Doctrine\ORM\Tools\Console\ConsoleRunner::class)
    ->addArgument(new Reference('db.entity_manager'))
    ->setFactory([
        \Doctrine\ORM\Tools\Console\ConsoleRunner::class,
        'createHelperSet'
    ]);

$container->register('entity_manager.factory', \App\ORM\EntityManagerFactory::class)
    ->addArgument($container->getParameter('db.connection'))
    ->addArgument(new Reference('db.configuration'));
$container->register('repository.factory', \App\RepositoryFactory::class)
    ->addArgument(new Reference('entity_manager.factory'));

$container->register('repository.shop')
    ->addArgument(\App\Model\Shop::class)
    ->setFactory([
        new Reference('repository.factory'),
        'createRepository'
    ])
    ->addMethodCall('setEventDispatcher', [
        new Reference('event.dispatcher')
    ]);

$container->register('event.dispatcher', \Symfony\Component\EventDispatcher\EventDispatcher::class)
    ->addMethodCall('addListener', [
        \App\Event\ShopInstalledEvent::NAME,
        new Reference('listener.shop_installed.create_database')
    ])
    ->addMethodCall('addListener', [
        \App\Event\ShopUninstalledEvent::NAME,
        new Reference('listener.shop_uninstalled.drop_database')
    ]);

$container->register('listener.shop_installed.create_database', \App\Listener\ShopInstalled\CreateDatabaseListener::class)
    ->addArgument(new Reference('db.entity_manager'))
    ->addArgument(new Reference('console.command.migrate'));

$container->register('listener.shop_uninstalled.drop_database', \App\Listener\ShopUninstalled\DropTenantDatabaseListener::class)
    ->addArgument(new Reference('db.entity_manager'))
    ->addArgument(new Reference('repository.shop'));

$container->register('middleware.shop_authorization', \App\Middleware\ShopAuthorizationMiddleware::class)
    ->addArgument(new Reference('repository.shop'));

$container->register('controller.shops', \App\Controller\ShopController::class)
    ->addArgument(new Reference('repository.factory'));

$container->register('console.command.migrate', \App\Command\Database\Migrate::class)
    ->addArgument(new Reference('repository.shop'))
    ->addArgument(new Reference('entity_manager.factory'))
    ->addMethodCall('setLogger', [new Reference('console.logger')]);

$container->register('console.application', \Symfony\Component\Console\Application::class)
    ->addMethodCall('add', [new Reference('console.command.migrate')]);

$container->set('errorHandler', function($request, $response, $exception) {
    error_log(get_class($exception).'::'.$exception->getMessage());
    if (is_a($exception, \App\Exception\Exception::class)) {
        return $response->withStatus($exception->getApiStatusCode())
            ->withJson(array(
                'code' => $exception->getApiErrorCode(),
                'error' => $exception->getApiErrorMessage()
            ));
    } else {
        return $response->withStatus(500)
            ->withJson(array(
                'code' => 500,
                'error' => "An unexpected error occured"
            ));
    }
});
