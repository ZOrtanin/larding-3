<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Показывает страницу управления пользователями.
     */
    public function index(): View
    {
        $roles = Role::query()
            ->where('slug', '!=', 'super_admin')
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        $users = User::query()
            ->with('role')
            ->latest()
            ->paginate(20, [
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'role_id',
            ]);

        return view('users.index', [
            'roles' => $roles,
            'users' => $users,
        ]);
    }

    /**
     * Обновляет роль выбранного пользователя.
     */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $allowedRoleIds = Role::query()
            ->where('slug', '!=', 'super_admin')
            ->pluck('id')
            ->all();

        $validated = $request->validate([
            'role_id' => ['nullable', Rule::in($allowedRoleIds)],
        ]);

        if ($user->role?->slug === 'super_admin') {
            return redirect()
                ->route('users.index')
                ->with('status', 'user-role-protected');
        }

        $user->update([
            'role_id' => $validated['role_id'] ?? null,
        ]);

        return redirect()
            ->route('users.index')
            ->with('status', 'user-role-updated');
    }

    /**
     * Удаляет выбранного пользователя.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ((int) $request->user()->id === (int) $user->id) {
            return redirect()
                ->route('users.index')
                ->with('status', 'user-delete-self-blocked');
        }

        if ($user->role?->slug === 'super_admin') {
            return redirect()
                ->route('users.index')
                ->with('status', 'user-delete-protected');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('status', 'user-deleted');
    }

    /**
     * Обновляет пароль выбранного пользователя.
     */
    public function updatePassword(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($user->role?->slug === 'super_admin') {
            return redirect()
                ->route('users.index')
                ->with('status', 'user-password-protected');
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('users.index')
            ->with('status', 'user-password-updated');
    }
}
