<?php

declare(strict_types=1);

/**
 * Full dummy-data seeder for the LGU Past Papers & Lectures Portal.
 *
 * Run migrate.php first, then this file, from the project root:
 *   php database/migrate.php
 *   php database/seed.php
 *
 * The script is idempotent: it checks for existing rows before inserting,
 * so it is safe to run more than once (e.g. after a fresh migration).
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$db = new \App\Core\Database();

echo "Seeding dummy data...\n";

/* ---------------------------------------------------------------------
 | Admin user
 * ------------------------------------------------------------------- */
$existingAdmin = $db->fetch(
    "SELECT id FROM admins WHERE email = ?",
    ['admin@lgu.local']
);

if (!$existingAdmin) {
    $seed = require __DIR__ . '/seeders/AdminSeeder.php';

    $db->execute(
        "INSERT INTO admins (name, email, password_hash, role) VALUES (?, ?, ?, ?)",
        [$seed['name'], $seed['email'], $seed['password_hash'], $seed['role']]
    );
    echo "  - Admin created: admin@lgu.local / admin123\n";
} else {
    echo "  - Admin already exists, skipping.\n";
}

/* ---------------------------------------------------------------------
 | Departments + Sub-departments + Papers
 * ------------------------------------------------------------------- */
$departments = [
    [
        'name' => 'Department of Computer Science',
        'slug' => 'computer-science',
        'description' => 'Undergraduate and graduate programs in Computer Science, Software Engineering, and Information Technology at Lahore Garrison University.',
        'subs' => [
            ['name' => 'BS Computer Science', 'slug' => 'bs-computer-science'],
            ['name' => 'BS Software Engineering', 'slug' => 'bs-software-engineering'],
        ],
    ],
    [
        'name' => 'Department of Management Sciences',
        'slug' => 'management-sciences',
        'description' => 'Business and management education preparing future leaders across finance, marketing, and HR.',
        'subs' => [
            ['name' => 'BBA', 'slug' => 'bba'],
            ['name' => 'MBA', 'slug' => 'mba'],
        ],
    ],
    [
        'name' => 'Department of Psychology',
        'slug' => 'psychology',
        'description' => 'Programs covering clinical, social, and applied psychology.',
        'subs' => [
            ['name' => 'BS Psychology', 'slug' => 'bs-psychology'],
        ],
    ],
    [
        'name' => 'Department of Mass Communication',
        'slug' => 'mass-communication',
        'description' => 'Media, journalism, and communication studies with hands-on production training.',
        'subs' => [
            ['name' => 'BS Mass Communication', 'slug' => 'bs-mass-communication'],
        ],
    ],
];

$subjectsBySubDept = [
    'bs-computer-science' => ['Programming Fundamentals', 'Data Structures & Algorithms', 'Database Systems', 'Operating Systems'],
    'bs-software-engineering' => ['Software Requirements Engineering', 'Software Design & Architecture', 'Web Engineering'],
    'bba' => ['Principles of Management', 'Financial Accounting', 'Marketing Management'],
    'mba' => ['Strategic Management', 'Corporate Finance'],
    'bs-psychology' => ['Introduction to Psychology', 'Cognitive Psychology'],
    'bs-mass-communication' => ['Introduction to Mass Communication', 'Media Writing'],
];

$order = 0;

