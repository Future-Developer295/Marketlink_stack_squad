<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * A farmer can use the dashboard only after an admin approved the account.
 * Pending / rejected farmers are sent to the status page instead.
 */
class EnsureFarmerApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'farmer' && ! $user->is_active) {
            return redirect()->route('farmer_pending');
        }

        return $next($request);
    }
}
