<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVendorIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        if (! $user->isVendor()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Only approved vendors can create and manage products.',
                ], 403);
            }

            abort(403, 'Only approved vendors can create and manage products.');
        }

        if ($user->vendor && ! $user->vendor->isApproved()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Only approved vendors can create and manage products.',
                ], 403);
            }

            abort(403, 'Only approved vendors can create and manage products.');
        }

        return $next($request);
    }
}
