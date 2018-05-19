<?php

namespace App\Event;

use App\Event;
use App\Model\Shop;

class ShopInstalledEvent extends Event
{
    const NAME = 'shop.installed';

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
