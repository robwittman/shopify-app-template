<?php

namespace App;

use App\Model\Product;
use App\Model\Shop;
use App\Model\Variant;
use App\ORM\TenantAwareInterface;

/**
 * In charge of registering all model classes, and wether
 * they're are a shared model, or tenant-specific. Makes them accessible
 * to Doctrine and other calling code.
 *
 * @package App
 */
class ClassRegister
{
    /**
     * @var array
     */
    public static $registry = [
        Shop::class,
        Product::class,
        Variant::class
    ];

    /**
     * @return array
     */
    public static function getSharedModels()
    {
        return array_filter(static::$registry, function($class) {
           return !static::isTenantModel($class);
        });
    }

    /**
     * @return array
     */
    public static function getTenantModels()
    {
        return array_filter(static::$registry, function($class) {
            return static::isTenantModel($class);
        });
    }

    /**
     * @param $class
     * @return array
     */
    protected static function getInterfaces($class)
    {
        return class_implements($class);
    }

    /**
     * @param $class
     * @return bool
     */
    protected static function isTenantModel($class)
    {
        $interfaces = static::getInterfaces($class);
        return in_array(TenantAwareInterface::class, $interfaces);
    }
}
