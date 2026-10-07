<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gallery;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gallery::create([
            'title' => 'Training Session Photos',
            'media_type' => 'image',
            'media_url' => 'training1.jpg',
        ]);

        Gallery::create([
            'title' => 'Match Highlights',
            'media_type' => 'video',
            'media_url' => 'match_highlights.mp4',
        ]);

        Gallery::create([
            'title' => 'Team Building Activities',
            'media_type' => 'image',
            'media_url' => 'team_building.jpg',
        ]);

        // Additional gallery items
        Gallery::create([
            'title' => 'Youth Championship Final',
            'media_type' => 'image',
            'media_url' => 'championship_final.jpg',
        ]);

        Gallery::create([
            'title' => 'Goalkeeper Training Highlights',
            'media_type' => 'video',
            'media_url' => 'gk_training_highlights.mp4',
        ]);

        Gallery::create([
            'title' => 'Academy Awards Ceremony',
            'media_type' => 'image',
            'media_url' => 'awards_ceremony.jpg',
        ]);

        Gallery::create([
            'title' => 'International Friendly Match',
            'media_type' => 'video',
            'media_url' => 'international_friendly.mp4',
        ]);

        Gallery::create([
            'title' => 'Player Development Sessions',
            'media_type' => 'image',
            'media_url' => 'player_development.jpg',
        ]);

        Gallery::create([
            'title' => 'Community Outreach Program',
            'media_type' => 'image',
            'media_url' => 'community_outreach.jpg',
        ]);

        Gallery::create([
            'title' => 'Summer Camp Activities',
            'media_type' => 'video',
            'media_url' => 'summer_camp_activities.mp4',
        ]);

        Gallery::create([
            'title' => 'Technical Training Drills',
            'media_type' => 'image',
            'media_url' => 'technical_drills.jpg',
        ]);

        Gallery::create([
            'title' => 'Academy Facilities Tour',
            'media_type' => 'video',
            'media_url' => 'facilities_tour.mp4',
        ]);

        Gallery::create([
            'title' => 'Alumni Success Stories',
            'media_type' => 'image',
            'media_url' => 'alumni_success.jpg',
        ]);

        Gallery::create([
            'title' => 'Behind the Scenes: Match Preparation',
            'media_type' => 'video',
            'media_url' => 'match_preparation.mp4',
        ]);

        Gallery::create([
            'title' => 'Youth Leadership Workshop',
            'media_type' => 'image',
            'media_url' => 'leadership_workshop.jpg',
        ]);

        Gallery::create([
            'title' => 'Medical Check-ups and Fitness Testing',
            'media_type' => 'image',
            'media_url' => 'medical_checkups.jpg',
        ]);

        Gallery::create([
            'title' => 'Regional Tournament Highlights',
            'media_type' => 'video',
            'media_url' => 'regional_tournament.mp4',
        ]);

        Gallery::create([
            'title' => 'Environmental Initiatives',
            'media_type' => 'image',
            'media_url' => 'environmental_projects.jpg',
        ]);

        Gallery::create([
            'title' => 'Parent-Teacher Conferences',
            'media_type' => 'image',
            'media_url' => 'parent_conferences.jpg',
        ]);

        Gallery::create([
            'title' => 'Technology in Training',
            'media_type' => 'video',
            'media_url' => 'training_technology.mp4',
        ]);

        Gallery::create([
            'title' => 'Cultural Exchange Programs',
            'media_type' => 'image',
            'media_url' => 'cultural_exchange.jpg',
        ]);

        Gallery::create([
            'title' => 'Victory Celebrations',
            'media_type' => 'video',
            'media_url' => 'victory_celebrations.mp4',
        ]);

        Gallery::create([
            'title' => 'Mentorship Program',
            'media_type' => 'image',
            'media_url' => 'mentorship_program.jpg',
        ]);

        Gallery::create([
            'title' => 'Sports Science Laboratory',
            'media_type' => 'image',
            'media_url' => 'sports_science_lab.jpg',
        ]);
    }
}
