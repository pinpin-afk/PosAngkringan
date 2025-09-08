<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class IsolatedGuardAuthController extends Controller
{
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $user = Auth::guard('admin')->user();
            
            // Check if user is admin
            if (!$user->isAdmin()) {
                Auth::guard('admin')->logout();
                return response()->json(['errors' => ['Hanya admin yang bisa login di sini.']], 422);
            }
            
            // Create unique session ID for admin
            $adminSessionId = 'admin_' . $user->id . '_' . time() . '_' . Str::random(10);
            
            // Regenerate session with new ID
            $request->session()->regenerate();
            
            // Store admin session data
            $request->session()->put('admin_session_id', $adminSessionId);
            $request->session()->put('admin_user_id', $user->id);
            $request->session()->put('admin_user_name', $user->name);
            $request->session()->put('admin_user_email', $user->email);
            $request->session()->put('admin_user_role', 'admin');
            
            // Store in database for tracking
            DB::table('admin_sessions')->insert([
                'session_id' => $adminSessionId,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            return response()->json(['success' => true, 'redirect' => route('admin.dashboard')]);
        }

        return response()->json(['errors' => ['Email atau password salah.']], 422);
    }
    
    public function kasirLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('kasir')->attempt($credentials)) {
            $user = Auth::guard('kasir')->user();
            
            // Check if user is kasir
            if (!$user->isKasir()) {
                Auth::guard('kasir')->logout();
                return response()->json(['errors' => ['Hanya kasir yang bisa login di sini.']], 422);
            }
            
            // Create unique session ID for kasir
            $kasirSessionId = 'kasir_' . $user->id . '_' . time() . '_' . Str::random(10);
            
            // Regenerate session with new ID
            $request->session()->regenerate();
            
            // Store kasir session data
            $request->session()->put('kasir_session_id', $kasirSessionId);
            $request->session()->put('kasir_user_id', $user->id);
            $request->session()->put('kasir_user_name', $user->name);
            $request->session()->put('kasir_user_email', $user->email);
            $request->session()->put('kasir_user_role', 'kasir');
            
            // Store in database for tracking
            DB::table('kasir_sessions')->insert([
                'session_id' => $kasirSessionId,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            return response()->json(['success' => true, 'redirect' => route('kasir.pos')]);
        }

        return response()->json(['errors' => ['Email atau password salah.']], 422);
    }
    
    public function adminLogout(Request $request)
    {
        $adminSessionId = $request->session()->get('admin_session_id');
        
        if ($adminSessionId) {
            // Delete admin session from database
            DB::table('admin_sessions')->where('session_id', $adminSessionId)->delete();
            
            // Clear admin session data
            $request->session()->forget([
                'admin_session_id',
                'admin_user_id',
                'admin_user_name',
                'admin_user_email',
                'admin_user_role'
            ]);
        }
        
        // Clear only admin authentication without affecting other sessions
        $this->clearGuardAuthentication($request, 'admin');
        
        return redirect()->route('admin.login')->with('success', 'Admin telah logout.');
    }
    
    public function kasirLogout(Request $request)
    {
        $kasirSessionId = $request->session()->get('kasir_session_id');
        
        if ($kasirSessionId) {
            // Delete kasir session from database
            DB::table('kasir_sessions')->where('session_id', $kasirSessionId)->delete();
            
            // Clear kasir session data
            $request->session()->forget([
                'kasir_session_id',
                'kasir_user_id',
                'kasir_user_name',
                'kasir_user_email',
                'kasir_user_role'
            ]);
        }
        
        // Clear only kasir authentication without affecting other sessions
        $this->clearGuardAuthentication($request, 'kasir');
        
        return redirect()->route('kasir.login')->with('success', 'Kasir telah logout.');
    }
    
    private function clearGuardAuthentication(Request $request, $guard)
    {
        // Get current user from specific guard
        $user = Auth::guard($guard)->user();
        
        if ($user) {
            // Clear Laravel auth session data for specific guard
            $request->session()->forget('login_' . $guard . '_' . sha1('App\Models\User'));
            
            // Clear any other auth-related session data for specific guard
            $request->session()->forget([
                'password_hash_' . $guard . '_' . sha1('App\Models\User'),
                'password_hash_' . $guard . '_' . sha1('App\Models\User'),
            ]);
        }
    }
    
    public function checkAdminSession(Request $request)
    {
        $adminSessionId = $request->session()->get('admin_session_id');
        
        if ($adminSessionId && DB::table('admin_sessions')->where('session_id', $adminSessionId)->exists()) {
            $user = Auth::guard('admin')->user();
            if ($user && $user->isAdmin()) {
                return response()->json([
                    'authenticated' => true,
                    'role' => 'admin',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                ]);
            }
        }
        
        return response()->json(['authenticated' => false]);
    }
    
    public function checkKasirSession(Request $request)
    {
        $kasirSessionId = $request->session()->get('kasir_session_id');
        
        if ($kasirSessionId && DB::table('kasir_sessions')->where('session_id', $kasirSessionId)->exists()) {
            $user = Auth::guard('kasir')->user();
            if ($user && $user->isKasir()) {
                return response()->json([
                    'authenticated' => true,
                    'role' => 'kasir',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                ]);
            }
        }
        
        return response()->json(['authenticated' => false]);
    }
}
