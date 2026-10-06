<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        if ($user->status !== 'active') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.');
        }

        if (!in_array($user->role, $roles)) {
            // Redirect to appropriate dashboard according to user's actual role
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.'),
                'pembuat_materi' => redirect()->route('pembuat.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.'),
                'user' => redirect()->route('user.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.'),
                default => redirect()->route('home')->with('error', 'Akses ditolak.'),
            };
        }

        return $next($request);
    }
}