foreach ($departments as $dept) {
    $order++;

    $existing = $db->fetch("SELECT id FROM departments WHERE slug = ?", [$dept['slug']]);

    if ($existing) {
        $deptId = (int) $existing['id'];
        echo "  - Department '{$dept['name']}' already exists, skipping insert.\n";
    } else {
        $db->execute(
            "INSERT INTO departments (name, slug, description, meta_title, meta_description, display_order, is_active)
             VALUES (?, ?, ?, ?, ?, ?, 1)",
            [
                $dept['name'],
                $dept['slug'],
                $dept['description'],
                $dept['name'] . ' | LGU Past Papers',
                $dept['description'],
                $order,
            ]
        );
        $deptId = (int) $db->pdo()->lastInsertId();
        echo "  - Department created: {$dept['name']}\n";
    }

    $subOrder = 0;
    foreach ($dept['subs'] as $sub) {
        $subOrder++;

        $existingSub = $db->fetch("SELECT id FROM sub_departments WHERE slug = ?", [$sub['slug']]);

        if ($existingSub) {
            $subId = (int) $existingSub['id'];
        } else {
            $db->execute(
                "INSERT INTO sub_departments (department_id, name, slug, description, display_order, is_active)
                 VALUES (?, ?, ?, ?, ?, 1)",
                [
                    $deptId,
                    $sub['name'],
                    $sub['slug'],
                    "Past papers and resources for {$sub['name']} at Lahore Garrison University.",
                    $subOrder,
                ]
            );
            $subId = (int) $db->pdo()->lastInsertId();
        }

        $subjects = $subjectsBySubDept[$sub['slug']] ?? ["{$sub['name']} Core Subject"];
        $examTypes = ['mids', 'finals', 'summer_mids', 'summer_finals'];
        $sessions = ['Fall 2023', 'Spring 2024', 'Fall 2024', 'Spring 2025'];

        foreach ($subjects as $i => $subjectName) {
            $examType = $examTypes[$i % count($examTypes)];
            $session = $sessions[$i % count($sessions)];
            $paperSlug = $sub['slug'] . '-' . strtolower(str_replace([' ', '&', '/'], ['-', 'and', '-'], $subjectName)) . '-' . $examType;
            $paperSlug = preg_replace('/[^a-z0-9\-]/', '', $paperSlug);

            $existingPaper = $db->fetch("SELECT id FROM papers WHERE slug = ?", [$paperSlug]);

            if (!$existingPaper) {
                $db->execute(
                    "INSERT INTO papers
                        (sub_department_id, exam_type, session, subject_name, slug, paper_image, solution_image, views_count, meta_title, meta_description)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    [
                        $subId,
                        $examType,
                        $session,
                        $subjectName,
                        $paperSlug,
                        '/assets/images/placeholder-paper.jpg',
                        '/assets/images/placeholder-solution.jpg',
                        random_int(20, 500),
                        "{$subjectName} {$session} Past Paper | LGU",
                        "Download the {$session} {$examType} past paper for {$subjectName}.",
                    ]
                );
            }
        }
    }
}

echo "  - Departments, sub-departments, and papers seeded.\n";

/* ---------------------------------------------------------------------
 | Hero slides
 * ------------------------------------------------------------------- */
$heroSlides = [
    ['image_path' => '/assets/images/hero-1.jpg', 'caption' => 'Find Every Past Paper You Need', 'link_url' => '/departments', 'display_order' => 1],
    ['image_path' => '/assets/images/hero-2.jpg', 'caption' => 'Book One-on-One Lectures With Top Tutors', 'link_url' => '/lectures', 'display_order' => 2],
    ['image_path' => '/assets/images/hero-3.jpg', 'caption' => 'Contribute Your Own Papers to Help Juniors', 'link_url' => '/contact-us', 'display_order' => 3],
];

foreach ($heroSlides as $slide) {
    $exists = $db->fetch("SELECT id FROM hero_slides WHERE caption = ?", [$slide['caption']]);
    if (!$exists) {
        $db->execute(
            "INSERT INTO hero_slides (image_path, caption, link_url, display_order, is_active) VALUES (?, ?, ?, ?, 1)",
            [$slide['image_path'], $slide['caption'], $slide['link_url'], $slide['display_order']]
        );
    }
}
echo "  - Hero slides seeded.\n";

/* ---------------------------------------------------------------------
 | Content blocks
 * ------------------------------------------------------------------- */
