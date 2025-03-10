<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/blogs/post_comments/*', // Exclude all blog comment posts
        'api/auth/*', // Exclude all blog comment posts
        'api/blogs/*', // Exclude all blog comment posts
        'api/chats/*',
    ];
}
