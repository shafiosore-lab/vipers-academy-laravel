@auth
    @if(Auth::user()->is_on_trial && Auth::user()->trial_ends_at)
        @php
            $daysRemaining = Auth::user()->getRemainingTrialDays();
            $isExpiring = $daysRemaining <= 3;
            $isExpired = $daysRemaining <= 0;
        @endphp

        @if($isExpired)
            <div class="alert alert-danger d-flex align-items-center justify-content-between mb-0" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>
                        <strong>Your free trial has expired!</strong>
                        <p class="mb-0 small">Please upgrade your account to continue using the platform.</p>
                    </div>
                </div>
                <a href="#" class="btn btn-sm btn-danger">Upgrade Now</a>
            </div>
        @elseif($isExpiring)
            <div class="alert alert-warning d-flex align-items-center justify-content-between mb-0" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-clock me-2"></i>
                    <div>
                        <strong>Only {{ $daysRemaining }} day{{ $daysRemaining === 1 ? '' : 's' }} left in your free trial!</strong>
                        <p class="mb-0 small">Upgrade now to continue enjoying full access to all features.</p>
                    </div>
                </div>
                <a href="#" class="btn btn-sm btn-warning">Upgrade Now</a>
            </div>
        @else
            <div class="alert alert-info d-flex align-items-center justify-content-between mb-0" role="alert" style="background: linear-gradient(135deg, rgba(255, 215, 0, 0.1) 0%, rgba(255, 183, 0, 0.1) 100%); border-color: rgba(255, 215, 0, 0.3);">
                <div class="d-flex align-items-center">
                    <i class="fas fa-gift me-2" style="color: #b45309;"></i>
                    <div>
                        <strong style="color: #92400e;">{{ $daysRemaining }} days remaining in your free trial</strong>
                        <p class="mb-0 small" style="color: #78350f;">You have full access to all features. No credit card required.</p>
                    </div>
                </div>
                <span class="badge" style="background: #ffd700; color: #0a1628; font-size: 0.8rem;">
                    <i class="fas fa-star me-1"></i> Trial Active
                </span>
            </div>
        @endif
    @endif
@endauth