$contentBlocks = [
    [
        'page_key' => 'home',
        'heading' => 'Why Students Choose Us',
        'body' => 'A single, organized place to find verified past papers, solved solutions, and recorded lectures for every LGU department.',
        'display_order' => 1,
    ],
    [
        'page_key' => 'about_lgu',
        'heading' => 'About Lahore Garrison University',
        'body' => 'Lahore Garrison University (LGU) is a private university in Lahore, Punjab, established by the Pakistan Army in 2010. LGU offers undergraduate, graduate, and PhD programs across Social Sciences, Computer Sciences, Languages, and Basic Sciences.',
        'display_order' => 1,
    ],
    [
        'page_key' => 'about_us',
        'heading' => 'About This Portal',
        'body' => 'This portal was built by students, for students — to make exam preparation easier by centralizing past papers, solutions, and video lectures in one place.',
        'display_order' => 1,
    ],
];

foreach ($contentBlocks as $block) {
    $exists = $db->fetch(
        "SELECT id FROM content_blocks WHERE page_key = ? AND heading = ?",
        [$block['page_key'], $block['heading']]
    );
    if (!$exists) {
        $db->execute(
            "INSERT INTO content_blocks (page_key, heading, body, display_order, is_active) VALUES (?, ?, ?, ?, 1)",
            [$block['page_key'], $block['heading'], $block['body'], $block['display_order']]
        );
    }
}
echo "  - Content blocks seeded.\n";

/* ---------------------------------------------------------------------
 | Video categories + videos
 * ------------------------------------------------------------------- */
$videoCategories = [
    ['name' => 'Programming Fundamentals', 'slug' => 'programming-fundamentals'],
    ['name' => 'Data Structures & Algorithms', 'slug' => 'data-structures-algorithms'],
    ['name' => 'Business & Management', 'slug' => 'business-management'],
];

$catIds = [];
foreach ($videoCategories as $i => $cat) {
    $existing = $db->fetch("SELECT id FROM video_categories WHERE slug = ?", [$cat['slug']]);
    if ($existing) {
        $catIds[$cat['slug']] = (int) $existing['id'];
    } else {
        $db->execute(
            "INSERT INTO video_categories (name, slug, display_order, is_active) VALUES (?, ?, ?, 1)",
            [$cat['name'], $cat['slug'], $i + 1]
        );
        $catIds[$cat['slug']] = (int) $db->pdo()->lastInsertId();
    }
}

$videos = [
    ['cat' => 'programming-fundamentals', 'title' => 'Introduction to Programming Logic', 'yt' => 'dQw4w9WgXcQ'],
    ['cat' => 'programming-fundamentals', 'title' => 'Loops and Conditionals Explained', 'yt' => 'dQw4w9WgXcQ'],
    ['cat' => 'data-structures-algorithms', 'title' => 'Arrays and Linked Lists', 'yt' => 'dQw4w9WgXcQ'],
    ['cat' => 'business-management', 'title' => 'Principles of Management Overview', 'yt' => 'dQw4w9WgXcQ'],
];

foreach ($videos as $i => $video) {
    $exists = $db->fetch("SELECT id FROM videos WHERE title = ?", [$video['title']]);
    if (!$exists) {
        $db->execute(
            "INSERT INTO videos (category_id, youtube_url, youtube_video_id, title, description, display_order, is_active)
             VALUES (?, ?, ?, ?, ?, ?, 1)",
            [
                $catIds[$video['cat']],
                'https://www.youtube.com/watch?v=' . $video['yt'],
                $video['yt'],
                $video['title'],
                'A recorded lecture covering ' . $video['title'] . '.',
                $i + 1,
            ]
        );
    }
}
echo "  - Video categories and videos seeded.\n";

/* ---------------------------------------------------------------------
 | Alumni testimonials
 * ------------------------------------------------------------------- */
$alumni = [
    ['name' => 'Ayesha Khan', 'batch_year' => '2022', 'degree' => 'BS Computer Science', 'testimonial' => 'The past papers archive saved me hours of searching before finals — everything was organized by subject and session.'],
    ['name' => 'Bilal Ahmed', 'batch_year' => '2021', 'degree' => 'BBA', 'testimonial' => 'Booking a one-on-one lecture before my accounting final made a huge difference to my grade.'],
    ['name' => 'Sara Malik', 'batch_year' => '2023', 'degree' => 'BS Psychology', 'testimonial' => 'A genuinely useful resource — wish it existed when I was a freshman.'],
];

