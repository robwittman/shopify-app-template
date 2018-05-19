<?php

define("BASEPATH", dirname(__FILE__, 2));

require_once(BASEPATH.'/bootstrap.php');

$repository = $container->get('repository.shop');

$shop = $repository->findOneBy([
    'myshopify_domain' => $argv[1]
]);

$event = new \App\Event\ShopUninstalledEvent($shop);

/** @var \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher */
$dispatcher = $container->get('event.dispatcher');

$dispatcher->dispatch(\App\Event\ShopUninstalledEvent::NAME, $event);
