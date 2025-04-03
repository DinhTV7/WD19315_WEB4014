<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Nếu không đăng nhặp và không phải là admin thì không được phép truy cập
        if (!Auth::check() || !Auth::user()->isRoleAdmin()) {
            return redirect()->route('login')
                ->withErrors('Bạn không đủ trình để truy cập vào trang admin');
        }
        return $next($request);
    }
}
