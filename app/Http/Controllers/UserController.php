<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('taxiRequests')
            ->withSum('taxiRequests', 'price')
            ->get();

        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['superadmin', 'admin', 'usuario'])],
        ]);

        // Security check: Admin cannot create Super Admin
        if (auth()->user()->role === 'admin' && $validated['role'] === 'superadmin') {
            return back()->with('error', 'No tienes permisos para crear un usuario Super Admin.');
        }

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return back()->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['superadmin', 'admin', 'usuario'])],
            'password' => 'nullable|string|min:8',
        ]);

        // Security check: Admin cannot modify Super Admin or promote to Super Admin
        if (auth()->user()->role === 'admin') {
            if ($user->role === 'superadmin') {
                return back()->with('error', 'No tienes permisos para modificar a un usuario Super Admin.');
            }
            if ($validated['role'] === 'superadmin') {
                return back()->with('error', 'No tienes permisos para asignar el rol de Super Admin.');
            }
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminarte a ti mismo.');
        }

        // Security check: Admin cannot delete Super Admin
        if (auth()->user()->role === 'admin' && $user->role === 'superadmin') {
            return back()->with('error', 'No tienes permisos para eliminar a un usuario Super Admin.');
        }

        $user->delete();

        return back()->with('success', 'Usuario eliminado correctamente.');
    }
}
