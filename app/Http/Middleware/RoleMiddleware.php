<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
    public function handle(Request $request, Closure $next, $role)
    {
        // ១. ឆែកមើលថាតើ User បាន Login ហើយឬនៅ
        if (!Auth::check()) {
            return redirect('/')->with('error', 'សូមចូលគណនីរបស់អ្នកដើម្បីចូលទៅកាន់ផ្នែកនេះ!');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // ២. ឆែក Role ដោយប្រើ Method hasRole() ដែលយើងបានបង្កើតក្នុង Model User
        // បញ្ជាក់៖ $role គឺជាតម្លៃដែលយើងបញ្ជូនមកពី Route (ឧទាហរណ៍៖ 'admin' ឬ 'cashier')
        if (!$user->hasRole($role))
        {

            // ប្រសិនបើជា Cashier ព្យាយាមចូលកន្លែង Admin ឱ្យវាដេញមកផ្ទាំងលក់វិញ
            if ($user->hasRole('cashier'))
            {
                return redirect('/sales/create')->with('error', 'អ្នកគ្មានសិទ្ធិចូលទៅកាន់ផ្នែកគ្រប់គ្រងឡើយ!');
            }

            // បើមិនមែនទេ ឱ្យទៅ Dashboard វិញ
            return redirect('/')->with('error', 'អ្នកគ្មានសិទ្ធិចូលទៅកាន់ផ្នែកនេះឡើយ!');
        }

        return $next($request);
    }
}
