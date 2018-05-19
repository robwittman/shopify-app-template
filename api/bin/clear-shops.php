<?php

require_once '../bootstrap.php';

/** @var \App\Repository\ShopRepository $repository */
$repository = $container->get('repository.shop');

/** @var \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher */
$dispatcher = $container->get('event.dispatcher');

$shops = $repository->findAll();

foreach ($shops as $shop) {
    $event = new \App\Event\ShopUninstalledEvent($shop);
    $dispatcher->dispatch(
        \App\Event\ShopUninstalledEvent::NAME,
        $event
    );
}
