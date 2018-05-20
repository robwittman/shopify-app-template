<?php

define("BASEPATH", dirname(__FILE__, 2));

require_once BASEPATH.'/vendor/autoload.php';

$api = new Shopify\Api([
    'api_key' => getenv("SHOPIFY_API_KEY"),
    'api_secret' => getenv("SHOPIFY_API_SECRET"),
    'myshopify_domain' => 'https://importer-testing.myshopify.com'
]);

$helper = $api->getOAuthHelper();
/**
 * array(4) { ["code"]=> string(32) "bfcef8908034c4472fa9d86507fdcb6d" ["hmac"]=> string(64) "6111e7814fe9efe0ac543b047234f8ea5ab2b0b0a762a7c1ae6e2affbc2889c0" ["shop"]=> string(30) "importer-testing.myshopify.com" ["timestamp"]=> string(10) "1526788815" }
 */
$token = $helper->getAccessToken('bfcef8908034c4472fa9d86507fdcb6d');

var_dump($token);

