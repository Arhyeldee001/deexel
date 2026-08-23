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
            Permission::firstOrCreate([
                'name' => 'view '.$module,
                'guard_name' => 'web',
            ]);

            Permission::firstOrCreate([
                'name' => 'manage '.$module,
                'guard_name' => 'web',
            ]);
        }

        // Roles
        $superAdmin = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web',
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $editor = Role::firstOrCreate([
            'name' => 'editor',
            'guard_name' => 'web',
        ]);

        // Super-admin gets everything
        $superAdmin->syncPermissions(Permission::all());

        // Admin permissions
        $admin->syncPermissions($this->permsFor(
            [
                'pages',
                'services',
                'projects',
                'team',
                'faqs',
                'blog',
                'messages',
                'settings',
                'legal',
            ],
            ['view', 'manage']
        ));

        // Editor permissions
        $editor->syncPermissions(array_merge(
            $this->permsFor(
                [
                    'services',
                    'projects',
                    'team',
                    'faqs',
                    'blog',
                ],
                ['view', 'manage']
            ),
            ['view messages']
        ));

        /*
         * Production admin account.
         *
         * The email and password come from environment variables.
         * This prevents production credentials from being stored in Git.
         */
        $email = env('ADMIN_EMAIL', 'admin@deexeladmin.com');
        $password = env('ADMIN_PASSWORD');

        if (!$password) {
            throw new \RuntimeException(
                'ADMIN_PASSWORD is not configured.'
            );
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Quin Admin',
                'password' => Hash::make($password),
            ]
        );

        // Make sure the account always has the super-admin role.
        $user->syncRoles(['super-admin']);
    }

    private function permsFor(array $modules, array $actions): array
    {
        $out = [];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $out[] = $action.' '.$module;
            }
        }

        return $out;
    }
}