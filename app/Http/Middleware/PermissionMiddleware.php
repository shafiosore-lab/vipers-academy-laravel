<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Gate access by a required permission slug.
     *
     * Usage: Route::middleware('permission:players.view')
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to access this area.');
        }

        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if (!$user->hasPermission($permission)) {
            Log::warning('Permission denied', [
                'user_id'    => $user->id,
                'permission' => $permission,
                'url'        => $request->fullUrl(),
                'method'     => $request->method(),
            ]);

            return $this->deny($request, $permission);
        }

        return $next($request);
    }

    /**
     * Return the appropriate denial response for the request type.
     */
    private function deny(Request $request, string $permission): Response
    {
        $message = 'You do not have permission to access this resource.';

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'error'               => 'Access Denied',
                'message'             => $message,
                'required_permission' => $permission,
            ], 403);
        }

        if ($request->header('X-Inertia')) {
            return inertia('Errors/Forbidden', [
                'message'             => $message,
                'required_permission' => $permission,
            ])->toResponse($request)->setStatusCode(403);
        }

        return redirect()->back()
            ->with('error', $message)
            ->withInput();
    }
}
