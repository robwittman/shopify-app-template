<?php

require_once '../bootstrap.php';

/** @var \App\Repository\ShopRepository $repository */
$repository = $container->get('repository.shop');

$factory = \Faker\Factory::create();

echo $faker->uuid;

for ($i = 0; $i < 25; $i++) {
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
        ->setMyshopifyDomain($domain.'.myshopify.com')
        ->setDatabaseName(preg_replace("/[^A-Za-z0-9 ]/", '', $domain))
        ->setDatabaseUserName($factory->md5)
        ->setDatabasePassword($factory->md5)
        ->setDatabasePort(3306);
    $repository->save($shop);
}
