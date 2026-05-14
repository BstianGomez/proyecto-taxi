<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $currentUser = auth()->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\'-]+$/u'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:super_admin,admin,gestor,cliente'],
        ]);

        if ($currentUser->hasRole('gestor')) {
            if ($data['role'] !== 'cliente') {
                abort(403, 'El gestor solo puede crear clientes.');
            }
        }

        if ($data['role'] === 'super_admin' && ! $currentUser->isSuperAdmin()) {
            abort(403, 'No tienes permiso para crear super admin.');
        }

        if ($data['role'] === 'admin' && ! $currentUser->isSuperAdmin() && ! $currentUser->isAdmin()) {
            abort(403, 'No tienes permiso para crear admin.');
        }

        if ($data['role'] === 'admin' && $currentUser->hasRole('admin')) {
            // admin puede crear admin o gestor/cliente, pero no super admin
            // permitido
        }

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        $currentUser = auth()->user();
        if ($currentUser->hasRole('gestor')) {
            abort(403, 'No tienes permiso para editar usuarios.');
        }

        // Super admin no puede editar otros super admin
        if ($currentUser->isSuperAdmin() && $user->isSuperAdmin() && $user->id !== $currentUser->id) {
            abort(403, 'No puedes editar otros super admin.');
        }

        // Admin no puede editar super admin ni admin
        if ($currentUser->isAdmin() && ! $currentUser->isSuperAdmin()) {
            if ($user->isSuperAdmin() || ($user->isAdmin() && $user->id !== $currentUser->id)) {
                abort(403, 'No tienes permiso para editar este usuario.');
            }
        }

        if ($user->isSuperAdmin() && ! $currentUser->isSuperAdmin()) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->hasRole('gestor')) {
            abort(403, 'No tienes permiso para editar usuarios.');
        }

        // Super admin no puede editar otros super admin
        if ($currentUser->isSuperAdmin() && $user->isSuperAdmin() && $user->id !== $currentUser->id) {
            abort(403, 'No puedes editar otros super admin.');
        }

        // Admin no puede editar super admin ni admin
        if ($currentUser->isAdmin() && ! $currentUser->isSuperAdmin()) {
            if ($user->isSuperAdmin() || ($user->isAdmin() && $user->id !== $currentUser->id)) {
                abort(403, 'No tienes permiso para editar este usuario.');
            }
        }

        if ($user->isSuperAdmin() && ! $currentUser->isSuperAdmin()) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\'-]+$/u'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:super_admin,admin,gestor,cliente'],
        ]);

        if ($data['role'] === 'super_admin' && ! $currentUser->isSuperAdmin()) {
            abort(403, 'No tienes permiso para asignar super admin.');
        }

        if ($currentUser->hasRole('admin') && $data['role'] === 'super_admin') {
            abort(403, 'No tienes permiso para asignar super admin.');
        }

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ];

        if (! empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $currentUser = auth()->user();

        if ($user->id === $currentUser->id) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        // Gestor solo puede eliminar usuarios normales
        if ($currentUser->hasRole('gestor')) {
            if ($user->role !== 'cliente') {
                abort(403, 'Solo puedes eliminar usuarios normales.');
            }
        }

        // Super admin no puede eliminar otros super admin
        if ($currentUser->isSuperAdmin() && $user->isSuperAdmin()) {
            abort(403, 'No puedes eliminar otros super admin.');
        }

        // Admin no puede eliminar super admin ni admin
        if ($currentUser->isAdmin() && ! $currentUser->isSuperAdmin()) {
            if ($user->isSuperAdmin() || $user->isAdmin()) {
                abort(403, 'No tienes permiso para eliminar este usuario.');
            }
        }

        if ($user->isSuperAdmin() && ! $currentUser->isSuperAdmin()) {
            abort(403, 'No tienes permiso para eliminar este usuario.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
