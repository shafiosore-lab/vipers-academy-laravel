{{-- Player AI Insights Partial --}}
@if(!isset($player) || !$player)
    <div class="alert alert-danger">Error: Player data not available</div>
@else
    <div class="insight-section">
        <h3><i class="fas fa-star"></i> Strengths</h3>
        <p>Exceptional ball control and dribbling ability in tight spaces. Demonstrates strong decision-making under pressure and maintains consistent performance across matches. Shows excellent spatial awareness and positioning.</p>
    </div>

    <div class="insight-section">
        <h3><i class="fas fa-chart-line"></i> Areas for Development</h3>
        <p>Improve long-range passing accuracy and defensive positioning. Build stamina for full 90-minute performances. Enhance aerial dueling and set-piece effectiveness.</p>
    </div>

    <div class="insight-section">
        <h3><i class="fas fa-trophy"></i> Performance Trend</h3>
        <p>Showing steady improvement over the last 6 months. Match ratings have increased by 15%, with particular growth in attacking contributions and creative play in the final third.</p>
    </div>

    <div class="insight-section">
        <h3><i class="fas fa-tactics"></i> Playing Style</h3>
        <p>Dynamic {{ strtolower($player->position) }} who excels in transition play. Prefers to operate in half-spaces and create opportunities through intelligent movement off the ball.</p>
    </div>
@endif
