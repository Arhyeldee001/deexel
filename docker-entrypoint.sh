#!/bin/sh
set -e

echo "Preparing Laravel writable directories..."

mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Laravel permissions ready."

# Clear config so current environment variables are loaded
php artisan config:clear

# Run database migrations
php artisan migrate --force

# Seed initial content
php artisan db:seed --force

# Reset admin password when explicitly enabled
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

# Cache config, routes, and views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage symlink
php artisan storage:link || true

exec "$@"
