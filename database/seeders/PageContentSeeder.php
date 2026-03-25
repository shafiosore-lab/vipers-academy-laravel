<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Home Page Content
        $this->seedHomePage();

        // About Page Content
        $this->seedAboutPage();

        // Programs Page Content
        $this->seedProgramsPage();

        // Announcements Page Content
        $this->seedAnnouncementsPage();

        // Careers Page Content
        $this->seedCareersPage();

        // Leaders Page Content
        $this->seedLeadersPage();
    }

    private function seedHomePage()
    {
        $page = 'home';

        // Hero Section
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'title'],
            ['value' => 'Transforming Lives Through Football & Education', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'subtitle'],
            ['value' => 'Founded in 2016, Mumias Vipers Academy is a community-based youth development organization using football to nurture talent, discipline, and education — with over 20 players currently on high school sports scholarships, accessing quality education that was once out of reach.', 'type' => 'textarea', 'sort_order' => 2, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'image_title'],
            ['value' => 'Mumias Vipers Academy football training', 'type' => 'text', 'sort_order' => 3, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'cta_text'],
            ['value' => 'Join Our Academy', 'type' => 'text', 'sort_order' => 4, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'cta_link'],
            ['value' => '/enroll', 'type' => 'text', 'sort_order' => 5, 'is_active' => true]
        );

        // Mission Section
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'mission', 'key' => 'title'],
            ['value' => 'Connecting Talent with Opportunity', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'mission', 'key' => 'description'],
            ['value' => 'We are dedicated to developing young talent from the ground up. We identify vulnerable and promising children, nurture their abilities through structured training and mentorship, and shape them into disciplined, competitive players. Beyond the pitch, we connect outstanding talents to sports scholarships and career opportunities, while promoting positive behavior change and lasting impact within our community.', 'type' => 'textarea', 'sort_order' => 2, 'is_active' => true]
        );

        // Impact Areas
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'impact', 'key' => 'title'],
            ['value' => 'Our Impact Areas', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'impact', 'key' => 'education_access'],
            ['value' => 'Education Access', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'impact', 'key' => 'sports_scholarships'],
            ['value' => 'Sports scholarships', 'type' => 'text', 'sort_order' => 3, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'impact', 'key' => 'community_building'],
            ['value' => 'Community Building', 'type' => 'text', 'sort_order' => 4, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'impact', 'key' => 'family_involvement'],
            ['value' => 'Family involvement', 'type' => 'text', 'sort_order' => 5, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'impact', 'key' => 'behavior_change'],
            ['value' => 'Behavior Change', 'type' => 'text', 'sort_order' => 6, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'impact', 'key' => 'positive_values'],
            ['value' => 'Positive values', 'type' => 'text', 'sort_order' => 7, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'impact', 'key' => 'healthy_lifestyles'],
            ['value' => 'Healthy Lifestyles', 'type' => 'text', 'sort_order' => 8, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'impact', 'key' => 'physical_activity'],
            ['value' => 'Physical activity', 'type' => 'text', 'sort_order' => 9, 'is_active' => true]
        );

        // Success Stories Section
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'success_stories', 'key' => 'title'],
            ['value' => 'Success Stories', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'success_stories', 'key' => 'subtitle'],
            ['value' => 'Real lives transformed through football and education', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
        );

        // Scholarship Success Story
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'success_stories', 'key' => 'story_1_type'],
            ['value' => 'Scholarship recipient', 'type' => 'text', 'sort_order' => 3, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'success_stories', 'key' => 'story_1_title'],
            ['value' => 'Sports Scholarship Success', 'type' => 'text', 'sort_order' => 4, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'success_stories', 'key' => 'story_1_quote'],
            ['value' => '"Thanks to Vipers Academy, I received a full sports scholarship to secondary school. Football opened doors to education I never thought possible."', 'type' => 'textarea', 'sort_order' => 5, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'success_stories', 'key' => 'story_1_author'],
            ['value' => 'Scholarship Recipient, Class of 2024', 'type' => 'text', 'sort_order' => 6, 'is_active' => true]
        );

        // Community Success Story
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'success_stories', 'key' => 'story_2_type'],
            ['value' => 'Community member', 'type' => 'text', 'sort_order' => 7, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'success_stories', 'key' => 'story_2_title'],
            ['value' => 'Community Transformation', 'type' => 'text', 'sort_order' => 8, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'success_stories', 'key' => 'story_2_quote'],
            ['value' => '"The academy has brought our community together. My son learned discipline and now helps coach younger players. It\'s changed our whole family."', 'type' => 'textarea', 'sort_order' => 9, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'success_stories', 'key' => 'story_2_author'],
            ['value' => 'Parent & Community Member', 'type' => 'text', 'sort_order' => 10, 'is_active' => true]
        );

        // Player Success Story
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'success_stories', 'key' => 'story_3_type'],
            ['value' => 'Young player', 'type' => 'text', 'sort_order' => 11, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'success_stories', 'key' => 'story_3_title'],
            ['value' => 'Life Skills Development', 'type' => 'text', 'sort_order' => 12, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'success_stories', 'key' => 'story_3_quote'],
            ['value' => '"Vipers taught me more than football. I learned responsibility, teamwork, and how to balance sports with studies. These skills will help me for life."', 'type' => 'textarea', 'sort_order' => 13, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'success_stories', 'key' => 'story_3_author'],
            ['value' => 'Brian Onyango, Age 15', 'type' => 'text', 'sort_order' => 14, 'is_active' => true]
        );
    }

    private function seedAboutPage()
    {
        $page = 'about';

        // Hero Section
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'title'],
            ['value' => 'About Mumias Vipers Academy', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'subtitle'],
            ['value' => 'Transforming Lives Through Football & Education', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
        );

        // Story Section
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'story', 'key' => 'title'],
            ['value' => 'Our Story', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'story', 'key' => 'content'],
            ['value' => 'Founded in 2016, Mumias Vipers Academy is a community-based youth development organization using football to nurture talent, discipline, and education. We have grown to become one of the most respected youth football academies in the region, with over 20 players currently on high school sports scholarships.', 'type' => 'textarea', 'sort_order' => 2, 'is_active' => true]
        );

        // Mission Section
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'mission', 'key' => 'title'],
            ['value' => 'Our Mission', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'mission', 'key' => 'content'],
            ['value' => 'To identify, nurture and develop young talent through structured football training and mentorship, while promoting education and positive life skills that transform lives and build stronger communities.', 'type' => 'textarea', 'sort_order' => 2, 'is_active' => true]
        );

        // Vision Section
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'vision', 'key' => 'title'],
            ['value' => 'Our Vision', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'vision', 'key' => 'content'],
            ['value' => 'To be the leading youth football academy in Kenya, producing not only skilled footballers but also educated, disciplined, and responsible citizens who contribute positively to society.', 'type' => 'textarea', 'sort_order' => 2, 'is_active' => true]
        );

        // Core Values
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'values', 'key' => 'title'],
            ['value' => 'Our Core Values', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'values', 'key' => 'discipline'],
            ['value' => json_encode(['icon' => '🎯', 'title' => 'Discipline', 'description' => 'We instill discipline as the foundation of success both on and off the pitch.']), 'type' => 'json', 'sort_order' => 2, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'values', 'key' => 'respect'],
            ['value' => json_encode(['icon' => '🤝', 'title' => 'Respect', 'description' => 'We teach respect for teammates, coaches, opponents, and the community.']), 'type' => 'json', 'sort_order' => 3, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'values', 'key' => 'education'],
            ['value' => json_encode(['icon' => '📚', 'title' => 'Education First', 'description' => 'We prioritize academic excellence alongside football development.']), 'type' => 'json', 'sort_order' => 4, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'values', 'key' => 'teamwork'],
            ['value' => json_encode(['icon' => '⚽', 'title' => 'Teamwork', 'description' => 'We believe success comes through collective effort and supporting each other.']), 'type' => 'json', 'sort_order' => 5, 'is_active' => true]
        );

        // Impact Stats
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stats', 'key' => 'title'],
            ['value' => 'Our Impact', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stats', 'key' => 'stat_1_value'],
            ['value' => '20+', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stats', 'key' => 'stat_1_label'],
            ['value' => 'Scholarship Recipients', 'type' => 'text', 'sort_order' => 3, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stats', 'key' => 'stat_2_value'],
            ['value' => '500+', 'type' => 'text', 'sort_order' => 4, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stats', 'key' => 'stat_2_label'],
            ['value' => 'Players Trained', 'type' => 'text', 'sort_order' => 5, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stats', 'key' => 'stat_3_value'],
            ['value' => '50+', 'type' => 'text', 'sort_order' => 6, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stats', 'key' => 'stat_3_label'],
            ['value' => 'Coach Network', 'type' => 'text', 'sort_order' => 7, 'is_active' => true]
        );
    }

    private function seedProgramsPage()
    {
        $page = 'programs';

        // Hero Section
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'title'],
            ['value' => 'Our Programs', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'subtitle'],
            ['value' => 'Football | Academics | Technology', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'description'],
            ['value' => 'Choose from our comprehensive range of development programs', 'type' => 'text', 'sort_order' => 3, 'is_active' => true]
        );

        // Football Training Program
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'football', 'key' => 'title'],
            ['value' => 'Football Training', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'football', 'key' => 'subtitle'],
            ['value' => 'Professional Skills Development', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'football', 'key' => 'feature_1'],
            ['value' => 'Weekend training sessions', 'type' => 'text', 'sort_order' => 3, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'football', 'key' => 'feature_2'],
            ['value' => 'Theory & tactical classes', 'type' => 'text', 'sort_order' => 4, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'football', 'key' => 'feature_3'],
            ['value' => 'Age-appropriate groups', 'type' => 'text', 'sort_order' => 5, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'football', 'key' => 'feature_4'],
            ['value' => 'Tournament participation', 'type' => 'text', 'sort_order' => 6, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'football', 'key' => 'cta'],
            ['value' => 'Weekend Programs', 'type' => 'text', 'sort_order' => 7, 'is_active' => true]
        );

        // Academic Mentorship Program
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'academic', 'key' => 'title'],
            ['value' => 'Academic Mentorship', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'academic', 'key' => 'subtitle'],
            ['value' => 'CBC-Aligned Support', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'academic', 'key' => 'feature_1'],
            ['value' => 'Study discipline coaching', 'type' => 'text', 'sort_order' => 3, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'academic', 'key' => 'feature_2'],
            ['value' => 'CBC homework support', 'type' => 'text', 'sort_order' => 4, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'academic', 'key' => 'feature_3'],
            ['value' => 'Life-skills mentorship', 'type' => 'text', 'sort_order' => 5, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'academic', 'key' => 'feature_4'],
            ['value' => 'Behavior tracking system', 'type' => 'text', 'sort_order' => 6, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'academic', 'key' => 'cta'],
            ['value' => 'Study Support', 'type' => 'text', 'sort_order' => 7, 'is_active' => true]
        );

        // Digital Skills Program
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'digital', 'key' => 'title'],
            ['value' => 'Digital Skills', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'digital', 'key' => 'subtitle'],
            ['value' => 'Technology Integration', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'digital', 'key' => 'feature_1'],
            ['value' => 'Computer basics training', 'type' => 'text', 'sort_order' => 3, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'digital', 'key' => 'feature_2'],
            ['value' => 'Introduction to coding', 'type' => 'text', 'sort_order' => 4, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'digital', 'key' => 'feature_3'],
            ['value' => 'Digital safety education', 'type' => 'text', 'sort_order' => 5, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'digital', 'key' => 'feature_4'],
            ['value' => 'Creative tech projects', 'type' => 'text', 'sort_order' => 6, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'digital', 'key' => 'cta'],
            ['value' => 'Computer Lab Access', 'type' => 'text', 'sort_order' => 7, 'is_active' => true]
        );
    }


    private function seedAnnouncementsPage()
    {
        $page = 'announcements';

        // Hero Section
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'title'],
            ['value' => 'Announcements & News', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'subtitle'],
            ['value' => 'Stay Updated with Vipers Academy', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'description'],
            ['value' => 'Latest news, updates, and important announcements from our academy.', 'type' => 'text', 'sort_order' => 3, 'is_active' => true]
        );
    }

    private function seedCareersPage()
    {
        $page = 'careers';

        // Hero Section
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'title'],
            ['value' => 'Join Our Team', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'subtitle'],
            ['value' => 'Build Your Career with Vipers Academy', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'description'],
            ['value' => 'We are always looking for passionate individuals to join our team. Explore current openings below.', 'type' => 'text', 'sort_order' => 3, 'is_active' => true]
        );

        // Benefits Section
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'benefits', 'key' => 'title'],
            ['value' => 'Why Join Us?', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'benefits', 'key' => 'benefit_1'],
            ['value' => 'Make a difference in young lives', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'benefits', 'key' => 'benefit_2'],
            ['value' => 'Professional development opportunities', 'type' => 'text', 'sort_order' => 3, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'benefits', 'key' => 'benefit_3'],
            ['value' => 'Friendly and supportive work environment', 'type' => 'text', 'sort_order' => 4, 'is_active' => true]
        );
    }

    private function seedLeadersPage()
    {
        $page = 'leaders';

        // Hero Section
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'title'],
            ['value' => 'Our Leadership', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'subtitle'],
            ['value' => 'Meet the Team Behind Vipers Academy', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'description'],
            ['value' => 'Our dedicated leadership team drives our mission forward.', 'type' => 'text', 'sort_order' => 3, 'is_active' => true]
        );

        // Section Titles
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'titles', 'key' => 'coaches'],
            ['value' => 'Coaching Staff', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'titles', 'key' => 'management'],
            ['value' => 'Management', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'titles', 'key' => 'support'],
            ['value' => 'Support Staff', 'type' => 'text', 'sort_order' => 3, 'is_active' => true]
        );
    }
}
