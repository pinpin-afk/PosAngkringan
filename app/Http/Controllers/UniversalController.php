<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UniversalController extends Controller
{
    public function dashboard()
    {
        if (auth('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        if (auth('kasir')->check()) {
            return redirect()->route('kasir.pos');
        }

        // Jika role/guard tidak valid
        auth('admin')->logout();
        auth('kasir')->logout();
        return redirect()->route('login')->withErrors(['email' => 'Role tidak valid.']);
    }

    public function pos()
    {
        if (auth('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        if (auth('kasir')->check()) {
            return redirect()->route('kasir.pos');
        }

        // Jika role/guard tidak valid
        auth('admin')->logout();
        auth('kasir')->logout();
        return redirect()->route('login')->withErrors(['email' => 'Role tidak valid.']);
    }
}
