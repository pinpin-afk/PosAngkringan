<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $baseRoles = [
            [
                'name' => 'admin',
                'description' => 'Administrator dengan akses penuh',
                'permissions' => [
                    'dashboard' => true,
                    'pos' => true,
                    'products' => true,
                    'orders' => true,
                    'users' => true
                ]
            ],
            [
                'name' => 'kasir',
                'description' => 'Kasir dengan akses POS dan order',
                'permissions' => [
                    'dashboard' => false,
                    'pos' => true,
                    'products' => false,
                    'orders' => true,
                    'users' => false
                ]
            ],
            [
                'name' => 'owner',
                'description' => 'Pemilik usaha, akses ringkasan & kontrol tinggi',
                'permissions' => [
                    'dashboard' => true,
                    'pos' => false,
                    'products' => true,
                    'orders' => true,
                    'users' => true
                ]
            ],
        ];
        // Merge with distinct roles found in users table (fallback permissions all false)
        $distinctRoles = User::query()->select('role')->distinct()->pluck('role')->filter()->values();
        $known = collect($baseRoles)->keyBy('name');
        foreach ($distinctRoles as $r) {
            if (!$known->has($r)) {
                $known->put($r, [
                    'name' => $r,
                    'description' => 'Role dinamis dari data pengguna',
                    'permissions' => [
                        'dashboard' => false,
                        'pos' => false,
                        'products' => false,
                        'orders' => false,
                        'users' => false,
                    ]
                ]);
            }
        }

        return response()->json(array_values($known->all()));
    }

    public function getStats()
    {
        $stats = [
            'admin' => User::where('role', 'admin')->count(),
            'kasir' => User::where('role', 'kasir')->count(),
            'total' => User::count()
        ];

        return response()->json($stats);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'required|array',
            'permissions.*' => 'string|in:dashboard,pos,products,orders,users'
        ]);

        // In a real application, you would store roles in a roles table
        // For now, we'll just return the created role data
        $role = [
            'name' => $request->name,
            'description' => $request->description,
            'permissions' => array_fill_keys($request->permissions, true)
        ];

        return response()->json($role, 201);
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,kasir,owner'
        ]);

        // Prevent changing the last admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return response()->json(['message' => 'Tidak dapat mengubah role admin terakhir'], 422);
        }

        $user->update(['role' => $request->role]);

        return response()->json($user);
    }
}
