<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/user/socials/twitter/link/callback',
        '/shop/credit/add/paypal/notification',
        '/shop/credit/add/dedipass/notification',
        '/shop/credit/add/hipay/notification',
        '/shop/credit/add/paysafecard/notification'
    ];
}
