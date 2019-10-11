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
	     'administrator/galleries/data/store',
        '/auth/user/in/customer',
        '/test/xml/send',
        '/customer/logout',
        '/logout'
    ];
}
