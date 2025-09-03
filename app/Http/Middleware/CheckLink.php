<?php

namespace App\Http\Middleware;

use App\Models\Link;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLink
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->route('token');

        $link = $token ? Link::active($token)->first() : null;

        if (!$link) {
            return redirect()->route('home')
                ->with('error', 'Link is missing or expired');
        }

        // Add link to request for use in controllers
        $request->merge(['link' => $link]);

        return $next($request);
    }
}
