# Database Error Fix - WebViper Academy

## Problem Summary

The application logs revealed critical database errors:

1. **Missing Tables**: `website_players` and `programs` tables do not exist
2. **Migration Failures**: Historical logs show migrations failed due to MySQL permission issues on December 7, 2025
3. **Foreign Key Errors**: `Integrity constraint violation: 1452` because parent tables are missing

## Root Cause

The database migrations were never successfully executed on the production database. The logs show:
- `SQLSTATE[HY000] [1698] Access denied for user 'root'@'localhost'`
- `SQLSTATE[HY000] [1044] Access denied for user 'viper'@'%' to database 'viper'`

## Files Created/Modified

### New Files
1. `database/seeders/ProgramSeeder.php` - Seeds the programs table with default academy programs
2. `fix_database.sh` - Automated fix script

### Modified Files
1. `app/Http/Controllers/Website/PlayerController.php` - Added graceful handling for missing tables

## Solution

### Option 1: Automated Fix (Recommended)

```bash
# SSH into your server
ssh your-user@your-server

# Navigate to the project
cd /var/www/vipers

# Make the fix script executable
chmod +x fix_database.sh

# Run the fix script
sudo ./fix_database.sh
```

### Option 2: Manual Fix

```bash
cd /var/www/vipers

# 1. Clear caches
php artisan config:clear
php artisan cache:clear

# 2. Run migrations
php artisan migrate --force

# 3. Seed programs table
php artisan db:seed --class=ProgramSeeder --force

# 4. Clear remaining caches
php artisan route:clear
php artisan view:clear

# 5. Verify tables exist
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
```

## Expected Results

After running the fix, you should see:
- ✓ `programs` table with 5 default programs
- ✓ `website_players` table created
- ✓ `enrollments` table created
- ✓ All foreign key constraints satisfied
- No more "table doesn't exist" errors in logs

## Verification

1. **Check Players Page**: Visit `http://your-domain.com/players`
2. **Check Enrollment**: Visit `http://your-domain.com/enroll`
3. **Check Admin Dashboard**: Visit `http://your-domain.com/admin`

## Troubleshooting

### If migrations fail with permission errors:

1. Check MySQL user permissions:
```sql
-- Connect to MySQL as root
mysql -u root -p

-- Check user permissions
SELECT user, host FROM mysql.user WHERE user = 'root';

-- Grant permissions if needed
GRANT ALL PRIVILEGES ON viper.* TO 'root'@'localhost' IDENTIFIED BY 'your-password';
FLUSH PRIVILEGES;
```

### If tables already exist but are empty:

```bash
# Rollback and re-run migrations (WARNING: This will delete data)
php artisan migrate:refresh --force

# Or just seed the data
php artisan db:seed --force
```

### Check MySQL connection:

```bash
# Test MySQL connection
php artisan tinker --execute="
try {
    \$pdo = DB::connection()->getPdo();
    echo '✓ Database connected successfully\\n';
    echo 'Database: ' . DB::connection()->getDatabaseName() . '\\n';
} catch (Exception \$e) {
    echo '✗ Database connection failed: ' . \$e->getMessage() . '\\n';
}
"
```

## Support

If you continue to experience issues:
1. Check the logs: `tail -f storage/logs/laravel.log`
2. Verify your `.env` database settings
3. Ensure MySQL server is running
4. Check disk space: `df -h`
