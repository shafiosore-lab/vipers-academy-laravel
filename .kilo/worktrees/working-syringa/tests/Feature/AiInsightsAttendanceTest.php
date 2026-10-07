<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Player;
use App\Models\TrainingSession;
use App\Services\AiInsightsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiInsightsAttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendance_metrics_calculation()
    {
        $player = Player::factory()->create();
        $session = TrainingSession::factory()->create();

        // Create attendance records
        Attendance::create([
            'player_id' => $player->id,
            'session_id' => $session->id,
            'session_type' => 'training',
            'session_date' => now()->subDays(10),
            'check_in_time' => now()->subDays(10)->setTime(14, 0),
            'check_out_time' => now()->subDays(10)->setTime(16, 0),
            'total_duration_minutes' => 120,
        ]);

        Attendance::create([
            'player_id' => $player->id,
            'session_id' => $session->id,
            'session_type' => 'training',
            'session_date' => now()->subDays(5),
            'check_in_time' => null, // Missed session
            'total_duration_minutes' => 120,
        ]);

        $service = new AiInsightsService();
        $metrics = $service->getAttendanceMetrics($player->id, 30);

        $this->assertEquals(2, $metrics['total_sessions']);
        $this->assertEquals(1, $metrics['attended_sessions']);
        $this->assertEquals(1, $metrics['missed_sessions']);
        $this->assertEquals(50.0, $metrics['attendance_rate']);
        $this->assertEquals(240, $metrics['total_scheduled_minutes']);
        $this->assertEquals(120, $metrics['total_actual_minutes']);
        $this->assertEquals(120, $metrics['total_missed_minutes']);
        $this->assertEquals(0.5, $metrics['training_exposure_ratio']);
    }

    public function test_zero_minute_sessions_handled_correctly()
    {
        $player = Player::factory()->create();

        // Create attendance with zero duration
        Attendance::create([
            'player_id' => $player->id,
            'session_type' => 'meeting',
            'session_date' => now()->subDays(5),
            'check_in_time' => now()->subDays(5)->setTime(10, 0),
            'check_out_time' => now()->subDays(5)->setTime(10, 0), // Same time
            'total_duration_minutes' => 0,
        ]);

        $service = new AiInsightsService();
        $metrics = $service->getAttendanceMetrics($player->id, 30);

        $this->assertEquals(1, $metrics['total_sessions']);
        $this->assertEquals(1, $metrics['attended_sessions']);
        $this->assertEquals(0, $metrics['total_actual_minutes']);
        $this->assertEquals(0, $metrics['training_exposure_ratio']);
    }

    public function test_late_arrivals_reduce_training_time()
    {
        $player = Player::factory()->create();
        $session = TrainingSession::factory()->create([
            'scheduled_start_time' => now()->subDays(5)->setTime(14, 0),
        ]);

        // Late arrival - session started at 14:00, arrived at 14:30
        Attendance::create([
            'player_id' => $player->id,
            'session_id' => $session->id,
            'session_type' => 'training',
            'session_date' => now()->subDays(5),
            'check_in_time' => now()->subDays(5)->setTime(14, 30),
            'check_out_time' => now()->subDays(5)->setTime(16, 0),
            'total_duration_minutes' => 90, // Only 90 minutes instead of 120
            'missed_minutes' => 30,
            'lateness_category' => 'late',
        ]);

        $service = new AiInsightsService();
        $metrics = $service->getAttendanceMetrics($player->id, 30);

        $this->assertEquals(1, $metrics['total_sessions']);
        $this->assertEquals(1, $metrics['attended_sessions']);
        $this->assertEquals(90, $metrics['total_actual_minutes']);
        $this->assertEquals(90, $metrics['total_scheduled_minutes']);
        $this->assertEquals(0.75, $metrics['training_exposure_ratio']); // 90/120 = 0.75
        $this->assertEquals(100.0, $metrics['lateness_frequency']); // 1 late out of 1 attended
    }

    public function test_absent_players_do_not_accumulate_time()
    {
        $player = Player::factory()->create();

        // Completely absent
        Attendance::create([
            'player_id' => $player->id,
            'session_type' => 'training',
            'session_date' => now()->subDays(5),
            'check_in_time' => null,
            'total_duration_minutes' => 120,
        ]);

        $service = new AiInsightsService();
        $metrics = $service->getAttendanceMetrics($player->id, 30);

        $this->assertEquals(1, $metrics['total_sessions']);
        $this->assertEquals(0, $metrics['attended_sessions']);
        $this->assertEquals(1, $metrics['missed_sessions']);
        $this->assertEquals(0, $metrics['total_actual_minutes']);
        $this->assertEquals(0, $metrics['attendance_rate']);
        $this->assertEquals(0, $metrics['training_exposure_ratio']);
    }

    public function test_aggregations_match_raw_attendance_data()
    {
        $player = Player::factory()->create();

        // Create multiple attendance records
        $records = [
            ['date' => now()->subDays(10), 'attended' => true, 'minutes' => 120],
            ['date' => now()->subDays(8), 'attended' => false, 'minutes' => 120],
            ['date' => now()->subDays(6), 'attended' => true, 'minutes' => 90],
            ['date' => now()->subDays(4), 'attended' => true, 'minutes' => 120],
            ['date' => now()->subDays(2), 'attended' => false, 'minutes' => 120],
        ];

        foreach ($records as $record) {
            Attendance::create([
                'player_id' => $player->id,
                'session_type' => 'training',
                'session_date' => $record['date'],
                'check_in_time' => $record['attended'] ? $record['date']->setTime(14, 0) : null,
                'check_out_time' => $record['attended'] ? $record['date']->setTime(14, 0)->addMinutes($record['minutes']) : null,
                'total_duration_minutes' => $record['minutes'],
            ]);
        }

        $service = new AiInsightsService();
        $metrics = $service->getAttendanceMetrics($player->id, 30);

        // Verify aggregations
        $this->assertEquals(5, $metrics['total_sessions']);
        $this->assertEquals(3, $metrics['attended_sessions']);
        $this->assertEquals(2, $metrics['missed_sessions']);
        $this->assertEquals(60.0, $metrics['attendance_rate']); // 3/5 * 100
        $this->assertEquals(600, $metrics['total_scheduled_minutes']); // 5 * 120
        $this->assertEquals(330, $metrics['total_actual_minutes']); // 120 + 90 + 120
        $this->assertEquals(270, $metrics['total_missed_minutes']); // 240 missed minutes
        $this->assertEquals(0.55, $metrics['training_exposure_ratio']); // 330/600
    }
}
