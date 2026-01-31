<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Blog::create([
            'title' => 'Vipers Academy Mumias Wins KFF Youth League Championship',
            'slug' => Str::slug('Vipers Academy Mumias Wins KFF Youth League Championship'),
            'excerpt' => 'Our U-18 team has won the KFF Youth League Championship after an outstanding season.',
            'content' => 'Vipers Academy Mumias is proud to announce that our U-18 team has won the Kenya Football Federation (KFF) Youth League Championship! This remarkable achievement marks a significant milestone in our academy\'s history.\n\nThe team showed exceptional skill, determination, and teamwork throughout the tournament. Coach John Mwangi praised the players for their dedication and hard work.\n\n"We are incredibly proud of our young players. They have worked tirelessly and this victory is a testament to their commitment to excellence," said Coach Mwangi.\n\nThe final match was a thrilling encounter that went into extra time, with our team scoring the winning goal in the 118th minute. The players celebrated with their families and fans who came out in large numbers to support them.\n\nThis championship win follows our academy\'s philosophy of developing young talent and instilling core values of discipline, respect, and sportsmanship.',
            'author' => 'Vipers Academy',
            'category' => 'Academy News',
            'published_at' => now(),
            'is_featured' => true,
            'views' => 1250,
        ]);

        Blog::create([
            'title' => 'Mumias Community Football Festival Draws 500+ Participants',
            'slug' => Str::slug('Mumias Community Football Festival Draws 500+ Participants'),
            'excerpt' => 'Over 500 young footballers participated in our annual community football festival.',
            'content' => 'Vipers Academy hosted its annual Community Football Festival, attracting over 500 participants from across Mumias and surrounding areas. The event was a huge success, bringing together young football enthusiasts for a day of fun, competition, and skill development.\n\nThe festival featured various age categories, from U-6 to U-16, with matches organized by our experienced coaching staff. Participants had the opportunity to learn new skills, make new friends, and experience the joy of football.\n\n"We believe in using football as a tool for positive youth development. This festival is just one of the ways we give back to our community," said Academy Director Peter Ouma.\n\nThe event also included coaching clinics where participants learned technical skills from our certified coaches. Prizes were awarded to the best performers in each category.\n\nWe would like to thank all participants, parents, volunteers, and sponsors who made this event possible.',
            'author' => 'Vipers Academy',
            'category' => 'Events',
            'published_at' => now()->subDays(2),
            'is_featured' => false,
            'views' => 890,
        ]);

        Blog::create([
            'title' => 'Former Harambee Stars Captain Joins Vipers Academy Coaching Staff',
            'slug' => Str::slug('Former Harambee Stars Captain Joins Vipers Academy Coaching Staff'),
            'excerpt' => 'We are excited to welcome former Harambee Stars captain to our coaching team.',
            'content' => 'Vipers Academy is delighted to announce that former Harambee Stars captain Dennis Oliech has joined our coaching staff as a Technical Director.\n\nOliech, who captained the national team for over 50 matches, brings with him a wealth of experience from his professional career in Europe and Africa.\n\n"I am honored to join Vipers Academy and contribute to developing the next generation of Kenyan footballers. This academy shares my vision for nurturing talent and instilling discipline," said Oliech.\n\nHis appointment comes as part of our ongoing commitment to providing world-class football education to young players in Mumias and beyond.\n\nOliech will oversee our youth development programs and work closely with our existing coaching staff to implement advanced training methodologies.\n\nWe are confident that his expertise will help our players reach their full potential and possibly break into the national team.',
            'author' => 'Vipers Academy',
            'category' => 'Academy News',
            'published_at' => now()->subDays(5),
            'is_featured' => true,
            'views' => 2100,
        ]);

        Blog::create([
            'title' => 'Vipers Academy Launches AI-Powered Player Development Program',
            'slug' => Str::slug('Vipers Academy Launches AI-Powered Player Development Program'),
            'excerpt' => 'Introducing our new AI-powered player development program for enhanced training.',
            'content' => 'Vipers Academy is at the forefront of football technology with the launch of our new AI-powered player development program. This innovative system uses artificial intelligence to analyze player performance and provide personalized training recommendations.\n\nThe program tracks various metrics including speed, agility, technique, and decision-making abilities. Coaches can now access detailed analytics to identify areas for improvement and track progress over time.\n\n"We believe in embracing technology to enhance our training methods. This AI system will help us provide more targeted coaching to each player," explained Technical Director Dennis Oliech.\n\nThe system also includes a mobile app where players and parents can view progress reports and training schedules. This transparency helps maintain open communication between the academy and families.\n\nInitial results show promising improvements in player performance since the program\'s pilot phase. We are excited to continue innovating and providing the best possible training environment for our players.',
            'author' => 'Vipers Academy',
            'category' => 'Training Updates',
            'published_at' => now()->subDays(7),
            'is_featured' => false,
            'views' => 1560,
        ]);

        Blog::create([
            'title' => 'Mumias Sugar Company Partners with Vipers Academy for Youth Development',
            'slug' => Str::slug('Mumias Sugar Company Partners with Vipers Academy for Youth Development'),
            'excerpt' => 'Strategic partnership announced to support youth football development in Mumias.',
            'content' => 'Vipers Academy is thrilled to announce a strategic partnership with Mumias Sugar Company to support youth football development in our region. This partnership will provide crucial funding and resources to help us expand our programs and reach more young people.\n\nUnder the partnership, Mumias Sugar Company will sponsor our youth league, provide equipment, and support our community football festivals. The company has a long history of supporting youth sports in the region.\n\n"We are grateful for this partnership. It will enable us to provide more opportunities for young people in our community," said Academy Director Peter Ouma.\n\nThe partnership was officially launched at a ceremony attended by company executives, local government officials, and academy stakeholders.\n\nAs part of the partnership, Mumias Sugar Company will also provide internship opportunities for our academy graduates, helping them transition into professional football or related careers.\n\nThis collaboration represents our shared commitment to youth development and community empowerment through sports.',
            'author' => 'Vipers Academy',
            'category' => 'Announcements',
            'published_at' => now()->subDays(10),
            'is_featured' => false,
            'views' => 980,
        ]);

        Blog::create([
            'title' => 'Vipers Academy Launches Elite Women\'s Football Program',
            'slug' => Str::slug('Vipers Academy Launches Elite Women\'s Football Program'),
            'excerpt' => 'New program to develop women\'s football talent in the region.',
            'content' => 'Vipers Academy is proud to announce the launch of our Elite Women\'s Football Program. This new initiative aims to provide young women with opportunities to develop their football skills and pursue careers in the sport.\n\nThe program will offer specialized training, competitive matches, and pathways to higher education and professional opportunities. We have hired experienced female coaches who will mentor our players both on and off the pitch.\n\n"Women\'s football is growing rapidly in Kenya, and we want to be at the forefront of this movement. Our program will provide the support and resources needed for young women to excel," said Program Coordinator Sarah Akinyi.\n\nThe program will start with U-14 and U-17 teams, with plans to expand to other age groups. Trials will be held next month at our academy grounds.\n\nWe encourage all interested young women to register and take advantage of this exciting opportunity. Together, we can build a strong women\'s football community.',
            'author' => 'Vipers Academy',
            'category' => 'Academy News',
            'published_at' => now()->subDays(12),
            'is_featured' => true,
            'views' => 2340,
        ]);

        Blog::create([
            'title' => 'Academy Players Excel in National Mathematics Olympiad',
            'slug' => Str::slug('Academy Players Excel in National Mathematics Olympiad'),
            'excerpt' => 'Our players showcase academic excellence alongside football achievements.',
            'content' => 'Several Vipers Academy players have demonstrated that they excel both on the pitch and in the classroom. Three of our U-16 players recently won medals at the National Mathematics Olympiad.\n\nThis achievement reflects our academy\'s commitment to holistic development. We emphasize the importance of education alongside football training, ensuring our players have options for their future.\n\n"Our players understand that education is their foundation. Football skills can take them far, but education will always be there as a backup and a life skill," said Academic Coordinator Mr. Wanyama.\n\nThe players trained hard for the olympiad while maintaining their football schedules. Their dedication to both academics and sports is truly commendable.\n\nWe congratulate them on this outstanding achievement and encourage all our players to follow their example in pursuing excellence in all areas of life.',
            'author' => 'Vipers Academy',
            'category' => 'Player Updates',
            'published_at' => now()->subDays(14),
            'is_featured' => false,
            'views' => 1120,
        ]);

        Blog::create([
            'title' => 'Vipers Academy Hosts International Coaching Clinic',
            'slug' => Str::slug('Vipers Academy Hosts International Coaching Clinic'),
            'excerpt' => 'Coaches from across East Africa attend our international coaching clinic.',
            'content' => 'Vipers Academy recently hosted an international coaching clinic that attracted coaches from Kenya, Uganda, Tanzania, and Rwanda. The clinic focused on modern football methodologies and youth development strategies.\n\nOur technical team, led by Technical Director Dennis Oliech, shared best practices and learned from international guest coaches. The event fostered knowledge sharing and collaboration within the East African football community.\n\n"We believe that by raising the standard of coaching, we can improve the quality of football across the region. This clinic was a step in that direction," said Oliech.\n\nParticipants attended theoretical sessions and practical demonstrations covering topics like tactical development, physical conditioning, and psychological aspects of the game.\n\nWe received positive feedback from attendees and plan to make this an annual event. Our goal is to position Mumias as a center of football excellence in East Africa.',
            'author' => 'Vipers Academy',
            'category' => 'Training Updates',
            'published_at' => now()->subDays(16),
            'is_featured' => false,
            'views' => 765,
        ]);

        Blog::create([
            'title' => 'Former Vipers Player Signs Professional Contract in Europe',
            'slug' => Str::slug('Former Vipers Player Signs Professional Contract in Europe'),
            'excerpt' => 'Proud moment as our academy graduate signs with a European club.',
            'content' => 'We are proud to announce that former Vipers Academy player Alex Ochieng has signed his first professional contract with a Swedish football club.\n\nOchieng came through our academy\'s youth system and showed exceptional talent from an early age. After graduating from our program, he joined a local professional club where his performances caught the attention of European scouts.\n\n"This is a dream come true. Vipers Academy gave me the foundation I needed to succeed. I will always be grateful for the training and support I received," said Ochieng.\n\nHis journey is an inspiration to our current players, demonstrating that with hard work and dedication, it is possible to reach the highest levels of football.\n\nWe wish Alex all the best in his professional career and hope he will continue to make our academy and community proud.',
            'author' => 'Vipers Academy',
            'category' => 'Transfer News',
            'published_at' => now()->subDays(18),
            'is_featured' => true,
            'views' => 3450,
        ]);

        Blog::create([
            'title' => 'Academy Introduces Mental Health Support Program',
            'slug' => Str::slug('Academy Introduces Mental Health Support Program'),
            'excerpt' => 'New initiative to support the psychological well-being of our players.',
            'content' => 'Vipers Academy is committed to the well-being of our players both on and off the pitch. We have introduced a comprehensive mental health support program to help players cope with the pressures of competitive sports.\n\nThe program includes regular workshops on stress management, mindfulness, and goal setting. We have partnered with licensed counselors who provide confidential support to players who need it.\n\n"Mental health is just as important as physical health. Our players face many pressures, and we want to equip them with tools to manage stress and maintain a healthy mindset," said Sports Psychologist Dr. Amina Hassan.\n\nPlayers have responded positively to the program, reporting improved focus and reduced anxiety. The academy has also provided training to coaches on recognizing signs of mental health issues in young athletes.\n\nWe believe that by supporting our players\' mental health, we are helping them become not just better footballers, but happier and more resilient individuals.',
            'author' => 'Vipers Academy',
            'category' => 'Announcements',
            'published_at' => now()->subDays(20),
            'is_featured' => false,
            'views' => 890,
        ]);
    }
}
