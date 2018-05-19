<?php

namespace App;

use App\Model\Shop;
use Firebase\JWT\JWT;

class JwtHelper
{
    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $algorithm = 'HS256';

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * Creat a JWT token for the given shop
     * @param Shop $shop
     * @return string
     */
    public function createJwtToken(Shop $shop): string
    {
        return JWT::encode([
            'shop' => $shop
        ], $this->secretKey, $this->algorithm);
    }
}