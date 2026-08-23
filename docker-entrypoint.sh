#!/bin/sh
set -e

# Clear config so current environment variables are loaded
php artisan config:clear

# Run database migrations
php artisan migrate --force

# TEMPORARY: seed initial content into the fresh database
php artisan db:seed --force

# TEMPORARY: reset admin password when explicitly enabled
if [ -n "$ADMIN_RESET_EMAIL" ] && [ -n "$ADMIN_RESET_PASSWORD" ]; then
    ADMIN_RESET_EMAIL="$ADMIN_RESET_EMAIL" ADMIN_RESET_PASSWORD="$ADMIN_RESET_PASSWORD" php artisan tinker --execute='
        $email = getenv("ADMIN_RESET_EMAIL");
        $password = getenv("ADMIN_RESET_PASSWORD");

        $user = App\Models\User::where("email", $email)->first();

        if (!$user) {
            fwrite(STDERR, "ADMIN RESET FAILED: User not found: " . $email . PHP_EOL);
            exit(1);
        }

        $user->password = Illuminate\Support\Facades\Hash::make($password);
        $user->save();

        echo "ADMIN PASSWORD RESET SUCCESSFULLY for " . $user->email . PHP_EOL;
    '
fi

# Cache config, routes, and views for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create the storage symlink
php artisan storage:link || true

exec "$@"