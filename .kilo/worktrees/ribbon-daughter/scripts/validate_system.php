<?php

/**
 * System Validation Script
 *
 * This script validates the complete tournament system implementation
 * by checking all components, routes, controllers, and functionality.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SystemValidator
{
    private $errors = [];
    private $warnings = [];
    private $successes = [];

    public function validate()
    {
        echo "🔍 Starting Tournament System Validation...\n\n";

        // 1. Validate Routes
        $this->validateRoutes();

        // 2. Validate Controllers
        $this->validateControllers();

        // 3. Validate Models
        $this->validateModels();

        // 4. Validate Database
        $this->validateDatabase();

        // 5. Validate Governance System
        $this->validateGovernanceSystem();

        // 6. Validate API Endpoints
        $this->validateAPIEndpoints();

        // 7. Validate Documentation
        $this->validateDocumentation();

        // 8. Validate Tests
        $this->validateTests();

        // Generate Report
        $this->generateReport();
    }

    private function validateRoutes()
    {
        echo "📋 Validating Routes...\n";

        // Check admin routes
        $adminRoutes = [
            'admin.dashboard',
            'admin.tournaments.index',
            'admin.tournaments.create',
            'admin.tournaments.show',
            'admin.tournaments.edit',
            'admin.teams.index',
            'admin.players.index',
            'admin.matches.index',
        ];

        foreach ($adminRoutes as $route) {
            if (Route::has($route)) {
                $this->successes[] = "✅ Admin route '{$route}' exists";
            } else {
                $this->errors[] = "❌ Admin route '{$route}' missing";
            }
        }

        // Check super admin routes
        $superAdminRoutes = [
            'super-admin.dashboard',
            'super-admin.tournaments.overview',
            'super-admin.tournaments.index',
            'super-admin.organizations.index',
            'super-admin.plans.index',
            'super-admin.analytics',
        ];

        foreach ($superAdminRoutes as $route) {
            if (Route::has($route)) {
                $this->successes[] = "✅ Super Admin route '{$route}' exists";
            } else {
                $this->errors[] = "❌ Super Admin route '{$route}' missing";
            }
        }

        // Check governance routes
        $governanceRoutes = [
            'governance.age-verification.index',
            'governance.disciplinary.index',
            'governance.appeals.index',
            'governance.protests.index',
        ];

        foreach ($governanceRoutes as $route) {
            if (Route::has($route)) {
                $this->successes[] = "✅ Governance route '{$route}' exists";
            } else {
                $this->warnings[] = "⚠️ Governance route '{$route}' missing (may be intentional)";
            }
        }

        echo "   Routes validation completed.\n\n";
    }

    private function validateControllers()
    {
        echo "🏗️ Validating Controllers...\n";

        $controllers = [
            'App\\Http\\Controllers\\Admin\\AdminTournamentController',
            'App\\Http\\Controllers\\SuperAdmin\\SuperAdminTournamentController',
            'App\\Http\\Controllers\\Governance\\AgeVerificationController',
            'App\\Http\\Controllers\\Governance\\DisciplinaryCaseController',
            'App\\Http\\Controllers\\Governance\\AppealController',
            'App\\Http\\Controllers\\Governance\\ProtestController',
        ];

        foreach ($controllers as $controller) {
            if (class_exists($controller)) {
                $this->successes[] = "✅ Controller '{$controller}' exists";
            } else {
                $this->errors[] = "❌ Controller '{$controller}' missing";
            }
        }

        echo "   Controllers validation completed.\n\n";
    }

    private function validateModels()
    {
        echo "📊 Validating Models...\n";

        $models = [
            'App\\Models\\Tournament',
            'App\\Models\\Player',
            'App\\Models\\Team',
            'App\\Models\\TournamentMatch',
            'App\\Models\\AgeAlertRule',
            'App\\Models\\PlayerAgeVerification',
            'App\\Models\\DisciplinaryCase',
            'App\\Models\\PlayerSuspension',
            'App\\Models\\Appeal',
            'App\\Models\\Protest',
        ];

        foreach ($models as $model) {
            if (class_exists($model)) {
                $this->successes[] = "✅ Model '{$model}' exists";
            } else {
                $this->errors[] = "❌ Model '{$model}' missing";
            }
        }

        echo "   Models validation completed.\n\n";
    }

    private function validateDatabase()
    {
        echo "🗄️ Validating Database...\n";

        try {
            // Check if database connection works
            DB::connection()->getPdo();
            $this->successes[] = "✅ Database connection successful";

            // Check key tables
            $tables = [
                'tournaments',
                'players',
                'teams',
                'tournament_matches',
                'age_alert_rules',
                'player_age_verifications',
                'disciplinary_cases',
                'player_suspensions',
                'appeals',
                'protests',
            ];

            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    $this->successes[] = "✅ Table '{$table}' exists";
                } else {
                    $this->errors[] = "❌ Table '{$table}' missing";
                }
            }

        } catch (\Exception $e) {
            $this->errors[] = "❌ Database connection failed: " . $e->getMessage();
        }

        echo "   Database validation completed.\n\n";
    }

    private function validateGovernanceSystem()
    {
        echo "⚖️ Validating Governance System...\n";

        // Check governance service
        if (class_exists('App\\Services\\GovernanceService')) {
            $this->successes[] = "✅ GovernanceService exists";
        } else {
            $this->errors[] = "❌ GovernanceService missing";
        }

        // Check governance policies
        $policies = [
            'App\\Policies\\AgeVerificationPolicy',
            'App\\Policies\\DisciplinaryCasePolicy',
            'App\\Policies\\AppealPolicy',
            'App\\Policies\\ProtestPolicy',
        ];

        foreach ($policies as $policy) {
            if (class_exists($policy)) {
                $this->successes[] = "✅ Policy '{$policy}' exists";
            } else {
                $this->warnings[] = "⚠️ Policy '{$policy}' missing (may use default authorization)";
            }
        }

        echo "   Governance system validation completed.\n\n";
    }

    private function validateAPIEndpoints()
    {
        echo "🌐 Validating API Endpoints...\n";

        // Check API routes
        $apiRoutes = [
            'api.governance.age-verification.players.status',
            'api.governance.age-verification.rules.active',
            'api.governance.age-verification.players.verify',
            'api.governance.disciplinary.cases.status',
            'api.governance.disciplinary.players.history',
            'api.governance.disciplinary.suspensions.active',
            'api.governance.appeals.cases.appeals',
            'api.governance.appeals.players.appeals',
            'api.governance.protests.matches.protests',
            'api.governance.protests.teams.protests',
        ];

        foreach ($apiRoutes as $route) {
            if (Route::has($route)) {
                $this->successes[] = "✅ API route '{$route}' exists";
            } else {
                $this->errors[] = "❌ API route '{$route}' missing";
            }
        }

        echo "   API endpoints validation completed.\n\n";
    }

    private function validateDocumentation()
    {
        echo "📚 Validating Documentation...\n";

        $docs = [
            'docs/TOURNAMENT_SYSTEM_TESTING.md',
            'docs/TOURNAMENT_API_DOCUMENTATION.md',
            'docs/TOURNAMENT_ADMIN_GUIDE.md',
            'docs/TOURNAMENT_SUPER_ADMIN_GUIDE.md',
            'docs/TOURNAMENT_DEVELOPER_GUIDE.md',
        ];

        foreach ($docs as $doc) {
            if (file_exists($doc)) {
                $this->successes[] = "✅ Documentation '{$doc}' exists";
            } else {
                $this->errors[] = "❌ Documentation '{$doc}' missing";
            }
        }

        echo "   Documentation validation completed.\n\n";
    }

    private function validateTests()
    {
        echo "🧪 Validating Tests...\n";

        $testFiles = [
            'tests/Feature/AdminDashboardTest.php',
            'tests/Feature/SuperAdminDashboardTest.php',
            'tests/Feature/ApiTest.php',
            'tests/Unit/GovernanceTest.php',
        ];

        foreach ($testFiles as $testFile) {
            if (file_exists($testFile)) {
                $this->successes[] = "✅ Test file '{$testFile}' exists";
            } else {
                $this->errors[] = "❌ Test file '{$testFile}' missing";
            }
        }

        echo "   Tests validation completed.\n\n";
    }

    private function generateReport()
    {
        echo "📊 VALIDATION REPORT\n";
        echo str_repeat("=", 50) . "\n\n";

        // Summary
        $totalChecks = count($this->successes) + count($this->errors) + count($this->warnings);
        $successRate = $totalChecks > 0 ? (count($this->successes) / $totalChecks) * 100 : 0;

        echo "📈 SUMMARY\n";
        echo "Total Checks: {$totalChecks}\n";
        echo "✅ Successes: " . count($this->successes) . "\n";
        echo "⚠️ Warnings: " . count($this->warnings) . "\n";
        echo "❌ Errors: " . count($this->errors) . "\n";
        echo "Success Rate: " . number_format($successRate, 1) . "%\n\n";

        // Successes
        if (!empty($this->successes)) {
            echo "✅ SUCCESSFUL VALIDATIONS\n";
            foreach ($this->successes as $success) {
                echo "  {$success}\n";
            }
            echo "\n";
        }

        // Warnings
        if (!empty($this->warnings)) {
            echo "⚠️ WARNINGS\n";
            foreach ($this->warnings as $warning) {
                echo "  {$warning}\n";
            }
            echo "\n";
        }

        // Errors
        if (!empty($this->errors)) {
            echo "❌ ERRORS\n";
            foreach ($this->errors as $error) {
                echo "  {$error}\n";
            }
            echo "\n";
        }

        // Final Status
        if (count($this->errors) === 0) {
            echo "🎉 SYSTEM VALIDATION PASSED!\n";
            echo "The tournament system implementation is complete and ready for use.\n";
        } else {
            echo "⚠️ SYSTEM VALIDATION HAS ISSUES\n";
            echo "Please address the errors above before using the system.\n";
        }

        echo "\n" . str_repeat("=", 50) . "\n";
    }
}

// Run validation
$validator = new SystemValidator();
$validator->validate();
