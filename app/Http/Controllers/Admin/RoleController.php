<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    private array $protected = ['super-admin'];

    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->orderBy('name')->get();

        return view('admin.roles.index', ['roles' => $roles]);
    }

    public function create()
    {
        return view('admin.roles.form', [
            'role'     => new Role(),
            'modules'  => $this->moduleMatrix(),
            'assigned' => [],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:120', 'unique:roles,name'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role = Role::create(['name' => Str::slug($data['name']), 'guard_name' => 'web']);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('ok', 'Role created.');
    }

    public function edit(Role $role)
    {
        if (in_array($role->name, $this->protected, true)) {
            return redirect()->route('admin.roles.index')->with('error', 'The super-admin role always has full access and can’t be edited.');
        }

        return view('admin.roles.form', [
            'role'     => $role,
            'modules'  => $this->moduleMatrix(),
            'assigned' => $role->permissions->pluck('name')->all(),
        ]);
    }

    public function update(Request $request, Role $role)
    {
        if (in_array($role->name, $this->protected, true)) {
            return redirect()->route('admin.roles.index')->with('error', 'The super-admin role can’t be edited.');
        }

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:120', Rule::unique('roles', 'name')->ignore($role->id)],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $role->update(['name' => Str::slug($data['name'])]);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.roles.index')->with('ok', 'Role updated.');
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, $this->protected, true) || $role->users()->count() > 0) {
            return back()->with('error', 'This role can’t be deleted (it’s a core role or still assigned to users).');
        }

        $role->delete();

        return back()->with('ok', 'Role deleted.');
    }

    /** Build a module => [read => perm, write => perm] matrix from existing permissions. */
    private function moduleMatrix(): array
    {
        $matrix = [];
        foreach (Permission::orderBy('name')->get() as $perm) {
            $parts = explode(' ', $perm->name, 2);
            if (count($parts) !== 2) {
                continue;
            }
            [$action, $module] = $parts;
            $key = $action === 'view' ? 'read' : 'write';
            $matrix[$module][$key] = $perm->name;
        }

        ksort($matrix);

        return $matrix;
    }
}
