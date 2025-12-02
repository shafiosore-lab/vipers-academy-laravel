<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        News::create([
            'title' => 'Vipers Academy Mumias Wins KFF Youth League Championship',
            'content' => 'In a thrilling finale at the Mumias Sports Complex, Vipers Academy Mumias clinched the Kenya Football Federation Youth League Championship with a 3-1 victory over AFC Leopards. Captain Brian Oduya scored twice, while midfielder Kevin Kiprop added the third goal. Coach Samuel Njoroge praised the team\'s resilience throughout the season, saying "This victory is for the entire Mumias community. Our players have shown that with dedication and proper training, Kenyan youth can compete at the highest levels." The championship win qualifies the team for the national playoffs next month.',
            'category' => 'Achievements',
            'image' => 'https://img.freepik.com/free-photo/soccer-players-action-professional-stadium_654080-1130.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=abc123def456',
            'published_at' => now()->subDays(2),
        ]);

        News::create([
            'title' => 'Mumias Community Football Festival Draws 500+ Participants',
            'content' => 'Vipers Academy Mumias successfully hosted the annual Mumias Community Football Festival at the academy grounds, attracting over 500 participants from across Western Kenya. The three-day event featured youth tournaments, coaching clinics, and community outreach programs. Notable participants included teams from Kakamega, Bungoma, and Busia counties. Academy director Grace Achieng noted, "Events like this strengthen football development in our region and identify new talent for our programs." The festival concluded with Vipers Academy emerging victorious in the main tournament, reinforcing their dominance in local youth football.',
            'category' => 'Events',
            'image' => 'https://img.freepik.com/free-photo/group-kids-playing-soccer-outdoors_23-2149521435.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=def456ghi789',
            'published_at' => now()->subDays(5),
        ]);

        News::create([
            'title' => 'Former Harambee Stars Captain Joins Vipers Academy Coaching Staff',
            'content' => 'Former Kenya national team captain and Mumias-born footballer Dennis Oliech has joined Vipers Academy Mumias as a senior coach and youth development consultant. Oliech, who played professionally in France and Israel, brings over 15 years of international experience to the academy. "I\'m excited to return to my roots in Mumias and contribute to developing the next generation of Kenyan football talent," said Oliech. His appointment is part of the academy\'s strategic plan to bridge the gap between grassroots football and professional opportunities. Oliech will oversee the advanced training programs and provide mentorship to aspiring professional players.',
            'category' => 'Achievements',
            'image' => 'https://img.freepik.com/free-photo/professional-soccer-coach-training-team_23-2149066215.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=ghi789jkl012',
            'published_at' => now()->subDays(7),
        ]);

        News::create([
            'title' => 'Vipers Academy Launches AI-Powered Player Development Program',
            'content' => 'Vipers Academy Mumias has launched an innovative AI-powered player development program in partnership with Kenyan tech firm Safaricom and international sports analytics company Opta. The program uses machine learning algorithms to analyze player performance data and provide personalized training recommendations. "This technology allows us to identify strengths and weaknesses more accurately than traditional methods," explained technical director Dr. James Kiprop. The program has already shown promising results, with participating players showing 25% improvement in technical skills within the first month. The initiative positions Vipers Academy at the forefront of football technology adoption in East Africa.',
            'category' => 'Programs',
            'image' => 'https://img.freepik.com/free-photo/soccer-player-training-with-modern-technology_23-2149215931.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=jkl012mno345',
            'published_at' => now()->subDays(10),
        ]);

        News::create([
            'title' => 'Mumias Sugar Company Partners with Vipers Academy for Youth Development',
            'content' => 'Mumias Sugar Company has signed a three-year partnership agreement with Vipers Academy Mumias to support youth football development in the region. The partnership includes funding for new training equipment, scholarship programs for talented but financially disadvantaged players, and community outreach initiatives. "Investing in our youth through football is investing in our community\'s future," stated Mumias Sugar CEO Dr. Erick Kiprotich. The partnership will also include joint programs to promote healthy lifestyles and education among young people. Vipers Academy director welcomed the collaboration, noting that such partnerships are crucial for sustainable football development in rural Kenya.',
            'category' => 'Partnerships',
            'image' => 'https://img.freepik.com/free-photo/business-partnership-handshake-concept_53876-65055.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=mno345pqr678',
            'published_at' => now()->subDays(12),
        ]);

        // Additional news articles to reach 20 total
        News::create([
            'title' => 'Vipers Academy Launches Elite Women\'s Football Program',
            'content' => 'Vipers Academy Mumias has launched a dedicated elite women\'s football program aimed at developing female talent across East Africa. The program features specialized training sessions, nutritional guidance, and mentorship from professional female coaches. "We\'re committed to gender equality in football and believe this program will produce the next generation of female football stars," said academy director Grace Achieng. The program has already attracted 50 participants and includes partnerships with regional women\'s teams.',
            'category' => 'Programs',
            'image' => 'https://img.freepik.com/free-photo/women-playing-soccer-field_23-2149521435.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=abc123def456',
            'published_at' => now()->subDays(15),
        ]);

        News::create([
            'title' => 'Academy Players Excel in National Mathematics Olympiad',
            'content' => 'Three Vipers Academy students have qualified for the national stage of the Kenya Mathematics Olympiad, showcasing the academy\'s commitment to holistic education. Brian Kiprop, Sarah Wanjiku, and David Oduya achieved top scores in the regional qualifiers. "Our integrated approach combines sports excellence with academic achievement," noted education coordinator Ms. Mary Wambui. The students will represent Western Kenya in the national finals next month.',
            'category' => 'Achievements',
            'image' => 'https://img.freepik.com/free-photo/students-studying-together_23-2147668975.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=def456ghi789',
            'published_at' => now()->subDays(18),
        ]);

        News::create([
            'title' => 'Vipers Academy Hosts International Coaching Clinic',
            'content' => 'Over 100 coaches from across East Africa attended a three-day international coaching clinic hosted by Vipers Academy Mumias. The clinic featured sessions from UEFA-licensed coaches and covered modern training methodologies, youth development strategies, and performance analysis techniques. "This event strengthens the coaching infrastructure in our region," said technical director James Kiprop. Participants received certification and training materials for implementation in their respective academies.',
            'category' => 'Events',
            'image' => 'https://img.freepik.com/free-photo/soccer-coach-training-team_23-2149066215.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=ghi789jkl012',
            'published_at' => now()->subDays(20),
        ]);

        News::create([
            'title' => 'Former Vipers Player Signs Professional Contract in Europe',
            'content' => 'Former Vipers Academy graduate Michael Oduya has signed his first professional contract with FC Basel in Switzerland. The 19-year-old midfielder, who left the academy two years ago, impressed during trials and earned a three-year deal. "This is a proud moment for Vipers Academy and proof that our development model works," said academy director Grace Achieng. Michael\'s success story inspires current academy players and validates the academy\'s professional pathway program.',
            'category' => 'Achievements',
            'image' => 'https://img.freepik.com/free-photo/soccer-player-celebrating-goal_23-2147892345.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=jkl012mno345',
            'published_at' => now()->subDays(22),
        ]);

        News::create([
            'title' => 'Academy Introduces Mental Health Support Program',
            'content' => 'Vipers Academy Mumias has partnered with local mental health professionals to introduce a comprehensive mental health support program for players. The initiative includes regular counseling sessions, stress management workshops, and performance psychology training. "Mental health is as important as physical fitness in elite sports," explained sports psychologist Dr. Sarah Kiprop. The program has received positive feedback from players and parents, contributing to a healthier academy environment.',
            'category' => 'Programs',
            'image' => 'https://img.freepik.com/free-photo/psychologist-consultation_23-2147892345.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=mno345pqr678',
            'published_at' => now()->subDays(25),
        ]);

        News::create([
            'title' => 'Vipers Academy Wins Environmental Sustainability Award',
            'content' => 'Vipers Academy Mumias has been recognized with the Green Sports Award for its environmental sustainability initiatives. The academy\'s eco-friendly practices include solar-powered facilities, rainwater harvesting, and community tree-planting programs. "Sports and environmental responsibility go hand in hand," said sustainability coordinator Peter Kiprop. The award includes a grant for expanding the academy\'s green initiatives and serves as a model for other sports organizations in Kenya.',
            'category' => 'Achievements',
            'image' => 'https://img.freepik.com/free-photo/environmental-sustainability-concept_23-2147892345.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=pqr678stu901',
            'published_at' => now()->subDays(28),
        ]);

        News::create([
            'title' => 'Regional Talent Identification Tour Completed',
            'content' => 'Vipers Academy Mumias has successfully completed its annual regional talent identification tour, visiting 25 schools across Western Kenya. The tour identified 150 promising young players who will join the academy\'s development programs. "This grassroots approach ensures we\'re developing talent from the foundation level," said scouting coordinator David Oduya. Selected players will begin training next month, with full scholarships available for those from disadvantaged backgrounds.',
            'category' => 'Programs',
            'image' => 'https://img.freepik.com/free-photo/scouts-watching-young-players_23-2147892345.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=stu901vwx234',
            'published_at' => now()->subDays(30),
        ]);

        News::create([
            'title' => 'Academy Alumni Reunion Draws 200 Former Players',
            'content' => 'Over 200 former Vipers Academy players attended the annual alumni reunion at the Mumias Sports Complex. The event featured friendly matches, coaching clinics, and networking opportunities. Notable alumni included national team players and professional footballers. "Seeing our graduates succeed is the greatest reward," said academy founder Samuel Njoroge. The reunion raised funds for academy scholarships and strengthened the alumni network.',
            'category' => 'Events',
            'image' => 'https://img.freepik.com/free-photo/alumni-reunion-event_23-2147892345.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=vwx234yz567',
            'published_at' => now()->subDays(32),
        ]);

        News::create([
            'title' => 'New Sports Science Laboratory Opens at Academy',
            'content' => 'Vipers Academy Mumias has inaugurated a state-of-the-art sports science laboratory equipped with the latest performance analysis technology. The facility includes motion capture systems, biomechanical analysis tools, and recovery monitoring equipment. "This investment in technology will give our players a competitive edge," said technical director James Kiprop. The laboratory will support player development, injury prevention, and performance optimization.',
            'category' => 'Programs',
            'image' => 'https://img.freepik.com/free-photo/sports-science-laboratory_23-2147892345.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=yz567abc890',
            'published_at' => now()->subDays(35),
        ]);

        News::create([
            'title' => 'Vipers Academy Partners with Local Hospitals for Player Healthcare',
            'content' => 'Vipers Academy Mumias has established partnerships with three local hospitals to provide comprehensive healthcare services for academy players. The collaboration includes regular medical check-ups, injury treatment, and nutritional counseling. "Player health and safety are our top priorities," said medical coordinator Dr. Mary Wambui. The partnerships ensure players receive professional medical care and contribute to community health initiatives.',
            'category' => 'Partnerships',
            'image' => 'https://img.freepik.com/free-photo/medical-checkup-soccer-player_23-2147892345.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=abc890def123',
            'published_at' => now()->subDays(38),
        ]);

        News::create([
            'title' => 'Academy Hosts Youth Leadership Conference',
            'content' => 'Vipers Academy Mumias organized a youth leadership conference that brought together 300 young people from across Western Kenya. The event featured motivational speakers, leadership workshops, and sports demonstrations. "Developing well-rounded individuals is our mission," said academy director Grace Achieng. Participants learned about goal-setting, teamwork, and community service, with many expressing interest in the academy\'s programs.',
            'category' => 'Events',
            'image' => 'https://img.freepik.com/free-photo/youth-leadership-conference_23-2147892345.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=def123ghi456',
            'published_at' => now()->subDays(40),
        ]);

        News::create([
            'title' => 'Vipers Academy Receives International Accreditation',
            'content' => 'Vipers Academy Mumias has received international accreditation from the Confederation of African Football (CAF) for its youth development programs. The accreditation recognizes the academy\'s high standards in coaching, facilities, and player development. "This is a significant milestone for Kenyan football," said CAF representative Ahmed Hassan. The accreditation opens doors for international collaborations and player exchange programs.',
            'category' => 'Achievements',
            'image' => 'https://img.freepik.com/free-photo/caf-accreditation-certificate_23-2147892345.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=ghi456jkl789',
            'published_at' => now()->subDays(42),
        ]);

        News::create([
            'title' => 'Academy Launches Digital Learning Platform',
            'content' => 'Vipers Academy Mumias has launched an innovative digital learning platform that provides online coaching resources, video tutorials, and performance tracking tools. The platform is accessible to academy players, alumni, and partner organizations. "Technology enhances our reach and effectiveness," said IT coordinator Peter Kiprop. The platform includes modules on technique, tactics, fitness, and mental preparation.',
            'category' => 'Programs',
            'image' => 'https://img.freepik.com/free-photo/digital-learning-platform_23-2147892345.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=jkl789mno012',
            'published_at' => now()->subDays(45),
        ]);

        News::create([
            'title' => 'Vipers Academy Wins Community Service Award',
            'content' => 'Vipers Academy Mumias has been awarded the Community Service Excellence Award for its extensive outreach programs. The academy organizes regular community coaching sessions, anti-drugs campaigns, and educational support programs. "Football is a tool for positive change," said community liaison officer Sarah Wanjiku. The award recognizes the academy\'s commitment to using sports for community development.',
            'category' => 'Achievements',
            'image' => 'https://img.freepik.com/free-photo/community-service-award_23-2147892345.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=mno012pqr345',
            'published_at' => now()->subDays(48),
        ]);

        News::create([
            'title' => 'International Scouts Visit Vipers Academy',
            'content' => 'Representatives from European clubs including Ajax, Barcelona, and Arsenal visited Vipers Academy Mumias for a talent scouting mission. The scouts observed training sessions and matches, identifying several players for potential trials. "This exposure is invaluable for our players," said academy director Grace Achieng. The visit strengthens the academy\'s international reputation and provides pathways for players to reach the highest levels of football.',
            'category' => 'Events',
            'image' => 'https://img.freepik.com/free-photo/international-scouts-visiting_23-2147892345.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=pqr345stu678',
            'published_at' => now()->subDays(50),
        ]);

        News::create([
            'title' => 'Academy Expands Facilities with New Training Complex',
            'content' => 'Vipers Academy Mumias has completed construction of a new training complex featuring multiple grass pitches, a gymnasium, and rehabilitation facilities. The expansion was funded through partnerships and donations. "These world-class facilities will attract top talent and improve our training quality," said facilities manager David Oduya. The complex includes environmentally sustainable features and serves as a regional training hub.',
            'category' => 'Programs',
            'image' => 'https://img.freepik.com/free-photo/new-training-complex_23-2147892345.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=stu678vwx901',
            'published_at' => now()->subDays(52),
        ]);

        News::create([
            'title' => 'Vipers Academy Partners with Technology Companies',
            'content' => 'Vipers Academy Mumias has formed partnerships with leading Kenyan technology companies to develop football analytics and performance tracking solutions. The collaboration will create custom software for player development and match analysis. "Innovation drives excellence in sports," said technology partner CEO Mark Kiprop. The partnerships will provide cutting-edge tools for coaches and players while creating local tech jobs.',
            'category' => 'Partnerships',
            'image' => 'https://img.freepik.com/free-photo/technology-partnership_23-2147892345.jpg?w=800&t=st=1699800000~exp=1699800600~hmac=vwx901yz234',
            'published_at' => now()->subDays(55),
        ]);
    }
}
