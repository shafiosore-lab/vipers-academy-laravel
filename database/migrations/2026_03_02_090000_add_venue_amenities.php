<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds venue facilities, amenities, and media support
     */
    public function up(): void
    {
        // Add enhanced venue fields
        Schema::table('tournament_venues', function (Blueprint $table) {
            // Contact & Management
            $table->string('contact_name')->nullable()->after('name');
            $table->string('contact_phone')->nullable()->after('contact_name');
            $table->string('contact_email')->nullable()->after('contact_phone');

            // Facilities
            $table->json('facilities')->nullable()->after('surface_type'); // Changing rooms, showers, parking, etc.
            $table->json('amenities')->nullable()->after('facilities'); // Floodlights, scoreboard, VAR, etc.

            // Media
            $table->json('photos')->nullable()->after('amenities');
            $table->string('main_photo')->nullable()->after('photos');

            // Dimensions
            $table->integer('length_meters')->nullable()->after('capacity');
            $table->integer('width_meters')->nullable()->after('length_meters');

            // Access & Accessibility
            $table->boolean('has_parking')->default(false)->after('width_meters');
            $table->boolean('has_accessibility')->default(false)->after('has_parking');
            $table->boolean('is_accessible')->default(true)->after('has_accessibility');

            // Availability
            $table->boolean('is_available')->default(true)->after('is_accessible');
            $table->decimal('hourly_rate', 10, 2)->nullable()->after('is_available');
            $table->string('booking_contact')->nullable()->after('hourly_rate');

            // Safety & Compliance
            $table->boolean('has_medical_facility')->default(false)->after('booking_contact');
            $table->boolean('has_security')->default(false)->after('has_medical_facility');
            $table->date('last_inspection_date')->nullable()->after('has_security');
            $table->string('safety_certificate')->nullable()->after('last_inspection_date');

            // Additional info
            $table->text('description')->nullable()->after('safety_certificate');
            $table->json('operating_hours')->nullable()->after('description'); // JSON for day/time slots
            $table->json('restrictions')->nullable()->after('operating_hours'); // No alcohol, no noise, etc.
        });

        // Create venue_availability_calendar table
        Schema::create('venue_availability_calendar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained('tournament_venues')->onDelete('cascade');

            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('is_available')->default(true);
            $table->string('reason')->nullable(); // maintenance, booked, etc.
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['venue_id', 'date']);
            $table->unique(['venue_id', 'date']);
        });

        // Create venue_booking_requests table
        Schema::create('venue_booking_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained('tournament_venues')->onDelete('cascade');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->foreignId('tournament_id')->nullable()->constrained()->nullOnDelete();

            $table->string('request_number')->unique();
            $table->string('event_name');
            $table->date('requested_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('expected_attendance')->nullable();

            $table->string('status'); // pending, approved, rejected, cancelled
            $table->decimal('booking_fee', 10, 2)->default(0);
            $table->text('notes')->nullable();

            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            $table->index(['venue_id', 'status']);
            $table->index(['organization_id', 'status']);
        });

        // Add referee training history table
        Schema::create('referee_training_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referee_id')->constrained('tournament_referees')->onDelete('cascade');

            $table->string('title');
            $table->text('description')->nullable();
            $table->date('session_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->string('trainer_name')->nullable();

            $table->string('training_type'); // fitness, rules_update, practical, assessment
            $table->string('status'); // scheduled, completed, cancelled, no_show

            $table->integer('hours_credited')->default(0);
            $table->decimal('performance_score', 3, 2)->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['referee_id', 'session_date']);
            $table->index(['session_date', 'status']);
        });

        // Add referee performance reviews table
        Schema::create('referee_performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referee_id')->constrained('tournament_referees')->onDelete('cascade');
            $table->foreignId('match_id')->nullable()->constrained('tournament_matches')->nullOnDelete();

            $table->date('review_date');
            $table->string('review_type'); // post_match, periodic, annual

            // Performance metrics
            $table->decimal('overall_rating', 3, 2);
            $table->decimal('decision_accuracy', 3, 2)->nullable();
            $table->decimal('game_management', 3, 2)->nullable();
            $table->decimal('fitness_rating', 3, 2)->nullable();
            $table->decimal('communication', 3, 2)->nullable();

            $table->text('strengths')->nullable();
            $table->text('areas_for_improvement')->nullable();
            $table->text('comments')->nullable();

            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['referee_id', 'review_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop referee performance reviews
        Schema::dropIfExists('referee_performance_reviews');

        // Drop referee training sessions
        Schema::dropIfExists('referee_training_sessions');

        // Drop venue booking requests
        Schema::dropIfExists('venue_booking_requests');

        // Drop venue availability calendar
        Schema::dropIfExists('venue_availability_calendar');

        // Remove enhanced venue fields
        Schema::table('tournament_venues', function (Blueprint $table) {
            $table->dropColumn([
                'contact_name', 'contact_phone', 'contact_email',
                'facilities', 'amenities', 'photos', 'main_photo',
                'length_meters', 'width_meters',
                'has_parking', 'has_accessibility', 'is_accessible',
                'is_available', 'hourly_rate', 'booking_contact',
                'has_medical_facility', 'has_security',
                'last_inspection_date', 'safety_certificate',
                'description', 'operating_hours', 'restrictions',
            ]);
        });
    }
};
