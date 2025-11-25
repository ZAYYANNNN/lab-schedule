<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Lab;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = $request->user();

        if ($user->role !== $role && $user->role !== 'superadmin') {
            abort(403);
        }

        return $next($request);
    }

}
