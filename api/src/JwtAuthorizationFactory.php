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
            "relaxed" => ["localhost", "api.local"],
            "error" => function ($response, $arguments) {
                return $response
                    ->withHeader("Content-Type", "application/json")
                    ->withStatus(403)
                    ->withJson(json_encode([
                        'error' => "Invalid access token",
                        'message' => $arguments['message']
                    ]));
            }
        ]);
    }
}
