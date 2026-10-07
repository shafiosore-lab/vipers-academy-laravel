<?php

namespace Database\Seeders;

use App\Models\FootballMatch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FootballMatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Upcoming Friendlies
        $upcomingFriendlies = [
            [
                'type' => 'friendly',
                'opponent' => 'Nairobi United FC',
                'match_date' => Carbon::now()->addDays(15)->setTime(16, 0),
                'venue' => 'Kasarani Stadium',
                'status' => 'upcoming',
                'description' => 'A friendly match against Nairobi United FC to prepare for the upcoming season.',
                'live_link' => 'https://youtube.com/watch?v=live1',
            ],
            [
                'type' => 'friendly',
                'opponent' => 'Western Stima FC',
                'match_date' => Carbon::now()->addDays(22)->setTime(15, 30),
                'venue' => 'Bukhungu Stadium',
                'status' => 'upcoming',
                'description' => 'Home friendly against Western Stima FC.',
                'live_link' => null,
            ],
            [
                'type' => 'friendly',
                'opponent' => 'Kakamega Homeboyz',
                'match_date' => Carbon::now()->addDays(30)->setTime(16, 0),
                'venue' => 'Kakamega Stadium',
                'status' => 'upcoming',
                'description' => 'Away friendly match in Kakamega.',
                'live_link' => 'https://youtube.com/watch?v=live2',
            ],
            [
                'type' => 'friendly',
                'opponent' => 'Mathare United',
                'match_date' => Carbon::now()->addDays(45)->setTime(14, 0),
                'venue' => 'Mombasa County Stadium',
                'status' => 'upcoming',
                'description' => 'Coastal friendly against Mathare United.',
                'live_link' => null,
            ],
        ];

        // Past Matches
        $pastMatches = [
            [
                'type' => 'friendly',
                'opponent' => 'Gor Mahia FC',
                'match_date' => Carbon::now()->subDays(7),
                'venue' => 'Moi International Sports Centre',
                'status' => 'completed',
                'vipers_score' => 2,
                'opponent_score' => 1,
                'match_summary' => 'Vipers Academy secured a convincing 2-1 victory against Gor Mahia FC in a friendly match. Goals from our strikers showcased the team\'s attacking prowess.',
                'highlights_link' => 'https://youtube.com/watch?v=highlights1',
            ],
            [
                'type' => 'friendly',
                'opponent' => 'AFC Leopards',
                'match_date' => Carbon::now()->subDays(14),
                'venue' => 'Nairobi City Stadium',
                'status' => 'completed',
                'vipers_score' => 1,
                'opponent_score' => 1,
                'match_summary' => 'An exciting 1-1 draw against AFC Leopards. Both teams displayed excellent football with Vipers showing great defensive organization.',
                'highlights_link' => 'https://youtube.com/watch?v=highlights2',
            ],
            [
                'type' => 'friendly',
                'opponent' => 'Tusker FC',
                'match_date' => Carbon::now()->subDays(21),
                'venue' => 'Ruaraka Stadium',
                'status' => 'completed',
                'vipers_score' => 3,
                'opponent_score' => 0,
                'match_summary' => 'Dominant performance by Vipers Academy with a 3-0 victory against Tusker FC. Clean sheet and multiple goals highlight.',
                'highlights_link' => 'https://youtube.com/watch?v=highlights3',
            ],
            [
                'type' => 'league',
                'opponent' => 'KCB FC',
                'match_date' => Carbon::now()->subDays(28),
                'venue' => 'Kenyatta International Conference Centre',
                'status' => 'completed',
                'vipers_score' => 0,
                'opponent_score' => 2,
                'match_summary' => 'Challenging match against KCB FC. Despite the defeat, valuable lessons learned for future improvements.',
                'highlights_link' => null,
            ],
            [
                'type' => 'friendly',
                'opponent' => 'Ulinzi Stars',
                'match_date' => Carbon::now()->subDays(35),
                'venue' => 'Thika Stadium',
                'status' => 'completed',
                'vipers_score' => 2,
                'opponent_score' => 2,
                'match_summary' => 'Thrilling 2-2 draw against Ulinzi Stars. Great comeback from 2-0 down shows team spirit and resilience.',
                'highlights_link' => 'https://youtube.com/watch?v=highlights4',
            ],
            [
                'type' => 'cup',
                'opponent' => 'Sofapaka FC',
                'match_date' => Carbon::now()->subDays(42),
                'venue' => 'Nairobi West Stadium',
                'status' => 'completed',
                'vipers_score' => 1,
                'opponent_score' => 0,
                'match_summary' => 'Cup victory against Sofapaka FC. Clean sheet and crucial goal secured the win.',
                'highlights_link' => null,
            ],
        ];

        // Planned Tournaments
        $plannedTournaments = [
            [
                'type' => 'tournament',
                'opponent' => 'Multiple Teams',
                'match_date' => Carbon::now()->addDays(60),
                'venue' => 'Kasarani Stadium',
                'status' => 'planned',
                'tournament_name' => 'East Africa Youth Championship 2026',
                'description' => 'Regional youth championship featuring teams from Kenya, Uganda, Tanzania, and Rwanda. Vipers Academy will compete in the U-17 category.',
                'registration_open' => true,
                'registration_deadline' => Carbon::now()->addDays(45),
            ],
            [
                'type' => 'tournament',
                'opponent' => 'Multiple Teams',
                'match_date' => Carbon::now()->addDays(90),
                'venue' => 'Moi International Sports Centre',
                'status' => 'planned',
                'tournament_name' => 'Nairobi International Football Festival',
                'description' => 'Annual international youth football festival bringing together academies from across Africa and Europe.',
                'registration_open' => false,
                'registration_deadline' => Carbon::now()->addDays(75),
            ],
            [
                'type' => 'tournament',
                'opponent' => 'Multiple Teams',
                'match_date' => Carbon::now()->addDays(120),
                'venue' => 'Bukhungu Stadium',
                'status' => 'planned',
                'tournament_name' => 'Western Kenya Academy Cup',
                'description' => 'Regional competition for youth academies in Western Kenya. Focus on grassroots development and talent identification.',
                'registration_open' => true,
                'registration_deadline' => Carbon::now()->addDays(100),
            ],
            [
                'type' => 'tournament',
                'opponent' => 'Multiple Teams',
                'match_date' => Carbon::now()->addDays(150),
                'venue' => 'Mombasa County Stadium',
                'status' => 'planned',
                'tournament_name' => 'Coastal Youth Tournament',
                'description' => 'Beach football tournament combining traditional football with beach soccer elements.',
                'registration_open' => false,
                'registration_deadline' => Carbon::now()->addDays(130),
            ],
        ];

        // Insert all match data
        foreach (array_merge($upcomingFriendlies, $pastMatches, $plannedTournaments) as $matchData) {
            FootballMatch::create($matchData);
        }
    }
}
