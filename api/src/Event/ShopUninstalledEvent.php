<?php

namespace App\Event;

use App\Event;
use App\Model\Shop;

class ShopUninstalledEvent extends Event
{
    const NAME = 'shop.uninstalled';

    protected $shop;

    public function __construct(Shop $shop)
    {
        $this->shop = $shop;
    }

    public function getShop()
    {
        return $this->shop;
    }
}
