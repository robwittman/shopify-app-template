<?php

namespace App;

abstract class Model implements \JsonSerializable
{
    /**
     * @var array
     */
    static $hidden = [];

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), function($key) {
            return !in_array($key, static::$hidden);
        }, ARRAY_FILTER_USE_KEY);
    }
}
