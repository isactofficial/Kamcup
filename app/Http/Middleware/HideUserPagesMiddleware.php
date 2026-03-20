<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class HideUserPagesMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $hideUserPages = Cache::get('hide_user_pages', false);
        
        if ($hideUserPages && auth()->check() && !auth()->user()->isAdmin()) {
            return redirect()->route('profile.index')
                ->with('error', 'Fitur Komunitas, Teman, dan Feeds sedang disembunyikan oleh admin.');
        }

        return $next($request);
    }
}
