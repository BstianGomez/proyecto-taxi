<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $currentUser = auth()->user();

        // Super admin puede ver a todos excepto otros super admins
        if ($currentUser->role === 'super_admin') {
            $users = User::where('role', '!=', 'super_admin')
                ->orWhere('id', $currentUser->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // Admin puede ver gestores y clientes
        elseif ($currentUser->role === 'admin') {
            $users = User::whereIn('role', ['gestor', 'cliente'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
        // Gestor solo puede ver clientes
        elseif ($currentUser->role === 'gestor') {
            $users = User::where('role', 'cliente')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            abort(403);
        }

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $currentUser = auth()->user();
        $availableRoles = $this->getAvailableRoles($currentUser->role);

        return view('users.create', compact('availableRoles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $currentUser = auth()->user();
        $availableRoles = $this->getAvailableRoles($currentUser->role);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in($availableRoles)],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Show the form for editing a user
     */
    public function edit(User $user)
    {
        $currentUser = auth()->user();

        // Verificar permisos para editar
        if (! $this->canManageUser($currentUser, $user)) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        $availableRoles = $this->getAvailableRoles($currentUser->role);

        return view('users.edit', compact('user', 'availableRoles'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $currentUser = auth()->user();

        // Verificar permisos
        if (! $this->canManageUser($currentUser, $user)) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        $availableRoles = $this->getAvailableRoles($currentUser->role);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in($availableRoles)],
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        $currentUser = auth()->user();

        // No puede eliminarse a sí mismo
        if ($currentUser->id === $user->id) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        // Verificar permisos
        if (! $this->canManageUser($currentUser, $user)) {
            abort(403, 'No tienes permiso para eliminar este usuario.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Get available roles based on current user role
     */
    private function getAvailableRoles($currentUserRole)
    {
        return match ($currentUserRole) {
            'super_admin' => ['admin', 'gestor', 'cliente'],
            'admin' => ['gestor', 'cliente'],
            'gestor' => ['cliente'],
            default => [],
        };
    }

    /**
     * Check if current user can manage target user
     */
    private function canManageUser($currentUser, $targetUser)
    {
        // Super admin puede gestionar a todos menos otros super admins
        if ($currentUser->role === 'super_admin') {
            return $targetUser->role !== 'super_admin';
        }

        // Admin puede gestionar gestores y clientes
        if ($currentUser->role === 'admin') {
            return in_array($targetUser->role, ['gestor', 'cliente']);
        }

        // Gestor solo puede gestionar clientes
        if ($currentUser->role === 'gestor') {
            return $targetUser->role === 'cliente';
        }

        return false;
    }
}
