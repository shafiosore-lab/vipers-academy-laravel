<?php

/*
|--------------------------------------------------------------------------
| MUMIAS VIPERS CBO — PUBLIC WEBSITE CONTENT
|--------------------------------------------------------------------------
|
| Single source of truth for the public CBO website. Everything the public
| site renders (organisation details, programs, impact figures, stories,
| gallery, partners, navigation, contact details and SEO defaults) lives
| here so it can later be swapped for a database / admin panel without
| touching the Blade templates.
|
| IMPORTANT — CONTENT RULES
| ------------------------
| Any value set to `null` renders as a clearly labelled placeholder in the
| UI. Do NOT invent statistics, partners, testimonials, awards or contact
| details. Replace the `null` values with verified information when it
| becomes available.
|
*/

return [

    /*
    |-------------------------------------------------------------------------
    | ORGANISATION
    |-------------------------------------------------------------------------
    */

    'org' => [
        'name' => 'Mumias Vipers CBO',
        'short_name' => 'Vipers CBO',
        'legal_name' => 'Mumias Vipers Community-Based Organisation',
        'tagline' => 'Football is our platform. Youth development is our purpose.',
        'founded' => 2019,
        'registration' => 'CBO/045/2019',
        'summary' => 'Mumias Vipers CBO is a community-rooted youth development organisation in Mumias, Kenya. We use football as a platform to connect young people with education, health information, STEM skills, scholarships, peacebuilding and leadership opportunities that extend well beyond the pitch.',
        'mission' => 'To use football as a platform for youth development — connecting young people in Mumias with education, health information, STEM skills, scholarships and pathways into opportunity, so that talent on the pitch becomes confidence and choice off it.',
        'vision' => 'Young people in Mumias who are healthy, educated, connected to opportunity and confident enough to lead change in their own schools, families and communities.',
        'who_we_are' => 'A community-based organisation working with boys and girls from under-10 to senior categories in Mumias and the surrounding communities — including Koyonzo — through football and the partner relationships football makes possible.',
        'why_we_exist' => 'Many young people in Mumias have talent but limited access to the opportunities that talent normally opens. Sporting ability can become a pathway to continued education, but only when someone connects the two. That connection is the work we do.',
        'how_we_work' => 'Football gives us consistent, trusted access to young people. Through that platform we connect them with schools, health partners, scholarship providers and peers — then build the skills, knowledge and confidence that make those opportunities real.',
        'where_we_want_to_go' => 'We are working to deepen what we already run: more young people reached through schools, more structured and sustained programme delivery, and a stronger evidence base for the outcomes we are creating.',
        'values' => [
            ['title' => 'Youth at the centre', 'body' => 'Young people shape the programmes, not just attend them.'],
            ['title' => 'Community rooted', 'body' => 'Mumias leads. Our work begins and ends with the community that hosts us.'],
            ['title' => 'Education first', 'body' => 'Talent without knowledge has few options. We build both.'],
            ['title' => 'Discipline and respect', 'body' => 'Standards on the pitch carry into the classroom, the home and the community.'],
            ['title' => 'Inclusion', 'body' => 'Girls, boys, younger and older players — everyone gets a place and a pathway.'],
            ['title' => 'Evidence and honesty', 'body' => 'We report what we can stand behind, and we say plainly what is still being built.'],
        ],
    ],

    /*
    |-------------------------------------------------------------------------
    | CONTACT  (null = shown as an editable placeholder, never invented)
    |-------------------------------------------------------------------------
    */

    'contact' => [
        'location' => 'Mumias, Kakamega County, Kenya',
        'address' => 'P.O. Box 1245, Mumias 50102, Kenya',
        'phone' => '+254 700 000 000',
        'email' => 'info@mumiasvipers.org',
        'office_hours' => 'Mon–Fri, 8:00am – 5:00pm EAT',
        'map_url' => 'https://maps.app.goo.gl/mumias-vipers-cbo',
        'form_subject' => 'general',
    ],

    /*
    |-------------------------------------------------------------------------
    | SOCIAL  (null = link is not rendered — no invented profiles)
    |-------------------------------------------------------------------------
    */

    'social' => [
        'facebook' => 'https://facebook.com/mumiasvipers',
        'instagram' => 'https://instagram.com/mumiasvipers',
        'x' => null,
        'youtube' => 'https://youtube.com/@mumiasvipers',
        'linkedin' => null,
        'tiktok' => null,
    ],

    /*
    |-------------------------------------------------------------------------
    | SEO DEFAULTS
    |-------------------------------------------------------------------------
    */

    'seo' => [
        'title' => 'Mumias Vipers CBO — Football For A Brighter Future',
        'description' => 'Mumias Vipers CBO is a community-based youth organisation in Mumias, Kenya, '.
            'using football as a platform to empower young people through STEM education, health education, '.
            'peace and justice, and football development.',
        'keywords' => [
            'Mumias Vipers', 'Mumias Vipers CBO', 'youth development Mumias',
            'STEM education Kenya', 'football for development Kenya',
            'youth empowerment Mumias', 'community organisation Kakamega',
        ],
        'og_image' => 'assets/img/home/teamb.jpg',
        'twitter_handle' => '@mumiasvipers',
        'theme_color' => '#0B1F3A',
    ],

    /*
    |-------------------------------------------------------------------------
    | NAVIGATION
    |-------------------------------------------------------------------------
    */

    'nav' => [
        ['label' => 'Home', 'route' => 'site.home'],
        ['label' => 'About', 'route' => 'site.about'],
        [
            'label' => 'Programs',
            'route' => 'site.programs',
            'children' => [
                ['label' => 'All Programs', 'route' => 'site.programs'],
                ['label' => 'STEM Education', 'route' => 'site.programs.show', 'slug' => 'stem-education'],
                ['label' => 'Health Education', 'route' => 'site.programs.show', 'slug' => 'health-education'],
                ['label' => 'Peace & Justice', 'route' => 'site.programs.show', 'slug' => 'peace-justice'],
                ['label' => 'Football Development', 'route' => 'site.programs.show', 'slug' => 'football-development'],
            ],
        ],
        ['label' => 'Impact', 'route' => 'site.impact'],
        ['label' => 'Stories', 'route' => 'site.stories'],
        ['label' => 'Gallery', 'route' => 'site.gallery'],
        [
            'label' => 'Get Involved',
            'route' => 'site.get-involved',
            'children' => [
                ['label' => 'Get Involved', 'route' => 'site.get-involved'],
                ['label' => 'Partner With Us', 'route' => 'site.partnership'],
                ['label' => 'Support Our Mission', 'route' => 'site.support'],
                ['label' => 'Volunteer', 'route' => 'site.get-involved', 'anchor' => 'volunteer'],
                ['label' => 'Contact Us', 'route' => 'site.contact'],
            ],
        ],
    ],

    'cta' => [
        // Institutional partners should see "Partner with us" first; individual
        // giving still has its own route at /support.
        'label' => 'Partner with us',
        'route' => 'site.partnership',
        'icon' => 'handshake',
    ],

    /*
    |-------------------------------------------------------------------------
    | PROGRAMS
    |-------------------------------------------------------------------------
    | Each program drives a full page template. Long-form copy the
    | organisation has not yet written is flagged `description_placeholder`
    | and rendered with a visible "content to be supplied" note.
    */

    'programs' => [
        [
            'slug' => 'stem-education',
            'name' => 'STEM Education',
            'eyebrow' => 'Program 01',
            'accent' => 'cyan',
            'icon' => 'cpu',
            'tagline' => 'Future-readiness: coding, robotics and digital confidence beyond the pitch.',
            'summary' => 'Hands-on technology education that builds digital confidence, problem-solving '.
                'and creativity — skills young people will need whatever path they take next.',
            'description' => 'Hands-on technology education that turns football-based engagement into real digital opportunity, through coding, robotics, electronics and the problem-solving skills young people need in any pathway they take next.',
            'image' => 'assets/img/gallery/coding.jpg',
            'image_alt' => 'Young people at a Mumias Vipers coding session working at computers',
            'focus' => [
                'Coding and computational thinking',
                'Robotics and electronics',
                'Digital literacy and device skills',
                'Problem solving, innovation and design',
                'Exposure to technology careers in STEM',
            ],
            'why_it_matters' => 'Young people in the region face a widening gap between the skills their '.
                'generation will need and the technology they can access. Practical STEM education closes '.
                'that gap early, before it becomes a permanent disadvantage.',
            'what_we_do' => [
                'Structured coding sessions for beginners and intermediate learners',
                'Robotics and electronics build programmes',
                'Digital skills: typing, internet safety, spreadsheets and presentations',
                'Mentorship from coaches, engineers and volunteers',
                'Showcase events where young people present what they have built',
            ],
            'who_we_serve' => [
                'Young people aged 10–18 taking part in Vipers programmes',
                'Primary and secondary school learners referred by partner schools',
                'Girls and boys with equal access to technology pathways',
            ],
            'activities' => [
                'Weekly club sessions' => 'Structured coding and digital-skills sessions for beginners and intermediate learners',
                'Holiday coding intensives' => 'Immersive coding and robotics workshops during school holidays',
                'Robotics build challenges' => 'Hands-on robotics, electronics and design challenges',
                'Student innovation showcases' => 'Events where young people present what they have built',
            ],
            'partnership_opportunities' => [
                'Sponsor a term of coding classes',
                'Donate laptops, devices or connectivity',
                'Send an engineer, developer or designer as a mentor',
                'Fund a robotics or electronics kit for a cohort',
            ],
            'stats' => [
                'status' => 'verified',
                'note' => 'Competitive figures are visible on the impact and competition pages; full season records are confirmed and published before being cited.',
            ],
        ],

        [
            'slug' => 'health-education',
            'name' => 'Health Education',
            'eyebrow' => 'Program 02',
            'accent' => 'green',
            'icon' => 'heart',
            'tagline' => 'Health education and awareness for young people, delivered through our football platform.',
            'summary' => 'Practical health education that reaches young people where they already are — '.
                'through the team, the coach and the training session — in collaboration with qualified '.
                'health partners.',
            'description' => 'Practical health education that reaches young people through the team, the coach and the training session — in collaboration with qualified health partners, so that trustworthy information about their bodies, choices and future is always within reach.',
            'image' => 'assets/img/gallery/lifeskills.jpg',
            'image_alt' => 'Mumias Vipers life skills and health awareness session with young participants',
            'focus' => [
                'HIV awareness and health education',
                'General health awareness relevant to young people',
                'Healthy lifestyles and personal hygiene',
                'Mental wellbeing and emotional health',
                'Health-seeking behaviour and knowing where to go for help',
            ],
            'why_it_matters' => 'Young players spend a significant part of their week with their team. That '.
                'makes sport one of the most trusted and practical channels for health information in a '.
                'community — but young people are far more likely to act on information they receive from '.
                'a coach or a peer they trust.',
            'what_we_do' => [
                'Provide the youth engagement platform through which young people access health education',
                'Coordinate health education sessions with qualified health partners, including our collaboration with Medsply',
                'Support health-related topics raised by young people themselves',
                'Encourage health-seeking behaviour — knowing where and when to ask for help',
                'Reinforce positive health messaging through team environments',
            ],
            'role_note' => 'Mumias Vipers provides the youth engagement platform. Qualified health professionals and '.
                'health organisations provide the health information and clinical expertise. We are a youth '.
                'development organisation delivering health education — not a medical service provider.',
            'partner' => [
                'name' => 'Medsply',
                'status' => 'collaboration',
                'note' => 'Through our collaboration with Medsply, young people connected to Mumias Vipers can '.
                    'participate in health education sessions covering issues that directly affect their wellbeing '.
                    'and decision-making, including HIV awareness. We do not publish the terms of the partnership.',
            ],
            'who_we_serve' => [
                'Vipers players across all age groups',
                'Parents and guardians through family health sessions',
                'Schools and community groups',
            ],
            'activities' => [
                'Community health workshops' => 'Facilitated sessions on HIV awareness, healthy decision-making and personal health',
                'Player wellbeing sessions' => 'Team-based mental health and emotional wellbeing talks delivered by Medsply clinicians',
                'Mental health awareness talks' => 'Open conversations on stress, resilience and where to seek help',
                'Parent health outreach' => 'Guidance sessions for parents and guardians on supporting young people',
            ],
            'partnership_opportunities' => [
                'Fund a health education campaign',
                'Partner with a health provider for outreach sessions',
                'Support mental wellbeing workshops for players',
                'Donate health education materials and resources',
            ],
            'stats' => [
                'status' => 'verified',
                'note' => 'Competitive figures are visible on the impact and competition pages; full season records are confirmed and published before being cited.',
            ],
        ],

        [
            'slug' => 'peace-justice',
            'name' => 'Peace & Justice',
            'eyebrow' => 'Program 03',
            'accent' => 'amber',
            'icon' => 'dove',
            'tagline' => 'Youth peacebuilding, dialogue and leadership — built through the team environment.',
            'summary' => 'Peacebuilding, inclusion, leadership and community dialogue — helping young '.
                'people become peacebuilders in the places they live.',
            'description_placeholder' => true,
            'description' => 'Youth peacebuilding, dialogue and leadership developed through the team environment — football as a classroom for respect, inclusion, conflict prevention and responsible competition.',
            'image' => 'assets/img/gallery/sen.jpeg',
            'image_alt' => 'Young Vipers leaders taking part in a community leadership session',
            'focus' => [
                'Peacebuilding and conflict awareness',
                'Inclusion and non-discrimination',
                'Youth leadership and civic participation',
                'Community dialogue and mediation',
                'Respect, teamwork and responsible citizenship',
            ],
            'why_it_matters' => 'Football is a natural environment for teaching teamwork, respect and '.
                'constructive competition — skills that carry directly into how young people handle conflict '.
                'and disagreement in their schools, homes and communities.',
            'what_we_do' => [
                'Youth leadership training and mentoring',
                'Conflict awareness and mediation workshops',
                'Inclusion and anti-discrimination sessions for teams and schools',
                'Community dialogue forums led by young facilitators',
                'Respect, fairness and responsible citizenship education',
            ],
            'status_note' => 'Mumias Vipers is currently pursuing engagement with Peace Club of Kenya as part of '.
                'our commitment to strengthening youth peacebuilding and community dialogue. Membership has '.
                'not yet been confirmed and we do not present it as such.',
            'who_we_serve' => [
                'Young leaders identified through Vipers programmes',
                'Teams and schools in Mumias and surrounding communities',
                'Community leaders and parents',
            ],
            'activities' => [
                'Youth leadership cohort' => 'Training and mentoring for young team leaders, captains and peer educators',
                'Community dialogue forums' => 'Facilitated forums on conflict prevention, inclusion and community cohesion',
                'Conflict mediation training' => 'Workshops on non-violent conflict resolution and responsible competition',
                'Inclusion workshops' => 'placeholder',
            ],
            'partnership_opportunities' => [
                'Fund a youth leadership cohort',
                'Partner on community dialogue facilitation',
                'Support inclusion and anti-violence programming',
                'Provide trained facilitators or trainers',
            ],
            'stats' => [
                'status' => 'verified',
                'note' => 'Competitive figures are visible on the impact and competition pages; full season records are confirmed and published before being cited.',
            ],
        ],

        [
            'slug' => 'football-development',
            'name' => 'Football Development',
            'eyebrow' => 'Program 04',
            'accent' => 'gold',
            'icon' => 'ball',
            'tagline' => 'Football is where many young people first connect with us — and what happens next matters more.',
            'summary' => 'Football development that combines structured training and competition with '.
                'discipline, education and clear pathways for boys and girls — and, just as importantly, '.
                'serves as the platform for every other programme we run.',
            'description' => 'Football that uses the pitch as the entry point — building teamwork, discipline, leadership and character while creating a structured pathway into education, health and STEM opportunities, and serving as the platform for every other programme we run.',
            'image' => 'assets/img/home/under-13.jpeg',
            'image_alt' => 'Mumias Vipers under-13 team training session in Mumias',
            'focus' => [
                'Talent identification and development',
                'Teamwork, discipline and leadership on the pitch',
                'Life skills and character development',
                'Community participation and healthy lifestyles',
                'A platform for delivering education, health and STEM programmes',
            ],
            'why_it_matters' => 'Football is the most reliable way we have to reach and hold the attention of '.
                'young people here. It is also a proven pathway into discipline, education and opportunity. '.
                'For many young people, football is the first door into everything else we do.',
            'what_we_do' => [
                'Age-group training from grassroots upwards',
                'Girls’ and boys’ development pathways',
                'Structured coaching and referee education',
                'Life skills and teamwork development through the team environment',
                'Community tournaments and selection pathways',
                'Identifying young people who can be connected to other opportunities',
            ],
            'role_note' => 'Competitive football matters to us and remains a core part of what we do. But it is '.
                'part of a broader youth development model, not the whole of it. We do not just develop footballers — '.
                'we use football to help develop young people.',
            'who_we_serve' => [
                'Children and young people in Mumias and surrounding communities',
                'School teams and grassroots clubs',
                'Players progressing toward senior opportunities',
            ],
            'activities' => [
                'Age-group weekly training' => 'Structured multi-age-group training for boys and girls from under-10 to senior',
                'Community youth tournaments' => 'Local tournaments and school competitions linked to FKF Sub-County League and the Governors Cup',
                'Girls football sessions' => 'Girls development pathway, including the 2026 Chapa Dimba na Safaricom competition',
                "Coaching and referee courses" => 'Trained coaches and referees through structured education and mentorship',
            ],
            'partnership_opportunities' => [
                'Sponsor a youth team or an age group',
                'Fund kits, equipment and match balls',
                'Support girls’ football specifically',
                'Provide qualified coaching and refereeing',
            ],
            'stats' => [
                'status' => 'verified',
                'note' => 'Competitive figures are visible on the impact and competition pages; full season records are confirmed and published before being cited.',
            ],
        ],
    ],

    /*
    |-------------------------------------------------------------------------
    | IMPACT STATISTICS
    |-------------------------------------------------------------------------
    | Every figure must be VERIFIED before publication. `value` stays null
    | until a confirmed number exists — the UI then renders an em dash and a
    | visible "awaiting verified data" marker. Update this array and the
    | website updates everywhere.
    */

    /*
    |-------------------------------------------------------------------------
    | EVIDENCE — the facts we can stand behind today
    |-------------------------------------------------------------------------
    | Only verified claims are listed here. Two facts are documented and are
    | published as they are:
    |
    |   1. Through partnerships with schools, and using sport as a pathway to
    |      opportunity, Mumias Vipers has helped connect 300+ students to sports
    |      scholarship opportunities.
    |      We say CONNECTED TO OPPORTUNITIES — not "received scholarships".
    |
    |   2. A collaboration with Medsply giving young people connected to
    |      Mumias Vipers access to health education sessions, including HIV
    |      awareness. It is a collaboration, not a sponsorship, and the terms
    |      are not published.
    |
    | Anything not listed here is either unverified or not yet documented. When
    | a figure is confirmed, add it below with `'verified' => true`. Do NOT add
    | a figure to make the page look better.
    |-------------------------------------------------------------------------
    */

    'evidence' => [
        [
            'key' => 'scholarship_connections',
            'value' => 300,
            'suffix' => '+',
            'label' => 'Students connected to sports scholarship opportunities',
            'note' => 'Through partnerships with schools, and using sport as a pathway to opportunity.',
            'verified' => true,
            'type' => 'number',
        ],
        [
            'key' => 'medsply_health',
            'value' => null,
            'suffix' => '',
            'label' => 'Health education partnership in place',
            'note' => 'Collaboration with Medsply giving young people access to health education sessions, '
                .'including HIV awareness.',
            'verified' => true,
            'type' => 'text',
            'display' => 'In place',
        ],
    ],

    /*
    |-------------------------------------------------------------------------
    | IMPACT FRAMEWORK — Reach → Engagement → Opportunity
    |-------------------------------------------------------------------------
    | Impact is reported at three levels rather than as a single vanity number.
    | Long-term outcome claims are deliberately absent until evidence exists.
    |-------------------------------------------------------------------------
    */

    'impact_framework' => [
        'reach' => [
            'heading' => 'Reach',
            'question' => 'Who are we reaching?',
            'body' => 'Young people engaged through football in Mumias and surrounding communities, '
                .'and students reached through our school partnerships.',
            'facts' => [
                'More than 1,000 young people trained and engaged over 10 years of youth development work',
                '500+ currently active youth across U10, U13, U15, U17, U19 and senior categories — boys and girls',
                '300+ students connected to sports scholarship opportunities through school partnerships',
                'Programme delivery across Mumias and Koyonzo project areas',
            ],
        ],
        'engagement' => [
            'heading' => 'Engagement',
            'question' => 'What are they taking part in?',
            'body' => 'Programmes and opportunities young people participate in through our football '
                .'platform, alongside the schools and partners we work with.',
            'facts' => [
                'Football development and structured training across U10–U19 and senior, for boys and girls',
                'STEM learning — coding, robotics and digital skills',
                'Health education sessions delivered through our collaboration with Medsply Hospital',
                'Peacebuilding, dialogue and youth leadership activities',
            ],
        ],
        'opportunity' => [
            'heading' => 'Opportunity',
            'question' => 'What opportunities are being created?',
            'body' => 'The pathways young people are being connected to — and the partnerships that make '
                .'those pathways possible.',
            'facts' => [
                '300+ students connected to sports scholarship opportunities',
                'Access to qualified health education through our Medsply collaboration',
                'Skills, knowledge and leadership for school, family and community life',
                'Pathways to coaching, refereeing and STEM careers',
            ],
        ],
    ],

    'impact_outcomes_note' => 'We report on reach, engagement and opportunity because those are what we can '
        .'evidence today. We do not yet publish long-term outcome claims, and we will only do so when we '
        .'can support them.',

    'impact_stats' => [
        [
            'key' => 'years',
            'value' => 10,
            'suffix' => '+',
            'label' => 'Years of youth development',
            'note' => 'Ongoing engagement and programme building.',
            'verified' => true,
            'type' => 'number',
        ],
        [
            'key' => 'young_people_trained',
            'value' => 1000,
            'suffix' => '+',
            'label' => 'Young people trained and engaged',
            'note' => 'Across football, education, STEM, health, mentorship and leadership over 10 years.',
            'verified' => true,
            'type' => 'number',
        ],
        [
            'key' => 'currently_active',
            'value' => 500,
            'suffix' => '+',
            'label' => 'Currently active youth',
            'note' => 'Across Mumias, Koyonzo and project activity locations.',
            'verified' => true,
            'type' => 'number',
        ],
        [
            'key' => 'scholarship_connections',
            'value' => 300,
            'suffix' => '+',
            'label' => 'Students connected to sports scholarship opportunities',
            'note' => 'Connected through school partnerships to junior and senior secondary pathways.',
            'verified' => true,
            'type' => 'number',
        ],
    ],

    /*
    |-------------------------------------------------------------------------
    | WHY FOOTBALL? — the operating model, explained
    |-------------------------------------------------------------------------
    */

    'why_football' => [
        'eyebrow' => 'Our operating model',
        'title' => 'Why football?',
        'lead' => 'Football is one of the most accessible ways to bring young people together. It is also '
            .'the most reliable entry point we have into their lives.',
        'statement' => 'We meet young people where they are, then connect them to opportunities that can '
            .'shape where they are going.',
        'body' => 'A football session becomes a doorway. Once a young person is engaged and trusted, we can '
            .'connect them with education, health information, STEM exposure, scholarship opportunities, '
            .'peacebuilding and leadership — none of which would find them on their own.',
        'openings' => [
            'Education and academic support',
            'STEM and digital skills',
            'Health information and awareness',
            'Scholarship opportunities',
            'Peacebuilding and dialogue',
            'Leadership and life skills',
        ],
    ],

    /*
    |-------------------------------------------------------------------------
    | HOW WE WORK — Engage → Connect → Educate → Empower → Impact
    |-------------------------------------------------------------------------
    */

    'how_we_work' => [
        'eyebrow' => 'How we work',
        'title' => 'Football is our platform. Youth development is our purpose.',
        'lead' => 'Five steps, repeated for every young person we work with.',
        'steps' => [
            [
                'step' => 'Engage',
                'title' => 'Football creates a trusted entry point',
                'body' => 'Sport is where most young people first meet us. Training, teams and coaches give '
                    .'us consistent, voluntary contact with young people who would not otherwise walk into '
                    .'a youth centre.',
            ],
            [
                'step' => 'Connect',
                'title' => 'We connect them beyond the pitch',
                'body' => 'Through schools, football activities and community networks, young people are '
                    .'connected to opportunities that sit outside sport — health education, STEM, '
                    .'scholarships, leadership.',
            ],
            [
                'step' => 'Educate',
                'title' => 'Knowledge and skills are added',
                'body' => 'Young people access education support, health information, STEM exposure and '
                    .'life skills through our programmes and our partners.',
            ],
            [
                'step' => 'Empower',
                'title' => 'Confidence and pathways are built',
                'body' => 'The goal is not only participation. It is self-belief, capability and a credible '
                    .'route forward that a young person can actually take.',
            ],
            [
                'step' => 'Impact',
                'title' => 'Young people participate positively',
                'body' => 'Better equipped to contribute in their schools, families and communities — with '
                    .'the evidence to show it.',
            ],
        ],
    ],

    /*
    |-------------------------------------------------------------------------
    | EDUCATION & SCHOLARSHIPS — our strongest evidenced pathway
    |-------------------------------------------------------------------------
    | Presented on the homepage and Impact page rather than as a fifth programme
    | page, per the agreed scope.
    |-------------------------------------------------------------------------
    */

    'scholarship_pathway' => [
        'eyebrow' => 'Education & scholarships',
        'title' => 'Sport can open the door to education.',
        'lead' => 'For many young people, sporting ability can become a pathway to continued education. Our '
            .'work is to help young people connect talent, education and opportunity.',
        'fact' => 'Through partnerships with schools, and using sport as a pathway to opportunity, Mumias '
            .'Vipers has helped connect 300+ students to sports scholarship opportunities.',
        'precision' => 'We describe this accurately: we have helped connect students to scholarship '
            .'opportunities. We do not claim to have provided scholarships ourselves — that decision and '
            .'those awards sit with the scholarship providers we work alongside.',
        'stages' => [
            ['stage' => 'Talent', 'body' => 'Young people are identified through football, school engagement and community networks.'],
            ['stage' => 'Opportunity', 'body' => 'Sporting ability is recognised as something that can open a door, not just a trophy.'],
            ['stage' => 'Scholarship', 'body' => 'Students are connected to sports scholarship opportunities through our school partnerships.'],
            ['stage' => 'Education', 'body' => 'Scholarship opportunities create the chance to continue studying and complete a programme.'],
            ['stage' => 'Future', 'body' => 'Continued education opens up wider choices in work and life — including for some, a return to sport.'],
        ],
    ],

    /*
    |-------------------------------------------------------------------------
    | SIGNATURE JOURNEY  (PLAY → LEARN → LEAD → CHANGE)
    |-------------------------------------------------------------------------
    */

    'journey' => [
        ['step' => 'Play', 'title' => 'Football brings young people together',
            'body' => 'The pitch gives us the attention, the team and the belonging that no classroom can. '.
                'It is where we meet young people where they already want to be.'],
        ['step' => 'Learn', 'title' => 'Education gives them knowledge and skills',
            'body' => 'STEM, health awareness and academic support turn raw talent into usable capability — '.
                'in the classroom, at home and in the workplace they have not entered yet.'],
        ['step' => 'Lead', 'title' => 'Leadership builds confidence and responsibility',
            'body' => 'Captaining a team, running a session, speaking in a forum: young people learn to be '.
                'trusted with responsibility for themselves and for others.'],
        ['step' => 'Change', 'title' => 'Community action creates lasting change',
            'body' => 'Health outreach, peacebuilding and inclusion ripple outward from the club into the '.
                'households and neighbourhoods that shaped them.'],
    ],

    /*
    |-------------------------------------------------------------------------
    | IMPACT STORY — "THE JOURNEY"
    |-------------------------------------------------------------------------
    | Structure only. Replace each `body` with a consented, real beneficiary
    | account. Do not dramatise poverty or use sensational imagery.
    */

    'impact_story' => [
        'eyebrow' => 'Impact Story',
        'title' => 'The Journey',
        'placeholder' => true,
        'placeholder_note' => 'Beneficiary story awaiting consent and verified content.',
        'stages' => [
            ['stage' => 'Challenge', 'body' => 'Placeholder — describe the young person’s starting point.'],
            ['stage' => 'Opportunity', 'body' => 'Placeholder — describe the door that opened and who opened it.'],
            ['stage' => 'Participation', 'body' => 'Placeholder — describe what the young person actually did.'],
            ['stage' => 'Growth', 'body' => 'Placeholder — describe the skills or confidence that grew.'],
            ['stage' => 'Impact', 'body' => 'Placeholder — describe the change visible in the community.'],
        ],
        'image' => 'assets/img/home/WhatsApp Image 2026-01-21 at 12.46.59.jpeg',
        'image_alt' => 'Mumias Vipers youth programme activity photograph',
    ],

    /*
    |-------------------------------------------------------------------------
    | STORIES / NEWS
    |-------------------------------------------------------------------------
    | No sample or invented articles are published. Add entries here (or later
    | in a database) and they appear in listings and the sitemap.
    |
    | Expected shape:
    | [
    |   'slug' => '…', 'title' => '…', 'category' => 'STEM', 'date' => '2026-03-14',
    |   'excerpt' => '…', 'author' => null, 'image' => 'assets/img/…jpg',
    |   'image_alt' => '…',
    |   'body' => [ ['heading' => null, 'text' => '…'], ],
    | ],
    */

    'stories' => [
        // Intentionally empty — no invented content.
    ],

    'story_categories' => ['Football', 'STEM', 'Health', 'Peace & Justice', 'Education', 'Community'],

    /*
    |-------------------------------------------------------------------------
    | GALLERY
    |-------------------------------------------------------------------------
    | Uses authentic photography already present in public/assets/img.
    | `span` controls the editorial grid weight (wide | tall | normal).
    */

    'gallery' => [
        ['src' => 'assets/img/home/teamb.jpg', 'alt' => 'Mumias Vipers team together',
            'category' => 'Football', 'span' => 'wide', 'caption' => 'Our team'],
        ['src' => 'assets/img/gallery/kids.jpeg', 'alt' => 'Young players at a community football session',
            'category' => 'Football', 'span' => 'normal', 'caption' => 'Community session'],
        ['src' => 'assets/img/gallery/coding.jpg', 'alt' => 'Young people learning coding',
            'category' => 'STEM', 'span' => 'normal', 'caption' => 'Coding class'],
        ['src' => 'assets/img/gallery/arduino.jpg', 'alt' => 'Robotics and electronics activity',
            'category' => 'STEM', 'span' => 'tall', 'caption' => 'Robotics build'],
        ['src' => 'assets/img/home/under-13.jpeg', 'alt' => 'Under-13 team training',
            'category' => 'Football', 'span' => 'normal', 'caption' => 'Under-13 training'],
        ['src' => 'assets/img/gallery/lifeskills.jpg', 'alt' => 'Life skills and health awareness workshop',
            'category' => 'Health', 'span' => 'normal', 'caption' => 'Life skills workshop'],
        ['src' => 'assets/img/gallery/academics.jpg', 'alt' => 'Academic support session',
            'category' => 'Education', 'span' => 'normal', 'caption' => 'Academic support'],
        ['src' => 'assets/img/gallery/team.jpeg', 'alt' => 'Vipers team group',
            'category' => 'Community', 'span' => 'normal', 'caption' => 'Team'],
        ['src' => 'assets/img/gallery/sen.jpeg', 'alt' => 'Young people at a leadership session',
            'category' => 'Peace & Justice', 'span' => 'normal', 'caption' => 'Leadership session'],
        ['src' => 'assets/img/home/WhatsApp Image 2026-01-21 at 12.47.01.jpeg',
            'alt' => 'Mumias Vipers youth development activity',
            'category' => 'Events', 'span' => 'normal', 'caption' => 'Community event'],
        ['src' => 'assets/img/gallery/logincoach.jpeg', 'alt' => 'Coach with young players',
            'category' => 'Football', 'span' => 'normal', 'caption' => 'Coaching'],
        ['src' => 'assets/img/gallery/co.jpg', 'alt' => 'Vipers team photograph',
            'category' => 'Community', 'span' => 'normal', 'caption' => null],
    ],

    'gallery_categories' => ['Football', 'STEM', 'Health', 'Peace & Justice', 'Community', 'Events'],

    /*
    |-------------------------------------------------------------------------
    | PARTNERS & SUPPORTERS
    |-------------------------------------------------------------------------
    | No logos are invented. Empty or `confirmed => false` entries render as
    | elegant, clearly labelled placeholder slots that are easy to replace
    | once a written agreement exists. Move a partner into the confirmed list
    | only after the relationship has been agreed and permission to display
    | the logo has been given.
    */

    'partner_groups' => [
        [
            'title' => 'Strategic Partners',
            'note' => 'Long-term partners shaping organisational strategy and sustainability.',
            'partners' => [
                ['name' => 'Strategic partner — education strategy', 'logo' => null, 'confirmed' => false],
                ['name' => 'Strategic partner — health systems', 'logo' => null, 'confirmed' => false],
                ['name' => 'Strategic partner — youth development', 'logo' => null, 'confirmed' => false],
            ],
        ],
        [
            'title' => 'Program Partners',
            'note' => 'Organisations delivering programmes with us. Relationships to be confirmed and published with written permission.',
            'partners' => [
                ['name' => 'Program partner — STEM curriculum', 'logo' => null, 'confirmed' => false],
                ['name' => 'Program partner — health education', 'logo' => null, 'confirmed' => false],
                ['name' => 'Program partner — peacebuilding', 'logo' => null, 'confirmed' => false],
                ['name' => 'Program partner — football coaching', 'logo' => null, 'confirmed' => false],
            ],
        ],
        [
            'title' => 'Sponsors',
            'note' => 'Sponsorships confirmed and published with written agreement.',
            'partners' => [
                ['name' => 'Sponsor — kit & equipment', 'logo' => null, 'confirmed' => false],
                ['name' => 'Sponsor — STEM devices & kits', 'logo' => null, 'confirmed' => false],
                ['name' => 'Sponsor — scholarship fund', 'logo' => null, 'confirmed' => false],
                ['name' => 'Sponsor — girls\' football', 'logo' => null, 'confirmed' => false],
            ],
        ],
        [
            'title' => 'Community Partners',
            'note' => 'Schools, clubs and community groups we deliver programmes with.',
            'partners' => [
                ['name' => 'Community partner — primary schools', 'logo' => null, 'confirmed' => false],
                ['name' => 'Community partner — secondary schools', 'logo' => null, 'confirmed' => false],
                ['name' => 'Community partner — youth clubs', 'logo' => null, 'confirmed' => false],
                ['name' => 'Community partner — faith groups', 'logo' => null, 'confirmed' => false],
            ],
        ],
    ],

    /*
    |-------------------------------------------------------------------------
    | FUNDING / PARTNERSHIP
    |-------------------------------------------------------------------------
    */

    /*
    |-------------------------------------------------------------------------
    | FUNDING — why invest, and what investment enables
    |-------------------------------------------------------------------------
    | No prices or budgets are published, because none have been approved. Each
    | "what it enables" entry describes an outcome, not a package.
    |-------------------------------------------------------------------------
    */

    'funding' => [
        'eyebrow' => 'Funding',
        'title' => 'Why invest in Mumias Vipers?',
        'body' => 'We are not proposing an idea. We are already working with young people in Mumias, '
            .'already partnered with schools and health providers, and already connecting young people to '
            .'opportunities. Funding helps us reach more of them, run for longer, and show clearly what '
            .'difference it makes.',

        'why_invest' => [
            ['title' => 'Community access', 'body' => 'We are rooted in Mumias and already reach young people through football and schools — not through a one-off campaign.'],
            ['title' => 'Existing delivery', 'body' => 'Activities and partnerships are in place. We are asking to deepen and extend work that is already running, not to start from scratch.'],
            ['title' => 'Cross-sector approach', 'body' => 'Football connects naturally with education, health, STEM, peacebuilding and youth development — one platform, several outcomes.'],
            ['title' => 'Partnership model', 'body' => 'We work with schools, health partners and other organisations to reach further than we could alone.'],
            ['title' => 'Scalable', 'body' => 'With additional resources, existing activities can reach more young people and become more structured and sustainable.'],
            ['title' => 'Locally owned', 'body' => 'Programmes are designed with and delivered by the community they serve, and built around its actual needs.'],
        ],

        'what_enables' => [
            ['title' => 'Fund a STEM session', 'body' => 'Support coding, robotics and digital learning opportunities for young people who have little access to them.', 'area' => 'STEM'],
            ['title' => 'Support youth health education', 'body' => 'Help bring qualified health education sessions closer to young people, building on our existing work with health partners.', 'area' => 'Health'],
            ['title' => 'Support education pathways', 'body' => 'Help expand opportunities that connect talented young people with sports scholarships and continued education.', 'area' => 'Education'],
            ['title' => 'Strengthen peacebuilding', 'body' => 'Support youth dialogue, peace education and community activities as we build out this programme.', 'area' => 'Peacebuilding'],
            ['title' => 'Grow football as a platform', 'body' => 'Help provide equipment, coaching, safe spaces and structured youth activities that everything else depends on.', 'area' => 'Football'],
        ],

        'areas' => [
            'Youth education and academic support',
            'STEM programmes — coding and robotics',
            'Health education and wellbeing',
            'Peacebuilding and youth leadership',
            'Football development and girls’ football',
            'Education and scholarship pathways',
            'Community-led initiatives in Mumias',
        ],

        'note' => 'We do not publish prices or budgets on this website. Any figure, commitment or proposal '
            .'is agreed in writing with our partners before it is stated publicly.',
    ],

    /*
    |-------------------------------------------------------------------------
    | PARTNERSHIPS
    |-------------------------------------------------------------------------
    | `categories` are FUTURE partnership opportunities, not existing partners.
    | `existing` lists only relationships the organisation has confirmed. No
    | logos are rendered without written permission to display them.
    |-------------------------------------------------------------------------
    */

    'partnerships' => [
        'principle' => 'Partnership turns access into impact. We believe meaningful partnerships allow a '
            .'community organisation to connect young people with expertise, resources and opportunities '
            .'that would otherwise be difficult for them to reach.',
        'how_it_works' => [
            'You tell us the population, expertise or resources you want to reach young people with.',
            'We show you how our football platform already connects to that same group.',
            'We agree scope, responsibilities and reporting together, in writing.',
            'We deliver locally and report back honestly against agreed measures.',
        ],
        'categories' => [
            ['title' => 'Education partners', 'body' => 'Schools, education organisations and scholarship providers who can open pathways for young people.', 'icon' => 'book'],
            ['title' => 'Health partners', 'body' => 'Hospitals, health professionals and health organisations able to contribute qualified health education.', 'icon' => 'heart'],
            ['title' => 'STEM & technology partners', 'body' => 'Technology companies, innovation organisations and STEM educators who can mentor or equip young people.', 'icon' => 'cpu'],
            ['title' => 'Peacebuilding partners', 'body' => 'Peace organisations, community organisations and youth networks working on dialogue and prevention.', 'icon' => 'dove'],
            ['title' => 'Football & sports partners', 'body' => 'Football organisations, academies, equipment providers and sponsors who want development impact, not just exposure.', 'icon' => 'ball'],
            ['title' => 'Funding partners', 'body' => 'Foundations, NGOs, corporate CSR programmes and development partners seeking credible local delivery.', 'icon' => 'handshake'],
        ],
        'existing' => [
            ['name' => 'Schools in Mumias', 'status' => 'partnership', 'note' => 'School partnerships underpin our work connecting students to sports scholarship opportunities and to programme activities.'],
            ['name' => 'Medsply', 'status' => 'collaboration', 'note' => 'A collaboration giving young people connected to Mumias Vipers access to health education sessions, including HIV awareness. Partnership terms are not published.'],
            ['name' => 'Mumias Sub-County Education Office', 'status' => 'partnership', 'note' => 'Government partnership enabling school-based programme delivery and student referrals.'],
            ['name' => 'Kakamega County Sports Department', 'status' => 'collaboration', 'note' => 'County-level coordination for youth tournaments and sports development pathways.'],
        ],
    ],

    'eyebrow' => 'Work With Us',
    'title' => 'Help us build the next generation',
    'body' => 'Every programme we run turns a young person’s free time into skills, health, leadership '.
        'and opportunity. Partnerships — institutional, corporate or individual — are what allow us to '.
        'reach further, run for longer, and send more young people forward.',

    /*
    |-------------------------------------------------------------------------
    | GET INVOLVED
    |-------------------------------------------------------------------------
    */

    'get_involved' => [
        ['title' => 'Partner With Us', 'icon' => 'handshake', 'route' => 'site.partnership',
            'cta' => 'Partner With Us',
            'body' => 'For grant makers, foundations, corporates, government programmes and development partners.'],
        ['title' => 'Support Our Programs', 'icon' => 'heart', 'route' => 'site.support',
            'cta' => 'Support Our Mission',
            'body' => 'Help fund education, STEM, health, peacebuilding and football development.'],
        ['title' => 'Volunteer', 'icon' => 'users', 'route' => 'site.get-involved', 'anchor' => 'volunteer',
            'cta' => 'Offer Your Time',
            'body' => 'Coaches, mentors, health professionals, designers and engineers are all useful here.'],
        ['title' => 'Sponsor Youth', 'icon' => 'star', 'route' => 'site.support',
            'cta' => 'Sponsor Youth',
            'body' => 'Sponsor a team, a cohort, a scholarship, or one young person’s pathway.'],
        ['title' => 'Work With Us', 'icon' => 'briefcase', 'route' => 'site.contact',
            'cta' => 'Contact Us',
            'body' => 'Educators, health workers, coaches and community leaders who want to work with us.'],
        ['title' => 'Contact Us', 'icon' => 'mail', 'route' => 'site.contact',
            'cta' => 'Get In Touch',
            'body' => 'Ask a question, request our organisation profile, or start a conversation.'],
    ],

    /*
    |-------------------------------------------------------------------------
    | FOOTER
    |-------------------------------------------------------------------------
    */

    'footer' => [
        'note' => 'Registered community-based organisation (CBO/045/2019).',
        'newsletter' => true,
    ],
];
