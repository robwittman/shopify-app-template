<?php

namespace App\Controller;

use App\JwtHelper;
use App\Model\Shop;
use App\Repository\ShopRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shopify\Api;
use Shopify\Object\Shop as ShopData;
use Shopify\Service\ShopService;

class AuthController
{
    protected $api;

    protected $shopRepo;

    protected $jwtHelper;

    public function __construct(ShopRepository $shopRepo, Api $api, JwtHelper $jwtHelper)
    {
        $this->api = $api;
        $this->shopRepo = $shopRepo;
        $this->jwtHelper = $jwtHelper;
    }

    public function install(ServerRequestInterface $request, ResponseInterface $response, array $arguments) : ResponseInterface
    {
        $this->api->setMyshopifyDomain($request->getParam('shop'));
        $helper = $this->api->getOAuthHelper();
        $token = $helper->getAccessToken($request->getParam('code'));
        $this->api->setAccessToken($token->access_token);
        $service = new ShopService($this->api);
        $data = $service->get();
        $this->persist($data);
        return $response->withRedirect(
            "https://{$request->getParams('shop')}/admin/apps/{$this->api->getApiKey()}"
        );
    }

    public function token(ServerRequestInterface $request, ResponseInterface $response, array $arguments) : ResponseInterface
    {
        $body = $request->getParsedBody();
        $shop = $this->shopRepo->findOneBy([
            'myshopify_domain' => $body['shop']
        ]);
        if (is_null($shop)) {
            return $response
                ->withStatus(401)
                ->withJson([
                    'error' => 'UNAUTHORIZED'
                ]);
        }
        $token = $this->jwtHelper->createJwtToken($shop);
        return $response->withJson([
            'token' => $token
        ]);
    }

    protected function persist(ShopData $data)
    {
        $shop = $this->shopRepo->findOneBy([
            'myshopify_domain' => $data->myshopify_domain
        ]);
        if (is_null($shop)) {
            $shop = new Shop();
            $shop ->setId($data->id);
        }
        $shop
            ->setName($data->name)
            ->setAddress1($data->address1)
            ->setAddress2($data->address2)
            ->setCity($data->city)
            ->setCountry($data->country)
            ->setCOuntryCode($data->country_code)
            ->setCountryName($data->country_name)
            ->setCustomerEmail($data->customer_email)
            ->setCurrency($data->currency)
            ->setDomain($data->domain)
            ->setEmail($data->email)
            ->setGoogleAppsDomain($data->google_apps_domain)
            ->setLatitude($data->latitude)
            ->setLongitude($data->longitude)
            ->setMoneyFormat($data->money_format)
            ->setMoneyWithCurrencyFormat($data->money_with_currency_format)
            ->setWeightUnit($data->weight_unit)
            ->setPlanName($data->plan_name)
            ->setPlanDisplayName($data->plan_display_name)
            ->setPhone($data->phone)
            ->setPrimaryLocale($data->primary_locale)
            ->setProvince($data->province)
            ->setProvinceCode($data->province_code)
            ->setShopOwner($data->shop_owner)
            ->setSource($data->source)
            ->setCountyTaxes($data->county_taxes)
            ->setTimezone($data->timezone)
            ->setIanaTimezone($data->iana_timezone)
            ->setZip($data->zip)
            ->setCreatedAt($data->created_at)
            ->setUpdatedAt($data->updated_at)
            ->setGoogleAppsLoginEnabled(false)
            ->setHasDiscounts(false)
            ->setHasGiftCards($data->has_gift_cards)
            ->setPasswordEnabled($data->password_enabled)
            ->setPreLaunchEnabled(false)
            ->setForceSsl($data->force_ssl)
            ->setTaxShipping(true)
            ->setTaxesIncluded(true)
            ->setHasStorefront(true)
            ->setSetupRequired(true)
            ->setCheckoutApiSupported(false)
            ->setMyshopifyDomain($data->myshopify_domain)
            ->setDatabaseName(preg_replace("/[^A-Za-z0-9]/", '_', $data->myshopify_domain))
            ->setDatabaseUserName(md5(uniqid(true)))
            ->setDatabasePassword(md5(uniqid(true)))
            ->setDatabasePort(3306);

        $this->shopRepo->save($shop);
        return true;
    }
}
