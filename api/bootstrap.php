<?php

require_once 'vendor/autoload.php';

use Flexsounds\Component\SymfonyContainerSlimBridge\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\Config\FileLocator;

$container = new ContainerBuilder();
$loader = new PhpFileLoader($container, new FileLocator(dirname(__FILE__)));
$loader->load('container.php');
if (file_exists('container.env.php')) {
    $loader->load('container.env.php');
}