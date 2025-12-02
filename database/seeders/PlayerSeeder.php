<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Player;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample player images for demonstration (using Lorem Picsum for consistent demo images)
        $playerImages = [
            'https://picsum.photos/200/300?random=1',  // Kofi Mensah
            'https://picsum.photos/200/300?random=2',  // Nomsa Zulu
            'https://picsum.photos/200/300?random=3',  // Amina Okafor
            'https://picsum.photos/200/300?random=4',  // Simba Moyo
            'https://picsum.photos/200/300?random=5',  // Fatima Hassan
            'https://picsum.photos/200/300?random=6',  // Jelani Nkosi
            'https://picsum.photos/200/300?random=7',  // Tendai Chitiyo
            'https://picsum.photos/200/300?random=8',  // Grace Wanjiku
            'https://picsum.photos/200/300?random=9',  // Zara Abdi
            'https://picsum.photos/200/300?random=10', // Jomo Kenyatta
            'https://picsum.photos/200/300?random=11', // Samuel Kiprop
            'https://picsum.photos/200/300?random=12', // Mercy Chebet
            'https://picsum.photos/200/300?random=13', // David Ochieng
            'https://picsum.photos/200/300?random=14', // Ann Wairimu
            'https://picsum.photos/200/300?random=15', // Peter Mwangi
            'https://picsum.photos/200/300?random=16', // Sarah Kiprop
        ];

        // Forwards
        Player::create([
            'name' => 'Kofi Mensah',
            'first_name' => 'Kofi',
            'last_name' => 'Mensah',
            'age' => 18,
            'position' => 'forward',
            'nationality' => 'Ghanaian',
            'height_cm' => 178,
            'weight_kg' => 72,
            'dominant_foot' => 'Right',
            'goals_scored' => 25,
            'assists' => 12,
            'performance_rating' => 8.5,
            'matches_played' => 28,
            'bio' => 'Kofi Mensah is a dynamic forward from Accra, Ghana, known for his incredible speed and finishing ability. Born and raised in a football-loving community, Kofi started playing at age 6 and quickly showed exceptional talent. He joined Vipers Academy at 14 and has been a key player ever since, scoring crucial goals in important matches. His technical skills, combined with his work ethic, make him a complete forward who can both create and finish chances.',
            'achievements' => '• Top Scorer U-18 Championship 2024 (18 goals)\n• Vipers Academy Player of the Year 2024\n• Ghana U-17 National Team Call-up\n• Regional Champions Cup Winner 2023\n• Most Valuable Player Award 2024',
            'photo' => $playerImages[0],
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
            'documents_completed' => true,
        ]);

        Player::create([
            'name' => 'Nomsa Zulu',
            'first_name' => 'Nomsa',
            'last_name' => 'Zulu',
            'age' => 16,
            'position' => 'forward',
            'nationality' => 'South African',
            'height_cm' => 168,
            'weight_kg' => 60,
            'dominant_foot' => 'Right',
            'goals_scored' => 22,
            'assists' => 9,
            'performance_rating' => 8.9,
            'matches_played' => 29,
            'bio' => 'Nomsa Zulu is a prolific striker from Durban, South Africa, famous for her clinical finishing and movement off the ball. Overcoming challenges in her early life, Nomsa found purpose and direction through football. Her dedication and talent earned her a place at Vipers Academy, where she has blossomed into one of the most dangerous forwards in youth football. Her story inspires many young girls to pursue their dreams in sports.',
            'achievements' => '• Top Female Scorer 2024\n• South African U-17 Women\'s Team Captain\n• COSAFA Women\'s Championship Top Scorer\n• Youth Empowerment Award\n• Academic Scholarship Recipient\n• Inspirational Speaker at Youth Conferences',
            'photo' => $playerImages[1],
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
            'documents_completed' => true,
        ]);

        // Midfielders
        Player::create([
            'name' => 'Amina Okafor',
            'first_name' => 'Amina',
            'last_name' => 'Okafor',
            'age' => 17,
            'position' => 'midfielder',
            'nationality' => 'Nigerian',
            'height_cm' => 165,
            'weight_kg' => 58,
            'dominant_foot' => 'Left',
            'goals_scored' => 8,
            'assists' => 22,
            'performance_rating' => 9.0,
            'matches_played' => 32,
            'bio' => 'Amina Okafor is a creative midfielder from Lagos, Nigeria, renowned for her exceptional vision and passing range. Coming from a family of educators, Amina brings intelligence and tactical awareness to her play. She joined Vipers Academy through a talent identification program and has become the heartbeat of the midfield, dictating play with her precise passes and ability to unlock defenses. Her leadership qualities have made her captain of both the academy and regional teams.',
            'achievements' => '• Midfielder of the Year 2024\n• Captain of Vipers Academy First Team\n• Nigerian U-17 Women\'s Team Captain\n• CAF Women\'s Championship Participant\n• Academic Excellence Award (Mathematics)\n• Community Service Recognition',
            'photo' => $playerImages[2],
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
            'documents_completed' => true,
        ]);

        Player::create([
            'name' => 'Simba Moyo',
            'first_name' => 'Simba',
            'last_name' => 'Moyo',
            'age' => 17,
            'position' => 'midfielder',
            'nationality' => 'Zimbabwean',
            'height_cm' => 175,
            'weight_kg' => 68,
            'dominant_foot' => 'Left',
            'goals_scored' => 12,
            'assists' => 18,
            'performance_rating' => 8.7,
            'matches_played' => 31,
            'bio' => 'Simba Moyo is a versatile attacking midfielder from Harare, Zimbabwe, known for his dribbling skills and creativity. Coming from a musical family, Simba brings rhythm and flair to his football. His technical ability and vision make him a playmaker who can unlock the most stubborn defenses. At Vipers Academy, he has developed into a complete midfielder, contributing both defensively and offensively while maintaining excellent academic performance.',
            'achievements' => '• Most Assists Award 2024\n• Zimbabwe U-17 National Team\n• COSAFA U-17 Championship Winner\n• Academic Excellence (Science)\n• Youth Leadership Program Graduate\n• Community Football Coach Certification',
            'photo' => $playerImages[3],
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
            'documents_completed' => true,
        ]);

        Player::create([
            'name' => 'Fatima Hassan',
            'first_name' => 'Fatima',
            'last_name' => 'Hassan',
            'age' => 15,
            'position' => 'midfielder',
            'nationality' => 'Tanzanian',
            'height_cm' => 162,
            'weight_kg' => 55,
            'dominant_foot' => 'Right',
            'goals_scored' => 6,
            'assists' => 15,
            'performance_rating' => 8.4,
            'matches_played' => 27,
            'bio' => 'Fatima Hassan is a box-to-box midfielder from Dar es Salaam, Tanzania, known for her endless energy and tactical intelligence. Despite her small stature, Fatima\'s determination and football IQ make her a dominant presence in midfield. She joined Vipers Academy through a partnership program and has become known for her ability to cover every blade of grass and provide defensive cover while supporting attacks. Her leadership extends beyond the pitch into community initiatives.',
            'achievements' => '• Most Improved Player 2024\n• Tanzanian U-17 Women\'s Team\n• CECAFA Women\'s Championship Participant\n• Environmental Leadership Award\n• Academic Excellence (Geography)\n• Youth Mentor Program Participant',
            'photo' => null,
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
            'documents_completed' => true,
        ]);

        // Defenders
        Player::create([
            'name' => 'Jelani Nkosi',
            'first_name' => 'Jelani',
            'last_name' => 'Nkosi',
            'age' => 19,
            'position' => 'defender',
            'nationality' => 'South African',
            'height_cm' => 185,
            'weight_kg' => 78,
            'dominant_foot' => 'Right',
            'goals_scored' => 3,
            'assists' => 5,
            'performance_rating' => 8.8,
            'matches_played' => 35,
            'bio' => 'Jelani Nkosi is a commanding center-back from Johannesburg, South Africa, known for his aerial ability and tactical intelligence. Growing up in Soweto, Jelani developed his physical presence and reading of the game through street football. His journey to Vipers Academy came after impressing scouts at a regional tournament. Now a key figure in the defensive setup, Jelani combines physical strength with excellent positioning and leadership, making him a role model for younger defenders.',
            'achievements' => '• Defender of the Year 2024\n• Clean Sheet Record Holder\n• South African U-20 National Team\n• CAF Champions League Experience\n• Academic Scholarship Recipient\n• Community Leadership Award',
            'photo' => $playerImages[5],
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
            'documents_completed' => true,
        ]);

        Player::create([
            'name' => 'Tendai Chitiyo',
            'first_name' => 'Tendai',
            'last_name' => 'Chitiyo',
            'age' => 18,
            'position' => 'defender',
            'nationality' => 'Zimbabwean',
            'height_cm' => 182,
            'weight_kg' => 75,
            'dominant_foot' => 'Left',
            'goals_scored' => 4,
            'assists' => 6,
            'performance_rating' => 8.6,
            'matches_played' => 33,
            'bio' => 'Tendai Chitiyo is a modern full-back from Bulawayo, Zimbabwe, combining defensive solidity with attacking flair. His ability to deliver accurate crosses from wide positions makes him invaluable in both defense and attack. At Vipers Academy, Tendai has developed his tactical understanding and physical attributes, becoming a complete left-back who can contribute in multiple ways. His versatility and work rate make him a fan favorite.',
            'achievements' => '• Full-Back of the Year 2024\n• Most Crosses Completed Award\n• Zimbabwe U-20 National Team\n• COSAFA Cup Participant\n• Engineering Scholarship\n• Team Spirit Award',
            'photo' => null,
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
            'documents_completed' => true,
        ]);

        Player::create([
            'name' => 'Grace Wanjiku',
            'first_name' => 'Grace',
            'last_name' => 'Wanjiku',
            'age' => 16,
            'position' => 'defender',
            'nationality' => 'Kenyan',
            'height_cm' => 170,
            'weight_kg' => 62,
            'dominant_foot' => 'Right',
            'goals_scored' => 2,
            'assists' => 4,
            'performance_rating' => 8.3,
            'matches_played' => 30,
            'bio' => 'Grace Wanjiku is a composed center-back from Nakuru, Kenya, known for her reading of the game and composure under pressure. Coming from a farming community, Grace brings discipline and work ethic to her football. Her ability to organize the defense and communicate effectively makes her a natural leader. At Vipers Academy, she has developed into a modern defender who combines traditional defensive values with contemporary tactical understanding.',
            'achievements' => '• Defensive Player of the Year 2024\n• Kenyan U-17 Women\'s Team\n• CECAFA Women\'s Championship Participant\n• Agricultural Science Excellence\n• Team Leadership Award\n• Community Health Initiative Volunteer',
            'photo' => null,
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
            'documents_completed' => true,
        ]);

        // Goalkeepers
        Player::create([
            'name' => 'Zara Abdi',
            'first_name' => 'Zara',
            'last_name' => 'Abdi',
            'age' => 16,
            'position' => 'goalkeeper',
            'nationality' => 'Kenyan',
            'height_cm' => 172,
            'weight_kg' => 65,
            'dominant_foot' => 'Right',
            'goals_scored' => 0,
            'assists' => 2,
            'performance_rating' => 9.2,
            'matches_played' => 30,
            'bio' => 'Zara Abdi is an outstanding goalkeeper from Nairobi, Kenya, celebrated for her reflexes and shot-stopping ability. Starting as a outfield player, Zara discovered her talent between the posts during a school tournament. Her journey to Vipers Academy was marked by dedication and continuous improvement. Now one of the most reliable keepers in African youth football, Zara combines technical excellence with mental toughness, making crucial saves in important matches and inspiring young female players across the continent.',
            'achievements' => '• Goalkeeper of the Year 2024\n• Most Clean Sheets Award\n• Kenyan U-17 Women\'s Team Goalkeeper\n• CAF Women\'s Championship Bronze Medal\n• STEM Excellence Award\n• Young Female Leader Recognition',
            'photo' => $playerImages[8],
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
            'documents_completed' => true,
        ]);

        Player::create([
            'name' => 'Jomo Kenyatta',
            'first_name' => 'Jomo',
            'last_name' => 'Kenyatta',
            'age' => 17,
            'position' => 'goalkeeper',
            'nationality' => 'Kenyan',
            'height_cm' => 188,
            'weight_kg' => 82,
            'dominant_foot' => 'Right',
            'goals_scored' => 0,
            'assists' => 1,
            'performance_rating' => 8.8,
            'matches_played' => 34,
            'bio' => 'Jomo Kenyatta is a commanding goalkeeper from Kisumu, Kenya, known for his imposing presence and shot-stopping prowess. Standing tall and confident, Jomo commands his area with authority and excellent distribution. His journey to Vipers Academy came after starring in regional tournaments, where his penalty saves became legendary. Now a key figure in the academy\'s goalkeeping development, Jomo combines physical attributes with technical excellence and mental toughness.',
            'achievements' => '• Goalkeeper of the Tournament 2024\n• Kenyan U-20 National Team\n• CECAFA Cup Participant\n• Physics Academic Excellence\n• Leadership in Sports Award\n• Community Goalkeeper Coaching Program',
            'photo' => null,
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
            'documents_completed' => true,
        ]);

        Player::create([
            'name' => 'Amina Okafor',
            'first_name' => 'Amina',
            'last_name' => 'Okafor',
            'age' => 17,
            'position' => 'midfielder',
            'nationality' => 'Nigerian',
            'height_cm' => 165,
            'weight_kg' => 58,
            'dominant_foot' => 'Left',
            'goals_scored' => 8,
            'assists' => 22,
            'performance_rating' => 9.0,
            'matches_played' => 32,
            'bio' => 'Amina Okafor is a creative midfielder from Lagos, Nigeria, renowned for her exceptional vision and passing range. Coming from a family of educators, Amina brings intelligence and tactical awareness to her play. She joined Vipers Academy through a talent identification program and has become the heartbeat of the midfield, dictating play with her precise passes and ability to unlock defenses. Her leadership qualities have made her captain of both the academy and regional teams.',
            'achievements' => '• Midfielder of the Year 2024\n• Captain of Vipers Academy First Team\n• Nigerian U-17 Women\'s Team Captain\n• CAF Women\'s Championship Participant\n• Academic Excellence Award (Mathematics)\n• Community Service Recognition',
            'milestones' => json_encode([
                ['year' => 2019, 'title' => 'Talent Discovery', 'description' => 'Identified through national scouting program'],
                ['year' => 2021, 'title' => 'Captaincy', 'description' => 'Appointed team captain at age 15'],
                ['year' => 2023, 'title' => 'International Debut', 'description' => 'First cap for Nigerian national team'],
                ['year' => 2024, 'title' => 'Leadership Award', 'description' => 'Recognized for exceptional leadership skills']
            ]),
            'photo' => null,
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
        ]);

        Player::create([
            'name' => 'Jelani Nkosi',
            'first_name' => 'Jelani',
            'last_name' => 'Nkosi',
            'age' => 19,
            'position' => 'defender',
            'nationality' => 'South African',
            'height_cm' => 185,
            'weight_kg' => 78,
            'dominant_foot' => 'Right',
            'goals_scored' => 3,
            'assists' => 5,
            'performance_rating' => 8.8,
            'matches_played' => 35,
            'bio' => 'Jelani Nkosi is a commanding center-back from Johannesburg, South Africa, known for his aerial ability and tactical intelligence. Growing up in Soweto, Jelani developed his physical presence and reading of the game through street football. His journey to Vipers Academy came after impressing scouts at a regional tournament. Now a key figure in the defensive setup, Jelani combines physical strength with excellent positioning and leadership, making him a role model for younger defenders.',
            'achievements' => '• Defender of the Year 2024\n• Clean Sheet Record Holder\n• South African U-20 National Team\n• CAF Champions League Experience\n• Academic Scholarship Recipient\n• Community Leadership Award',
            'milestones' => json_encode([
                ['year' => 2018, 'title' => 'Regional Champion', 'description' => 'Won Gauteng provincial championship'],
                ['year' => 2020, 'title' => 'Academy Scholarship', 'description' => 'Awarded full scholarship to Vipers Academy'],
                ['year' => 2022, 'title' => 'National Call-up', 'description' => 'Selected for South African U-20 team'],
                ['year' => 2024, 'title' => 'Professional Debut', 'description' => 'Made first team debut in senior competition']
            ]),
            'photo' => null,
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
        ]);

        Player::create([
            'name' => 'Zara Abdi',
            'first_name' => 'Zara',
            'last_name' => 'Abdi',
            'age' => 16,
            'position' => 'goalkeeper',
            'nationality' => 'Kenyan',
            'height_cm' => 172,
            'weight_kg' => 65,
            'dominant_foot' => 'Right',
            'goals_scored' => 0,
            'assists' => 2,
            'performance_rating' => 9.2,
            'matches_played' => 30,
            'bio' => 'Zara Abdi is an outstanding goalkeeper from Nairobi, Kenya, celebrated for her reflexes and shot-stopping ability. Starting as a outfield player, Zara discovered her talent between the posts during a school tournament. Her journey to Vipers Academy was marked by dedication and continuous improvement. Now one of the most reliable keepers in African youth football, Zara combines technical excellence with mental toughness, making crucial saves in important matches and inspiring young female players across the continent.',
            'achievements' => '• Goalkeeper of the Year 2024\n• Most Clean Sheets Award\n• Kenyan U-17 Women\'s Team Goalkeeper\n• CAF Women\'s Championship Bronze Medal\n• STEM Excellence Award\n• Young Female Leader Recognition',
            'milestones' => json_encode([
                ['year' => 2019, 'title' => 'Position Change', 'description' => 'Switched from forward to goalkeeper'],
                ['year' => 2021, 'title' => 'National Recognition', 'description' => 'Selected for Kenyan national youth team'],
                ['year' => 2023, 'title' => 'Continental Success', 'description' => 'Bronze medal at CAF Women\'s Championship'],
                ['year' => 2024, 'title' => 'Record Breaker', 'description' => 'Set new clean sheet record for academy']
            ]),
            'photo' => null,
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
        ]);

        Player::create([
            'name' => 'Simba Moyo',
            'first_name' => 'Simba',
            'last_name' => 'Moyo',
            'age' => 17,
            'position' => 'midfielder',
            'nationality' => 'Zimbabwean',
            'height_cm' => 175,
            'weight_kg' => 68,
            'dominant_foot' => 'Left',
            'goals_scored' => 12,
            'assists' => 18,
            'performance_rating' => 8.7,
            'matches_played' => 31,
            'bio' => 'Simba Moyo is a versatile attacking midfielder from Harare, Zimbabwe, known for his dribbling skills and creativity. Coming from a musical family, Simba brings rhythm and flair to his football. His technical ability and vision make him a playmaker who can unlock the most stubborn defenses. At Vipers Academy, he has developed into a complete midfielder, contributing both defensively and offensively while maintaining excellent academic performance.',
            'achievements' => '• Most Assists Award 2024\n• Zimbabwe U-17 National Team\n• COSAFA U-17 Championship Winner\n• Academic Excellence (Science)\n• Youth Leadership Program Graduate\n• Community Football Coach Certification',
            'milestones' => json_encode([
                ['year' => 2019, 'title' => 'Local Hero', 'description' => 'Won Harare youth championship'],
                ['year' => 2021, 'title' => 'International Recognition', 'description' => 'Selected for Zimbabwe national youth team'],
                ['year' => 2023, 'title' => 'Continental Champion', 'description' => 'Won COSAFA U-17 Championship'],
                ['year' => 2024, 'title' => 'Complete Player', 'description' => 'Balanced athletic, academic, and leadership development']
            ]),
            'photo' => null,
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
        ]);

        Player::create([
            'name' => 'Nomsa Zulu',
            'first_name' => 'Nomsa',
            'last_name' => 'Zulu',
            'age' => 16,
            'position' => 'forward',
            'nationality' => 'South African',
            'height_cm' => 168,
            'weight_kg' => 60,
            'dominant_foot' => 'Right',
            'goals_scored' => 22,
            'assists' => 9,
            'performance_rating' => 8.9,
            'matches_played' => 29,
            'bio' => 'Nomsa Zulu is a prolific striker from Durban, South Africa, famous for her clinical finishing and movement off the ball. Overcoming challenges in her early life, Nomsa found purpose and direction through football. Her dedication and talent earned her a place at Vipers Academy, where she has blossomed into one of the most dangerous forwards in youth football. Her story inspires many young girls to pursue their dreams in sports.',
            'achievements' => '• Top Female Scorer 2024\n• South African U-17 Women\'s Team Captain\n• COSAFA Women\'s Championship Top Scorer\n• Youth Empowerment Award\n• Academic Scholarship Recipient\n• Inspirational Speaker at Youth Conferences',
            'milestones' => json_encode([
                ['year' => 2018, 'title' => 'Community Champion', 'description' => 'Led local girls\' team to victory'],
                ['year' => 2020, 'title' => 'Academy Journey Begins', 'description' => 'Joined Vipers Academy development program'],
                ['year' => 2022, 'title' => 'National Captain', 'description' => 'Appointed captain of South African U-17 women\'s team'],
                ['year' => 2024, 'title' => 'Role Model', 'description' => 'Featured in national media as inspirational figure']
            ]),
            'photo' => null,
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
        ]);

        Player::create([
            'name' => 'Tendai Chitiyo',
            'first_name' => 'Tendai',
            'last_name' => 'Chitiyo',
            'age' => 18,
            'position' => 'defender',
            'nationality' => 'Zimbabwean',
            'height_cm' => 182,
            'weight_kg' => 75,
            'dominant_foot' => 'Left',
            'goals_scored' => 4,
            'assists' => 6,
            'performance_rating' => 8.6,
            'matches_played' => 33,
            'bio' => 'Tendai Chitiyo is a modern full-back from Bulawayo, Zimbabwe, combining defensive solidity with attacking flair. His ability to deliver accurate crosses from wide positions makes him invaluable in both defense and attack. At Vipers Academy, Tendai has developed his tactical understanding and physical attributes, becoming a complete left-back who can contribute in multiple ways. His versatility and work rate make him a fan favorite.',
            'achievements' => '• Full-Back of the Year 2024\n• Most Crosses Completed Award\n• Zimbabwe U-20 National Team\n• COSAFA Cup Participant\n• Engineering Scholarship\n• Team Spirit Award',
            'milestones' => json_encode([
                ['year' => 2019, 'title' => 'Position Mastery', 'description' => 'Developed as complete full-back'],
                ['year' => 2021, 'title' => 'International Recognition', 'description' => 'Called up to Zimbabwe U-20 team'],
                ['year' => 2023, 'title' => 'Technical Excellence', 'description' => 'Awarded for crossing accuracy'],
                ['year' => 2024, 'title' => 'Leadership Role', 'description' => 'Became vice-captain of first team']
            ]),
            'photo' => null,
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
        ]);

        Player::create([
            'name' => 'Fatima Hassan',
            'first_name' => 'Fatima',
            'last_name' => 'Hassan',
            'age' => 15,
            'position' => 'midfielder',
            'nationality' => 'Tanzanian',
            'height_cm' => 162,
            'weight_kg' => 55,
            'dominant_foot' => 'Right',
            'goals_scored' => 6,
            'assists' => 15,
            'performance_rating' => 8.4,
            'matches_played' => 27,
            'bio' => 'Fatima Hassan is a box-to-box midfielder from Dar es Salaam, Tanzania, known for her endless energy and tactical intelligence. Despite her small stature, Fatima\'s determination and football IQ make her a dominant presence in midfield. She joined Vipers Academy through a partnership program and has become known for her ability to cover every blade of grass and provide defensive cover while supporting attacks. Her leadership extends beyond the pitch into community initiatives.',
            'achievements' => '• Most Improved Player 2024\n• Tanzanian U-17 Women\'s Team\n• CECAFA Women\'s Championship Participant\n• Environmental Leadership Award\n• Academic Excellence (Geography)\n• Youth Mentor Program Participant',
            'milestones' => json_encode([
                ['year' => 2019, 'title' => 'Foundation Building', 'description' => 'Developed basic football fundamentals'],
                ['year' => 2021, 'title' => 'National Recognition', 'description' => 'Selected for Tanzanian national youth team'],
                ['year' => 2023, 'title' => 'Leadership Emergence', 'description' => 'Took on mentoring role for younger players'],
                ['year' => 2024, 'title' => 'Complete Development', 'description' => 'Balanced athletic, academic, and personal growth']
            ]),
            'photo' => null,
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
        ]);

        Player::create([
            'name' => 'Jomo Kenyatta',
            'first_name' => 'Jomo',
            'last_name' => 'Kenyatta',
            'age' => 17,
            'position' => 'goalkeeper',
            'nationality' => 'Kenyan',
            'height_cm' => 188,
            'weight_kg' => 82,
            'dominant_foot' => 'Right',
            'goals_scored' => 0,
            'assists' => 1,
            'performance_rating' => 8.8,
            'matches_played' => 34,
            'bio' => 'Jomo Kenyatta is a commanding goalkeeper from Kisumu, Kenya, known for his imposing presence and shot-stopping prowess. Standing tall and confident, Jomo commands his area with authority and excellent distribution. His journey to Vipers Academy came after starring in regional tournaments, where his penalty saves became legendary. Now a key figure in the academy\'s goalkeeping development, Jomo combines physical attributes with technical excellence and mental toughness.',
            'achievements' => '• Goalkeeper of the Tournament 2024\n• Kenyan U-20 National Team\n• CECAFA Cup Participant\n• Physics Academic Excellence\n• Leadership in Sports Award\n• Community Goalkeeper Coaching Program',
            'milestones' => json_encode([
                ['year' => 2018, 'title' => 'Local Legend', 'description' => 'Became known for penalty saves in local leagues'],
                ['year' => 2020, 'title' => 'Academy Opportunity', 'description' => 'Joined Vipers Academy goalkeeper program'],
                ['year' => 2022, 'title' => 'National Call-up', 'description' => 'Selected for Kenyan U-20 national team'],
                ['year' => 2024, 'title' => 'Mentorship Role', 'description' => 'Started coaching young goalkeepers in community']
            ]),
            'photo' => null,
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
        ]);

        Player::create([
            'name' => 'Grace Wanjiku',
            'first_name' => 'Grace',
            'last_name' => 'Wanjiku',
            'age' => 16,
            'position' => 'defender',
            'nationality' => 'Kenyan',
            'height_cm' => 170,
            'weight_kg' => 62,
            'dominant_foot' => 'Right',
            'goals_scored' => 2,
            'assists' => 4,
            'performance_rating' => 8.3,
            'matches_played' => 30,
            'bio' => 'Grace Wanjiku is a composed center-back from Nakuru, Kenya, known for her reading of the game and composure under pressure. Coming from a farming community, Grace brings discipline and work ethic to her football. Her ability to organize the defense and communicate effectively makes her a natural leader. At Vipers Academy, she has developed into a modern defender who combines traditional defensive values with contemporary tactical understanding.',
            'achievements' => '• Defensive Player of the Year 2024\n• Kenyan U-17 Women\'s Team\n• CECAFA Women\'s Championship Participant\n• Agricultural Science Excellence\n• Team Leadership Award\n• Community Health Initiative Volunteer',
            'milestones' => json_encode([
                ['year' => 2019, 'title' => 'Defensive Foundation', 'description' => 'Built strong defensive fundamentals'],
                ['year' => 2021, 'title' => 'Leadership Recognition', 'description' => 'Appointed defensive captain'],
                ['year' => 2023, 'title' => 'National Representation', 'description' => 'Selected for Kenyan national youth team'],
                ['year' => 2024, 'title' => 'Holistic Development', 'description' => 'Balanced sports, academics, and community service']
            ]),
            'photo' => null,
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
        ]);

        // Additional players to reach 23 total
        Player::create([
            'name' => 'Samuel Kiprop',
            'first_name' => 'Samuel',
            'last_name' => 'Kiprop',
            'age' => 17,
            'position' => 'midfielder',
            'nationality' => 'Kenyan',
            'height_cm' => 173,
            'weight_kg' => 65,
            'dominant_foot' => 'Right',
            'goals_scored' => 7,
            'assists' => 14,
            'performance_rating' => 8.1,
            'matches_played' => 28,
            'bio' => 'Samuel Kiprop is a hardworking central midfielder from Eldoret, Kenya, known for his tireless running and tactical discipline. His ability to break up opposition attacks and distribute the ball accurately makes him invaluable in the midfield engine room.',
            'achievements' => '• Most Tackles Award 2024\n• Team Player of the Year\n• Academic Excellence in Mathematics',
            'photo' => $playerImages[10],
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
        ]);

        Player::create([
            'name' => 'Mercy Chebet',
            'first_name' => 'Mercy',
            'last_name' => 'Chebet',
            'age' => 15,
            'position' => 'forward',
            'nationality' => 'Kenyan',
            'height_cm' => 165,
            'weight_kg' => 58,
            'dominant_foot' => 'Left',
            'goals_scored' => 18,
            'assists' => 6,
            'performance_rating' => 8.2,
            'matches_played' => 25,
            'bio' => 'Mercy Chebet is an emerging talent from Kitale, showing great potential as a left-footed forward. Her technical ability and finishing skills have already caught the attention of regional scouts.',
            'achievements' => '• Rising Star Award 2024\n• U-17 Regional Team\n• Most Improved Player',
            'photo' => null,
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
        ]);

        Player::create([
            'name' => 'David Ochieng',
            'first_name' => 'David',
            'last_name' => 'Ochieng',
            'age' => 18,
            'position' => 'defender',
            'nationality' => 'Kenyan',
            'height_cm' => 180,
            'weight_kg' => 74,
            'dominant_foot' => 'Right',
            'goals_scored' => 1,
            'assists' => 3,
            'performance_rating' => 7.9,
            'matches_played' => 32,
            'bio' => 'David Ochieng is a solid right-back from Kisumu, providing stability on the defensive flank. His crossing ability from wide positions adds attacking threat to his defensive duties.',
            'achievements' => '• Defensive Consistency Award\n• Team Captain 2024\n• Academic Scholarship',
            'photo' => null,
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
        ]);

        Player::create([
            'name' => 'Ann Wairimu',
            'first_name' => 'Ann',
            'last_name' => 'Wairimu',
            'age' => 16,
            'position' => 'goalkeeper',
            'nationality' => 'Kenyan',
            'height_cm' => 168,
            'weight_kg' => 60,
            'dominant_foot' => 'Right',
            'goals_scored' => 0,
            'assists' => 0,
            'performance_rating' => 8.0,
            'matches_played' => 22,
            'bio' => 'Ann Wairimu is a promising young goalkeeper from Nairobi, showing excellent reflexes and shot-stopping ability. Her distribution and command of the penalty area make her a reliable last line of defense.',
            'achievements' => '• Goalkeeper Development Award\n• Clean Sheet in Regional Cup\n• Academic Excellence',
            'photo' => null,
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
        ]);

        Player::create([
            'name' => 'Peter Mwangi',
            'first_name' => 'Peter',
            'last_name' => 'Mwangi',
            'age' => 17,
            'position' => 'midfielder',
            'nationality' => 'Kenyan',
            'height_cm' => 175,
            'weight_kg' => 67,
            'dominant_foot' => 'Left',
            'goals_scored' => 5,
            'assists' => 11,
            'performance_rating' => 8.3,
            'matches_played' => 29,
            'bio' => 'Peter Mwangi is a creative attacking midfielder from Thika, known for his vision and ability to unlock defenses. His left-footed delivery and technical skills make him a key playmaker.',
            'achievements' => '• Most Assists 2024\n• Creative Player Award\n• National Youth Team Call-up',
            'photo' => null,
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
        ]);

        Player::create([
            'name' => 'Sarah Kiprop',
            'first_name' => 'Sarah',
            'last_name' => 'Kiprop',
            'age' => 15,
            'position' => 'defender',
            'nationality' => 'Kenyan',
            'height_cm' => 172,
            'weight_kg' => 63,
            'dominant_foot' => 'Right',
            'goals_scored' => 1,
            'assists' => 2,
            'performance_rating' => 7.8,
            'matches_played' => 26,
            'bio' => 'Sarah Kiprop is a reliable center-back from Nakuru, showing good positional sense and tackling ability. Her leadership qualities are evident both on and off the pitch.',
            'achievements' => '• Defensive Rookie Award\n• Leadership Recognition\n• Community Service Award',
            'photo' => null,
            'program_id' => 1,
            'registration_status' => 'Active',
            'approval_type' => 'full',
        ]);
    }
}
