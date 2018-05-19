<?php

namespace App;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class Action
{
    public function __construct()
    {

    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $arguments)
    {

    }
}