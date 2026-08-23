<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminSeeder extends Seeder
{
    /** Modules that have Read (view) + Write (manage) permissions. */
    public const MODULES = [
        'pages', 'services', 'projects', 'team', 'faqs',
        'blog', 'messages', 'settings', 'users', 'roles', 'legal',
    ];

    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create view + manage permission for every module
        foreach (self::MODULES as $module) {
            Permission::firstOrCreate(['name' => 'view '.$module, 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => 'manage '.$module, 'guard_name' => 'web']);
        }

        // Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $admin      = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $editor     = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);

        // super-admin: everything
        $superAdmin->syncPermissions(Permission::all());

        // admin: full read/write on content + settings + legal, plus read/write messages (no users/roles)
        $admin->syncPermissions($this->permsFor(
            ['pages', 'services', 'projects', 'team', 'faqs', 'blog', 'messages', 'settings', 'legal'],
            ['view', 'manage']
        ));

        // editor: read/write content, read-only messages
        $editor->syncPermissions(array_merge(
            $this->permsFor(['services', 'projects', 'team', 'faqs', 'blog'], ['view', 'manage']),
            ['view messages']
        ));

        // First super-admin account (password preserved on re-run)
        $user = User::firstOrCreate(
            ['email' => 'admin@quintech.local'],
            ['name' => 'Quin Admin', 'password' => Hash::make('ChangeMe!2026')]
        );
        $user->syncRoles(['super-admin']);
    }

    private function permsFor(array $modules, array $actions): array
    {
        $out = [];
        foreach ($modules as $m) {
            foreach ($actions as $a) {
                $out[] = $a.' '.$m;
            }
        }

        return $out;
    }
}
