<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next, $role)
    {
        // ឆែកមើលថាតើ User បាន Login ហើយឬនៅ និងមាន Role ត្រូវគ្នាដែរឬទេ
        if (!Auth::check() || Auth::user()->role !== $role) {
            // បើមិនត្រូវទេ ឱ្យវាលោតទៅ Dashboard វិញជាមួយសារប្រាប់
            return redirect('/dashboard')->with('error', 'អ្នកគ្មានសិទ្ធិចូលទៅកាន់ផ្នែកនេះឡើយ!');
        }

        return $next($request);
    }
}
