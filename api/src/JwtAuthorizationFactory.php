<?php

namespace App;

use Tuupola\Middleware\JwtAuthentication;

class JwtAuthorizationFactory
{
    private $secretKey;

    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function createJwtAuthenticationMiddleware()
    {
        return new JwtAuthentication([
            'secret' => $this->secretKey,
            'algorithm' => ['HS256'],
            'attribute' => 'jwt',
            "error" => function ($response) {
                return $response
                    ->withHeader("Content-Type", "application/json")
                    ->withStatusCode(403)
                    ->withJson(json_encode([
                        'error' => "Invalid access token"
                    ]));
            }
        ]);
    }
}