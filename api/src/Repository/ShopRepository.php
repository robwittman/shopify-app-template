<?php

namespace App\Repository;

use App\Event\ShopInstalledEvent;
use App\Model\Shop;
use App\Repository;

class ShopRepository extends Repository
{
    /**
     * @param Shop $shop
     */
    public function save(Shop $shop)
    {
        $this->getEntityManager()->persist($shop);
        $this->getEntityManager()->flush();
        $event = new ShopInstalledEvent($shop);
        $this->getEventDispatcher()->dispatch(
            ShopInstalledEvent::NAME,
            $event
        );
    }

    /**
     * @param Shop $shop
     */
    public function remove(Shop $shop)
    {
        $this->getEntityManager()->remove($shop);
        $this->getEntityManager()->flush();
    }
}