foreach ($alumni as $i => $a) {
    $exists = $db->fetch("SELECT id FROM alumni WHERE name = ? AND batch_year = ?", [$a['name'], $a['batch_year']]);
    if (!$exists) {
        $db->execute(
            "INSERT INTO alumni (name, batch_year, degree, testimonial, display_order, is_active) VALUES (?, ?, ?, ?, ?, 1)",
            [$a['name'], $a['batch_year'], $a['degree'], $a['testimonial'], $i + 1]
        );
    }
}
echo "  - Alumni testimonials seeded.\n";

/* ---------------------------------------------------------------------
 | Site settings
 * ------------------------------------------------------------------- */
$settings = [
    'site_name' => 'LGU Past Papers & Lectures',
    'copyright_text' => '© ' . date('Y') . ' Lahore Garrison University. All Rights Reserved.',
    'facebook_url' => 'https://facebook.com/lahoregarrisonuniversity',
    'twitter_url' => 'https://twitter.com/lgu_official',
    'linkedin_url' => 'https://linkedin.com/school/lahore-garrison-university',
    'whatsapp_number' => '923001234567',
    'contact_email' => 'info@lgu.edu.pk',
    'contact_phone' => '+92 42 111 000 111',
    'contact_address' => 'Phase VI, Sector C, DHA, Lahore, Punjab, Pakistan',
];

foreach ($settings as $key => $value) {
    $exists = $db->fetch("SELECT id FROM site_settings WHERE setting_key = ?", [$key]);
    if (!$exists) {
        $db->execute(
            "INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?)",
            [$key, $value]
        );
    }
}
echo "  - Site settings seeded.\n";

/* ---------------------------------------------------------------------
 | Class bookings / newsletter / paper submissions (sample activity)
 * ------------------------------------------------------------------- */
$bookings = [
    ['name' => 'Hamza Tariq', 'email' => 'hamza.tariq@example.com', 'whatsapp' => '923001112222', 'subject' => 'Database Systems', 'topic' => 'Normalization', 'fee_type' => 'single_lecture'],
    ['name' => 'Zainab Riaz', 'email' => 'zainab.riaz@example.com', 'whatsapp' => '923003334444', 'subject' => 'Corporate Finance', 'topic' => null, 'fee_type' => 'full_subject'],
];

foreach ($bookings as $b) {
    $exists = $db->fetch("SELECT id FROM class_bookings WHERE email = ? AND subject = ?", [$b['email'], $b['subject']]);
    if (!$exists) {
        $db->execute(
            "INSERT INTO class_bookings (name, email, whatsapp, subject, topic, fee_type, description, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'new')",
            [$b['name'], $b['email'], $b['whatsapp'], $b['subject'], $b['topic'], $b['fee_type'], 'Looking forward to the session.']
        );
    }
}

$subscribers = ['student1@example.com', 'student2@example.com', 'student3@example.com'];
foreach ($subscribers as $email) {
    $exists = $db->fetch("SELECT id FROM newsletter_subscribers WHERE email = ?", [$email]);
    if (!$exists) {
        $db->execute(
            "INSERT INTO newsletter_subscribers (email, is_verified) VALUES (?, 1)",
            [$email]
        );
    }
}

$submissions = [
    ['name' => 'Usman Farooq', 'email' => 'usman.farooq@example.com', 'whatsapp' => '923005556666', 'subject' => 'Operating Systems', 'status' => 'pending'],
];
foreach ($submissions as $s) {
    $exists = $db->fetch("SELECT id FROM paper_submissions WHERE email = ? AND subject = ?", [$s['email'], $s['subject']]);
    if (!$exists) {
        $db->execute(
            "INSERT INTO paper_submissions (name, email, whatsapp, subject, description, image_path, status)
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [$s['name'], $s['email'], $s['whatsapp'], $s['subject'], 'Finals paper, Fall 2024.', '/assets/images/placeholder-submission.jpg', $s['status']]
        );
    }
}

echo "  - Sample bookings, subscribers, and submissions seeded.\n";

echo "\nDone. Admin login: admin@lgu.local / admin123\n";
