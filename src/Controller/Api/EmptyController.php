<?php
namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Response;

class EmptyController
{
    public function __invoke()
    {
        return new Response();
    }
}