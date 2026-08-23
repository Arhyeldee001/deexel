<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->orderBy('name')->get();

        return view('admin.users.index', ['users' => $users]);
    }

    public function create()
    {
        return view('admin.users.form', [
            'user'  => new User(),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:190'],
            'email'    => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'roles'    => ['required', 'array', 'min:1'],
            'roles.*'  => ['exists:roles,name'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->syncRoles($data['roles']);

        return redirect()->route('admin.users.index')->with('ok', 'User created.');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', [
            'user'  => $user,
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:190'],
            'email'    => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'roles'    => ['required', 'array', 'min:1'],
            'roles.*'  => ['exists:roles,name'],
        ]);

        // Don't let the last super-admin lose that role.
        if ($user->hasRole('super-admin') && ! in_array('super-admin', $data['roles'], true)
            && User::role('super-admin')->count() <= 1) {
            return back()->with('error', 'You can’t remove the last super-admin.');
        }

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);
        if (! empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }
        $user->syncRoles($data['roles']);

        return redirect()->route('admin.users.index')->with('ok', 'User updated.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You can’t delete your own account.');
        }
        if ($user->hasRole('super-admin') && User::role('super-admin')->count() <= 1) {
            return back()->with('error', 'You can’t delete the last super-admin.');
        }

        $user->delete();

        return back()->with('ok', 'User deleted.');
    }
}
