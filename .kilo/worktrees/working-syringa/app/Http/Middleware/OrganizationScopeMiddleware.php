<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OrganizationScopeMiddleware
{
    /**
     * Models that should be automatically scoped to the authenticated user's organization.
     */
    private const SCOPED_MODELS = [
        \App\Models\Tournament::class,
        \App\Models\Team::class,
        \App\Models\Player::class,
        \App\Models\TournamentTeam::class,
        \App\Models\TournamentMatch::class,
        \App\Models\TournamentStanding::class,
        \App\Models\GameStatistic::class,
        \App\Models\PlayerGameStats::class,
        \App\Models\PlayerAvailability::class,
        \App\Models\PlayerContract::class,
        \App\Models\PlayerDocument::class,
        \App\Models\PlayerInjury::class,
        \App\Models\PlayerSuspension::class,
        \App\Models\PlayerTransfer::class,
        \App\Models\AgeAlertRule::class,
        \App\Models\PlayerAgeVerification::class,
        \App\Models\DisciplinaryCase::class,
        \App\Models\Appeal::class,
        \App\Models\Protest::class,
        \App\Models\Attendance::class,
        \App\Models\TrainingSession::class,
        \App\Models\TrainingSessionPlayer::class,
        \App\Models\BudgetPlan::class,
        \App\Models\BudgetItem::class,
        \App\Models\Expense::class,
        \App\Models\ExpenseCategory::class,
        \App\Models\Payment::class,
        \App\Models\PaymentCategory::class,
        \App\Models\Document::class,
        \App\Models\DocumentApproval::class,
        \App\Models\Equipment::class,
        \App\Models\EquipmentCategory::class,
        \App\Models\EquipmentDistribution::class,
        \App\Models\Blog::class,
        \App\Models\Leader::class,
        \App\Models\LeagueStanding::class,
        \App\Models\RefereePerformanceReview::class,
        \App\Models\RefereeTrainingSession::class,
        \App\Models\Registration::class,
        \App\Models\Partner::class,
        \App\Models\PartnerDocument::class,
        \App\Models\Program::class,
        \App\Models\OrganizationDocument::class,
        \App\Models\OrganizationBranding::class,
        \App\Models\OrganizationLetterhead::class,
        \App\Models\Guardian::class,
        \App\Models\Job::class,
        \App\Models\JobApplication::class,
        \App\Models\ExportLog::class,
        \App\Models\ActivityLog::class,
        \App\Models\Approval::class,
        \App\Models\AiInsight::class,
        \App\Models\AiInsightsAnalytic::class,
        \App\Models\AiInsightsDataSource::class,
        \App\Models\AiInsightsJob::class,
        \App\Models\AiInsightsMetric::class,
        \App\Models\CleanSheet::class,
        \App\Models\CompanySettings::class,
        \App\Models\ContractAmendment::class,
        \App\Models\ContractRenewal::class,
        \App\Models\Coupon::class,
        \App\Models\Enrollment::class,
        \App\Models\Event::class,
        \App\Models\FootballMatch::class,
        \App\Models\FootballTerminology::class,
        \App\Models\GoalkeeperRanking::class,
        \App\Models\MessageGateway::class,
        \App\Models\ModulePermission::class,
        \App\Models\MonthlyBilling::class,
        \App\Models\Order::class,
        \App\Models\PageContent::class,
        \App\Models\Permission::class,
        \App\Models\Role::class,
    ];

    /**
     * Apply per-organization query scoping for all non-super-admin users.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && !$user->hasRole('super-admin') && $user->organization_id) {
            $this->applyOrganizationScope($user->organization_id);
        }

        return $next($request);
    }

    /**
     * Register a global Eloquent scope that filters all scoped models
     * to the given organization.
     */
    private function applyOrganizationScope(int $organizationId): void
    {
        foreach (self::SCOPED_MODELS as $model) {
            $model::addGlobalScope('organization', function ($builder) use ($organizationId) {
                $builder->where('organization_id', $organizationId);
            });
        }
    }
}
