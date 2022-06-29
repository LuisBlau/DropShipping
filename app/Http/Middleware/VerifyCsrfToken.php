<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        '/getbeaufyfort',
        '/filternewoldstocks',
        '/addproduct',
        '/getebaysellerproduct',
        '/post-products-shopify',
        '/get-products-shopify',
        '/remove-products-shopify',
    ];
}
