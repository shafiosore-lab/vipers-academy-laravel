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
            ['value' => '"Thanks to Mumias Vipers Academy, I received a full sports scholarship to secondary school. Football opened doors to education I never thought possible."', 'type' => 'textarea', 'sort_order' => 5, 'is_active' => true]
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
            ['value' => 'About Mumias Vipers Football Academy', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'subtitle'],
            ['value' => 'A 100% FREE Not-for-Profit Youth Development Initiative Combining Sports, STEM & Education', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
        );

        // Story Section
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'story', 'key' => 'title'],
            ['value' => 'Our Story', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'story', 'key' => 'content'],
            ['value' => 'Founded in 2016, Mumias Vipers Football Academy is a community-based youth development initiative that uses football as a catalyst for holistic development. We provide 100% FREE programs in football, STEM education (powered by E.N.G.I.N.E USA), academic support, and life skills to empower underserved youth in Kenya. Our ESG-aligned model focuses on social impact, education access, and youth empowerment without any fees or costs to participants.', 'type' => 'textarea', 'sort_order' => 2, 'is_active' => true]
        );

        // Mission Section
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'mission', 'key' => 'title'],
            ['value' => 'Our Mission', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'mission', 'key' => 'content'],
            ['value' => 'To empower underserved youth through 100% FREE integrated programs in sports, STEM, and education that foster talent development, discipline, leadership, and academic excellence while creating safe, inclusive spaces for holistic growth.', 'type' => 'textarea', 'sort_order' => 2, 'is_active' => true]
        );

        // Vision Section
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'vision', 'key' => 'title'],
            ['value' => 'Our Vision', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'vision', 'key' => 'content'],
            ['value' => "To be Kenya's leading ESG-aligned youth development initiative that transforms lives through accessible sports, STEM, and education programs, creating pathways to opportunity for underserved communities while promoting gender equality and sustainable community impact.", 'type' => 'textarea', 'sort_order' => 2, 'is_active' => true]
        );

        // Core Values - ESG Aligned
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'values', 'key' => 'title'],
            ['value' => 'Our ESG-Aligned Core Values', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'values', 'key' => 'social_impact'],
            ['value' => json_encode(['icon' => '💙', 'title' => 'Social Impact', 'description' => 'Creating measurable positive change in underserved communities through youth empowerment and education access.']), 'type' => 'json', 'sort_order' => 2, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'values', 'key' => 'education_access'],
            ['value' => json_encode(['icon' => '📚', 'title' => 'Education Access', 'description' => 'Ensuring every child has access to quality education and learning opportunities regardless of background.']), 'type' => 'json', 'sort_order' => 3, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'values', 'key' => 'youth_empowerment'],
            ['value' => json_encode(['icon' => '🚀', 'title' => 'Youth Empowerment', 'description' => 'Fostering leadership, confidence, and life skills that enable youth to become agents of change in their communities.']), 'type' => 'json', 'sort_order' => 4, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'values', 'key' => 'inclusion_equality'],
            ['value' => json_encode(['icon' => '🤝', 'title' => 'Inclusion & Equality', 'description' => 'Promoting gender equality and equal access to sports and STEM opportunities for all youth, especially girls.']), 'type' => 'json', 'sort_order' => 5, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'values', 'key' => 'sustainable_growth'],
            ['value' => json_encode(['icon' => '🌱', 'title' => 'Sustainable Growth', 'description' => 'Building structured, governed programs that create lasting impact and can scale to benefit more communities over time.']), 'type' => 'json', 'sort_order' => 6, 'is_active' => true]
        );

        // Impact Stats - Updated for ESG focus
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stats', 'key' => 'title'],
            ['value' => 'Our ESG Impact', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
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
            ['value' => 'Youth Empowered', 'type' => 'text', 'sort_order' => 5, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stats', 'key' => 'stat_3_value'],
            ['value' => '50+', 'type' => 'text', 'sort_order' => 6, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stats', 'key' => 'stat_3_label'],
            ['value' => 'Girls Participating', 'type' => 'text', 'sort_order' => 7, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stats', 'key' => 'stat_4_value'],
            ['value' => '100%', 'type' => 'text', 'sort_order' => 8, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stats', 'key' => 'stat_4_label'],
            ['value' => 'Programs FREE of Charge', 'type' => 'text', 'sort_order' => 9, 'is_active' => true]
        );
    }

    private function seedProgramsPage()
    {
        $page = 'programs';

        // Hero Section
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'title'],
            ['value' => 'Our ESG-Aligned Programs', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'subtitle'],
            ['value' => 'Sports | STEM | Education | Mentorship | Empowerment', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'hero', 'key' => 'description'],
            ['value' => 'All programs are 100% FREE - Empowering youth through integrated sports, STEM, and education initiatives', 'type' => 'text', 'sort_order' => 3, 'is_active' => true]
        );

        // Sports for Development Program
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'sports_development', 'key' => 'title'],
            ['value' => 'Sports for Development Program', 'type' => 'text', 'sort_order' => 1, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'sports_development', 'key' => 'subtitle'],
            ['value' => 'Football Training & Life Skills', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'sports_development', 'key' => 'feature_1'],
            ['value' => 'Professional football training', 'type' => 'text', 'sort_order' => 3, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'sports_development', 'key' => 'feature_2'],
            ['value' => 'Discipline, teamwork & leadership development', 'type' => 'text', 'sort_order' => 4, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'sports_development', 'key' => 'feature_3'],
            ['value' => 'Talent identification & scholarship pathways', 'type' => 'text', 'sort_order' => 5, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'sports_development', 'key' => 'feature_4'],
            ['value' => 'Age-appropriate groups & tournament participation', 'type' => 'text', 'sort_order' => 6, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'sports_development', 'key' => 'cta'],
            ['value' => 'Join Free Training', 'type' => 'text', 'sort_order' => 7, 'is_active' => true]
        );

        // STEM & Digital Skills Program (Powered by E.N.G.I.N.E USA)
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stem_digital', 'key' => 'title'],
            ['value' => 'STEM & Digital Skills Program', 'type' => 'text', 'sort_order' => 8, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stem_digital', 'key' => 'subtitle'],
            ['value' => 'Powered by E.N.G.I.N.E USA', 'type' => 'text', 'sort_order' => 9, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stem_digital', 'key' => 'feature_1'],
            ['value' => 'Coding basics & programming fundamentals', 'type' => 'text', 'sort_order' => 10, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stem_digital', 'key' => 'feature_2'],
            ['value' => 'Digital literacy & computer skills', 'type' => 'text', 'sort_order' => 11, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stem_digital', 'key' => 'feature_3'],
            ['value' => 'Innovation & problem-solving workshops', 'type' => 'text', 'sort_order' => 12, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stem_digital', 'key' => 'feature_4'],
            ['value' => 'Bridging the digital divide in underserved communities', 'type' => 'text', 'sort_order' => 13, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'stem_digital', 'key' => 'cta'],
            ['value' => 'Learn Digital Skills Free', 'type' => 'text', 'sort_order' => 14, 'is_active' => true]
        );

        // Youth Mentorship & Mental Wellbeing Program
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'mentorship', 'key' => 'title'],
            ['value' => 'Youth Mentorship & Mental Wellbeing Program', 'type' => 'text', 'sort_order' => 15, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'mentorship', 'key' => 'subtitle'],
            ['value' => 'Life Skills & Emotional Support', 'type' => 'text', 'sort_order' => 16, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'mentorship', 'key' => 'feature_1'],
            ['value' => 'Structured mentorship sessions', 'type' => 'text', 'sort_order' => 17, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'mentorship', 'key' => 'feature_2'],
            ['value' => 'Life skills development workshops', 'type' => 'text', 'sort_order' => 18, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'mentorship', 'key' => 'feature_3'],
            ['value' => 'Mental health awareness & support', 'type' => 'text', 'sort_order' => 19, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'mentorship', 'key' => 'feature_4'],
            ['value' => 'Safe youth development spaces & counseling', 'type' => 'text', 'sort_order' => 20, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'mentorship', 'key' => 'cta'],
            ['value' => 'Join Mentorship Program', 'type' => 'text', 'sort_order' => 21, 'is_active' => true]
        );

        // Academic & Exposure Program
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'academic_exposure', 'key' => 'title'],
            ['value' => 'Academic & Exposure Program', 'type' => 'text', 'sort_order' => 22, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'academic_exposure', 'key' => 'subtitle'],
            ['value' => 'Education Support & Career Pathways', 'type' => 'text', 'sort_order' => 23, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'academic_exposure', 'key' => 'feature_1'],
            ['value' => 'Academic guidance & tutoring support', 'type' => 'text', 'sort_order' => 24, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'academic_exposure', 'key' => 'feature_2'],
            ['value' => 'University & college exposure visits', 'type' => 'text', 'sort_order' => 25, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'academic_exposure', 'key' => 'feature_3'],
            ['value' => 'Career awareness & job readiness training', 'type' => 'text', 'sort_order' => 26, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'academic_exposure', 'key' => 'feature_4'],
            ['value' => 'Study skills development & exam preparation', 'type' => 'text', 'sort_order' => 27, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'academic_exposure', 'key' => 'cta'],
            ['value' => 'Access Academic Support', 'type' => 'text', 'sort_order' => 28, 'is_active' => true]
        );

        // Girls Empowerment Program
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'girls_empowerment', 'key' => 'title'],
            ['value' => 'Girls Empowerment Program', 'type' => 'text', 'sort_order' => 29, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'girls_empowerment', 'key' => 'subtitle'],
            ['value' => 'Equal Access & Leadership Development', 'type' => 'text', 'sort_order' => 30, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'girls_empowerment', 'key' => 'feature_1'],
            ['value' => 'Equal access to sports & STEM opportunities', 'type' => 'text', 'sort_order' => 31, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'girls_empowerment', 'key' => 'feature_2'],
            ['value' => 'Girls-only training sessions & mentorship', 'type' => 'text', 'sort_order' => 32, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'girls_empowerment', 'key' => 'feature_3'],
            ['value' => 'Leadership development & confidence building', 'type' => 'text', 'sort_order' => 33, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'girls_empowerment', 'key' => 'feature_4'],
            ['value' => 'Safe spaces & female role models', 'type' => 'text', 'sort_order' => 34, 'is_active' => true]
        );
        PageContent::updateOrCreate(
            ['page' => $page, 'section' => 'girls_empowerment', 'key' => 'cta'],
            ['value' => 'Join Girls Empowerment', 'type' => 'text', 'sort_order' => 35, 'is_active' => true]
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
            ['value' => 'Stay Updated with Mumias Vipers Academy', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
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
            ['value' => 'Build Your Career with Mumias Vipers Academy', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
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
            ['value' => 'Meet the Team Behind Mumias Vipers Academy', 'type' => 'text', 'sort_order' => 2, 'is_active' => true]
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
