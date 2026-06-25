<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->hasAnyRole(['Super Admin', 'Registrar', 'Training Coordinator', 'Instructor'])) {
            abort(403, 'Unauthorized access to admin area.');
        }

        return $next($request);
    }
}
