<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SufficientBalanceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $balance = $user->balance;

        if ($request->is('withdrawal') && $balance < $request->input('amount')) {
            return redirect()->route('dashboard')->with('error', 'Insufficient funds for withdrawal');
        }
        if ($user->balance < $request->input('amount')) {
            return redirect()->route('dashboard')->with('error', 'Insufficient funds for transfer');
        }
        return $next($request);
    }
}
