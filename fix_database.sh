#!/bin/bash

# Database Fix Script for WebViper Academy
# This script resolves the missing table issues identified in the error logs

echo "========================================="
echo "WebViper Academy - Database Fix Script"
echo "========================================="
echo ""

# Navigate to the project directory
cd /var/www/vipers

# Step 1: Clear any cached configuration
echo "[1/6] Clearing configuration cache..."
php artisan config:clear
php artisan cache:clear
echo "✓ Configuration cache cleared"
echo ""

# Step 2: Check migration status
echo "[2/6] Checking migration status..."
php artisan migrate:status
echo ""

# Step 3: Run migrations (this will create missing tables)
echo "[3/6] Running database migrations..."
php artisan migrate --force
echo ""

# Step 4: Seed the programs table
echo "[4/6] Seeding programs table..."
php artisan db:seed --class=ProgramSeeder --force
echo ""

# Step 5: Clear routes and views cache
echo "[5/6] Clearing routes and views cache..."
php artisan route:clear
php artisan view:clear
echo ""

# Step 6: Verify the fix
echo "[6/6] Verifying database tables..."
php artisan tinker --execute="
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

\$tables = ['migrations', 'users', 'programs', 'enrollments', 'website_players', 'players'];
foreach (\$tables as \$table) {
    if (Schema::hasTable(\$table)) {
        \$count = DB::table(\$table)->count();
        echo \"✓ \$table table exists (\$count records)\\n\";
    } else {
        echo \"✗ \$table table is MISSING\\n\";
    }
}
"
echo ""

echo "========================================="
echo "Database fix completed!"
echo "========================================="
echo ""
echo "Next steps:"
echo "1. Check your application at http://your-domain.com/players"
echo "2. Verify enrollment form works at http://your-domain.com/enroll"
echo "3. Check logs: tail -f storage/logs/laravel.log"
echo ""
