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
        '6496982679:AAELMYScxqkTvEFQaBjy5Ml1wULPF2lbYT8/webhook',
        '/webhook',
        '/post-to-telegram',
    ];
}
