<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AjaxRedirectToJson
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->expectsJson() || ! $response instanceof RedirectResponse) {
            return $response;
        }

        $session = $request->session();

        $errors = $session->get('errors');
        if ($errors && method_exists($errors, 'any') && $errors->any()) {
            $bag = $errors->getBag('default')->toArray();
            $session->forget(['errors', '_old_input']);

            return response()->json([
                'success' => false,
                'message' => collect($bag)->flatten()->first() ?? 'Please check the form and try again.',
                'errors' => $bag,
            ], 422);
        }

        $message = null;
        $type = 'success';
        foreach (['success', 'status', 'warning', 'error'] as $key) {
            if ($session->has($key)) {
                $message = $session->get($key);
                $type = $key === 'status' ? 'success' : $key;
                break;
            }
        }
        $session->forget(['success', 'status', 'warning', 'error']);

        return response()->json([
            'success' => $type !== 'error',
            'type' => $type,
            'message' => is_string($message) ? $message : null,
            'redirect' => $response->getTargetUrl(),
            'cart_count' => (int) array_sum((array) $session->get('cart', [])),
        ], $type === 'error' ? 422 : 200);
    }
}
