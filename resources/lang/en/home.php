<?php

return [

    // ===== HERO (you already have keys; keeping parity) =====
    'hero' => [
        'badge'    => 'Learn, Teach, and Exchange Skills',
        'title'    => 'Skills Hub',
        'subtitle' => 'A community platform to exchange skills without barriers',
        'search'   => [
            'aria'        => 'Search for a skill',
            'placeholder' => 'Search a skill (e.g., Programming, English...)',
        ],
        'select'   => [
            'aria'       => 'Filter by type',
            'all'        => 'All types',
            'language'   => 'Languages',
            'tech'       => 'Tech',
            'music'      => 'Music',
            'art'        => 'Art',
            'academic'   => 'Academic',
        ],
        'quick'    => [ 'aria' => 'Quick search suggestions' ],
        'stats'    => [
            'popular'          => 'Popular skills',
            'skills_available' => 'skills available',
            'sessions_done'    => 'sessions done',
        ],
        'search' => [
            'help' => 'Type a skill and press search, or pick from suggestions.'
        ],
    ],

    // ===== WHY =====
    'why' => [
        'title'    => 'Why Skills Hub?',
        'subtitle' => 'We make skill learning easy, community-driven, and sustainable.',
        'items' => [
            [
                'icon'  => 'bi-arrow-left-right',
                'title' => 'Skill Exchange',
                'text'  => 'Teach what you know and learn what you need through fair exchange.',
            ],
            [
                'icon'  => 'bi-cash-coin',
                'title' => 'Free or Low Cost',
                'text'  => 'Accessible to everyone without financial barriers.',
            ],
            [
                'icon'  => 'bi-people-fill',
                'title' => 'Community-Driven',
                'text'  => 'Join an active community that shares your passion for learning.',
            ],
            [
                'icon'  => 'bi-patch-check-fill',
                'title' => 'Skill Verification',
                'text'  => 'Earn badges that validate your skills and increase trust.',
            ],
            [
                'icon'  => 'bi-calendar-check',
                'title' => 'Flexible Scheduling',
                'text'  => 'Coordinate sessions easily, in-person or remote.',
            ],
            [
                'icon'  => 'bi-mortarboard-fill',
                'title' => 'Global Reach',
                'text'  => 'Connect with learners worldwide and exchange across cultures.',
            ],
        ],
    ],

    // ===== HOW =====
    'how' => [
        'title'    => 'How does it work?',
        'subtitle' => 'Start in three simple steps and exchange skills today.',
        'steps' => [
            [ 'num' => '1', 'title' => 'Create your profile', 'text' => 'List skills you can teach and what you want to learn.' ],
            [ 'num' => '2', 'title' => 'Connect with others',  'text' => 'Find matching partners and start chatting.' ],
            [ 'num' => '3', 'title' => 'Start exchanging',     'text' => 'Schedule and run sessions in person or online.' ],
        ],
    ],

    // ===== COMMUNITY =====
    'community' => [
        'image' => 'https://www.en.agraria.unina.it/images/2023/05/31/courses4.png',
        'alt'   => 'Skills Hub Community',
        'title' => 'Join our growing community',
        'lead'  => 'Skills Hub is more than a platform — it’s a community of passionate learners and teachers.',
        'features' => [
            'Connect with like-minded people who share your interests.',
            'Join community events and group learning sessions.',
            'Build meaningful relationships while leveling up your skills.',
        ],
    ],

    // ===== FAQ =====
    'faq' => [
        'title'    => 'Frequently Asked Questions',
        'subtitle' => 'Answers to common questions about Skills Hub',
        'items' => [
            [
                'q' => 'How does skill exchange work?',
                'a' => 'We match users based on complementary skills; then you schedule in-person or remote sessions.',
            ],
            [
                'q' => 'Is Skills Hub completely free?',
                'a' => 'Exchanges are fully free. Optional advanced features are available via a premium plan.',
            ],
            [
                'q' => 'How do I earn and redeem points?',
                'a' => 'Earn points through exchanges and activities, redeem them for perks like badges, discounts, and premium access.',
            ],
            [
                'q' => 'How are skills verified?',
                'a' => 'Peer reviews, ratings, and verification badges based on your performance and feedback.',
            ],
            [
                'q' => 'When is the launch?',
                'a' => 'We’re in development after winning a 2024 hackathon — join the waitlist for early access.',
            ],
            [
                'q' => 'What kinds of skills can be exchanged?',
                'a' => 'Almost anything — languages, tech, music, arts, cooking, fitness, academics, and more.',
            ],
        ],
    ],

];
