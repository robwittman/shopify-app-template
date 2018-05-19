<?php

define("BASEPATH", dirname(__FILE__, 2));

require_once(BASEPATH.'/bootstrap.php');

use Faker\Factory;

$factory = Factory::create();

$shop = new \App\Model\Shop();
$domain = $factory->domainWord;
$shop
    ->setId($factory->randomNumber(7))
    ->setName($factory->name)
    ->setCreatedAt($factory->dateTime)
    ->setUpdatedAt($factory->dateTime)
    ->setGoogleAppsLoginEnabled(false)
    ->setHasDiscounts(false)
    ->setHasGiftCards(false)
    ->setPasswordEnabled(false)
    ->setPreLaunchEnabled(false)
    ->setForceSsl(false)
    ->setTaxShipping(false)
    ->setTaxesIncluded(false)
    ->setHasStorefront(false)
    ->setSetupRequired(false)
    ->setCheckoutApiSupported(false)
    ->setMyshopifyDomain($factory->domainWord.'.myshopify.com')
    ->setDatabaseName(str_replace('.', '_', $domain))
    ->setDatabaseUserName(str_replace('.', '_', $domain))
    ->setDatabasePassword($factory->uuid)
    ->setDatabasePort(3306);

/** @var \App\Repository\ShopRepository $repository */
$repository = $container->get('repository.shop');
$repository->save($shop);
