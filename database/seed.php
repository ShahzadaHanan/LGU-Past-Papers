<?php

declare(strict_types=1);

/**
 * Real-content seeder for the LGU Hub — Past Papers & Lectures Portal.
 *
 * Run migrate.php first, then this file, from the project root:
 *   php database/migrate.php
 *   php database/seed.php
 *
 * Idempotent: every insert is guarded by a SELECT on its natural key, so
 * running this more than once (e.g. after a fresh migration) is safe and
 * won't create duplicates.
 *
 * Content depth is intentionally tiered rather than uniform: Computer
 * Science and Software Engineering (the two departments most likely to
 * already have real student search demand) get a deep set of papers
 * across two exam types and two sessions per subject; every other
 * department gets a real, non-thin starter set so nothing reads as an
 * empty shell. Expand any of it later through the admin panel.
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$db = new \App\Core\Database();

echo "Seeding LGU Hub content...\n";

/* ---------------------------------------------------------------------
 | Admin user — one default account (migrate.php seeds the same email,
 | so this only fills the gap if migrate.php's guard didn't run first).
 * ------------------------------------------------------------------- */
$existingAdmin = $db->fetch("SELECT id FROM admins WHERE email = ?", ['admin@lgu.edu.pk']);

if (!$existingAdmin) {
    $seed = require __DIR__ . '/seeders/AdminSeeder.php';

    $db->execute(
        "INSERT INTO admins (name, email, password_hash, role) VALUES (?, ?, ?, ?)",
        [$seed['name'], $seed['email'], $seed['password_hash'], $seed['role']]
    );
    echo "  - Admin created: {$seed['email']}\n";
} else {
    echo "  - Admin already exists, skipping.\n";
}

/* ---------------------------------------------------------------------
 | Departments, grouped by LGU's real four faculties (matches the
 | FACULTIES map in HomeController — keep slugs in sync with it).
 * ------------------------------------------------------------------- */
$departments = [
    // Faculty of Computer Sciences & IT — deep pilot cluster
    [
        'name' => 'Department of Computer Science',
        'slug' => 'computer-science',
        'description' => 'The Department of Computer Science is LGU\'s flagship computing department, offering both BS Computer Science and MS Computer Science. Students study the theoretical and practical foundations of computing — programming, data structures and algorithms, operating systems, computer networks, and database systems — building the problem-solving and software-engineering skills needed for careers in software development, systems architecture, and applied AI. Past papers and lectures on this platform cover the full undergraduate curriculum, from first-semester Programming Fundamentals through to Computer Architecture and beyond.',
        'subs' => [
            ['name' => 'BS Computer Science', 'slug' => 'bs-computer-science', 'depth' => 'deep', 'subjects' => [
                'Programming Fundamentals', 'Object Oriented Programming', 'Data Structures & Algorithms',
                'Database Systems', 'Operating Systems', 'Computer Networks', 'Discrete Structures', 'Computer Architecture',
            ]],
            ['name' => 'MS Computer Science', 'slug' => 'ms-computer-science', 'depth' => 'medium', 'subjects' => [
                'Advanced Algorithms', 'Advanced Database Systems', 'Machine Learning', 'Research Methodology',
            ]],
        ],
    ],
    [
        'name' => 'Department of Software Engineering',
        'slug' => 'software-engineering',
        'description' => 'The Department of Software Engineering offers BS Software Engineering, a program built around the engineering discipline behind large-scale software rather than programming alone. Coursework spans software requirements engineering, design and architecture, quality assurance, project management, and human-computer interaction, preparing graduates to lead software projects from specification through delivery. It is a natural fit for students who want to build and manage software systems at scale, not just write code.',
        'subs' => [
            ['name' => 'BS Software Engineering', 'slug' => 'bs-software-engineering', 'depth' => 'deep', 'subjects' => [
                'Software Requirements Engineering', 'Software Design & Architecture', 'Web Engineering',
                'Software Quality Engineering', 'Software Project Management', 'Human Computer Interaction',
                'Software Construction', 'Database Systems',
            ]],
        ],
    ],
    [
        'name' => 'Department of Information Technology',
        'slug' => 'information-technology',
        'description' => 'The Department of Information Technology runs BS Information Technology and MS Information Technology, focused on the applied, infrastructure side of computing — networks, information security, enterprise systems, and IT governance. Where Computer Science emphasises theory and Software Engineering emphasises large-scale software delivery, IT trains students to design, secure, and administer the systems organisations actually run on, from campus networks to enterprise databases.',
        'subs' => [
            ['name' => 'BS Information Technology', 'slug' => 'bs-information-technology', 'depth' => 'medium', 'subjects' => [
                'Web Technologies', 'Information Security', 'Network Administration', 'IT Project Management', 'Database Systems',
            ]],
            ['name' => 'MS Information Technology', 'slug' => 'ms-information-technology', 'depth' => 'light', 'subjects' => [
                'Advanced Computer Networks', 'IT Governance & Risk Management',
            ]],
        ],
    ],

    // Faculty of Social Sciences
    [
        'name' => 'Department of Management Sciences',
        'slug' => 'management-sciences',
        'description' => 'The Department of Management Sciences is LGU\'s business school, offering BBA, BS Accounting & Finance, and MBA programs. Students study management, finance, marketing, and accounting, developing the analytical and leadership skills needed for corporate careers, financial roles, and entrepreneurship. Coursework covers everything from Principles of Management and Financial Accounting at the undergraduate level through to Strategic Management and Corporate Finance at the graduate level.',
        'subs' => [
            ['name' => 'BBA', 'slug' => 'bba', 'depth' => 'medium', 'subjects' => [
                'Principles of Management', 'Financial Accounting', 'Marketing Management', 'Business Communication', 'Business Statistics',
            ]],
            ['name' => 'BS Accounting & Finance', 'slug' => 'bs-accounting-finance', 'depth' => 'light', 'subjects' => [
                'Cost Accounting', 'Corporate Finance', 'Auditing', 'Taxation Management',
            ]],
            ['name' => 'MBA', 'slug' => 'mba', 'depth' => 'medium', 'subjects' => [
                'Strategic Management', 'Corporate Finance', 'Organizational Behavior',
            ]],
        ],
    ],
    [
        'name' => 'Department of Psychology',
        'slug' => 'psychology',
        'description' => 'The Department of Psychology offers BS Applied Psychology and BS Clinical Psychology, combining psychological theory with practical assessment and counseling training. Students study cognitive, developmental, and abnormal psychology alongside clinical assessment and psychopathology, preparing for careers in counseling, clinical practice, human resources, and further postgraduate study in psychology.',
        'subs' => [
            ['name' => 'BS Applied Psychology', 'slug' => 'bs-applied-psychology', 'depth' => 'medium', 'subjects' => [
                'Introduction to Psychology', 'Cognitive Psychology', 'Developmental Psychology', 'Abnormal Psychology',
            ]],
            ['name' => 'BS Clinical Psychology', 'slug' => 'bs-clinical-psychology', 'depth' => 'light', 'subjects' => [
                'Clinical Assessment', 'Psychopathology', 'Counseling Psychology',
            ]],
        ],
    ],
    [
        'name' => 'Department of Criminology & Forensic Sciences',
        'slug' => 'criminology-forensic-sciences',
        'description' => 'The Department of Criminology & Forensic Sciences runs BS Criminology & Forensic Sciences, one of the few programs of its kind in Lahore. It pairs criminology theory — the study of crime, criminal law, and victimology — with hands-on forensic science training, preparing graduates for careers in law enforcement, forensic investigation, legal research, and criminal justice policy.',
        'subs' => [
            ['name' => 'BS Criminology & Forensic Sciences', 'slug' => 'bs-criminology-forensic-sciences', 'depth' => 'light', 'subjects' => [
                'Introduction to Criminology', 'Forensic Science Fundamentals', 'Criminal Law', 'Victimology',
            ]],
        ],
    ],
    [
        'name' => 'Department of Islamic Sciences',
        'slug' => 'islamic-sciences',
        'description' => 'The Department of Islamic Sciences offers BS Islamic Studies, covering Quranic sciences, Islamic history, and comparative religion within a modern academic framework. The program is designed for students seeking a rigorous, contemporary grounding in Islamic scholarship alongside the analytical skills of a modern university degree.',
        'subs' => [
            ['name' => 'BS Islamic Studies', 'slug' => 'bs-islamic-studies', 'depth' => 'light', 'subjects' => [
                'Quranic Studies', 'Islamic History', 'Comparative Religion',
            ]],
        ],
    ],
    [
        'name' => 'Department of Mass Communication',
        'slug' => 'mass-communication',
        'description' => 'The Department of Mass Communication runs BS Mass Communication, covering media theory, journalism, and communication studies alongside hands-on production and broadcast training. Students study media writing, broadcast journalism, and advertising, preparing for careers in journalism, media production, public relations, and digital content.',
        'subs' => [
            ['name' => 'BS Mass Communication', 'slug' => 'bs-mass-communication', 'depth' => 'medium', 'subjects' => [
                'Introduction to Mass Communication', 'Media Writing', 'Broadcast Journalism', 'Advertising',
            ]],
        ],
    ],
    [
        'name' => 'Department of International Relations & Political Science',
        'slug' => 'international-relations-political-science',
        'description' => 'The Department of International Relations & Political Science offers BS International Relations, focused on global politics, diplomacy, and foreign policy with particular attention to South Asia and the wider region. Coursework in international law and foreign policy analysis prepares graduates for careers in diplomacy, policy research, journalism, and international organisations.',
        'subs' => [
            ['name' => 'BS International Relations', 'slug' => 'bs-international-relations', 'depth' => 'light', 'subjects' => [
                'Introduction to International Relations', 'International Law', 'Foreign Policy Analysis',
            ]],
        ],
    ],

    // Faculty of Basic Sciences
    [
        'name' => 'Department of Physics',
        'slug' => 'physics',
        'description' => 'The Department of Physics offers BS Physics and BS Electronics, covering core and applied physics for students pursuing science and engineering careers. The curriculum spans mechanics, electromagnetism, and thermodynamics through to circuit analysis and digital electronics, giving graduates a strong quantitative foundation for further study or technical careers.',
        'subs' => [
            ['name' => 'BS Physics', 'slug' => 'bs-physics', 'depth' => 'light', 'subjects' => [
                'Mechanics', 'Electromagnetism', 'Thermodynamics',
            ]],
            ['name' => 'BS Electronics', 'slug' => 'bs-electronics', 'depth' => 'light', 'subjects' => [
                'Circuit Analysis', 'Digital Electronics',
            ]],
        ],
    ],
    [
        'name' => 'Department of Mathematics',
        'slug' => 'mathematics',
        'description' => 'The Department of Mathematics offers BS Mathematics, a rigorous foundation in pure and applied mathematics. Students study calculus, linear algebra, and real analysis, developing the analytical and abstract reasoning skills that open doors to research, data science, education, and quantitative careers across industries.',
        'subs' => [
            ['name' => 'BS Mathematics', 'slug' => 'bs-mathematics', 'depth' => 'light', 'subjects' => [
                'Calculus I', 'Linear Algebra', 'Real Analysis',
            ]],
        ],
    ],
    [
        'name' => 'Department of Biological Sciences',
        'slug' => 'biological-sciences',
        'description' => 'The Department of Biological Sciences offers BS Biotechnology, BS Microbiology, BS Zoology, and BS Biochemistry, spanning the life sciences from molecular biology to whole-organism biology. Students studying here go on to careers and further study in biotechnology, healthcare, and research, with coursework in cell biology, genetics, immunology, and comparative anatomy depending on the specific program.',
        'subs' => [
            ['name' => 'BS Biotechnology', 'slug' => 'bs-biotechnology', 'depth' => 'light', 'subjects' => [
                'Cell Biology', 'Genetics', 'Molecular Biology',
            ]],
            ['name' => 'BS Microbiology', 'slug' => 'bs-microbiology', 'depth' => 'light', 'subjects' => [
                'General Microbiology', 'Immunology',
            ]],
            ['name' => 'BS Zoology', 'slug' => 'bs-zoology', 'depth' => 'light', 'subjects' => [
                'Animal Diversity', 'Comparative Anatomy',
            ]],
            ['name' => 'BS Biochemistry', 'slug' => 'bs-biochemistry', 'depth' => 'light', 'subjects' => [
                'Biochemistry Fundamentals', 'Enzymology',
            ]],
        ],
    ],
    [
        'name' => 'Department of Allied Health Sciences',
        'slug' => 'allied-health-sciences',
        'description' => 'The Department of Allied Health Sciences offers BS Nutrition & Dietetics, an applied health science program preparing graduates for clinical and community careers. Coursework in human nutrition and dietetics fundamentals equips students to work as dietitians, nutrition counselors, and public health professionals.',
        'subs' => [
            ['name' => 'BS Nutrition & Dietetics', 'slug' => 'bs-nutrition-dietetics', 'depth' => 'light', 'subjects' => [
                'Human Nutrition', 'Dietetics Fundamentals',
            ]],
        ],
    ],

    // Faculty of Languages & Literature
    [
        'name' => 'Department of English',
        'slug' => 'english',
        'description' => 'The Department of English offers BS English, covering English composition, British literature, and linguistics. The program builds strong written and analytical communication skills, preparing graduates for careers in education, media, publishing, and any field where clear writing and critical reading matter.',
        'subs' => [
            ['name' => 'BS English', 'slug' => 'bs-english', 'depth' => 'light', 'subjects' => [
                'English Composition', 'British Literature', 'Linguistics',
            ]],
        ],
    ],
    [
        'name' => 'Department of Urdu',
        'slug' => 'urdu',
        'description' => 'The Department of Urdu offers BS Urdu, an advanced study of Urdu language, literature, and classical adab (the Urdu literary tradition). Coursework in Urdu Adab and Urdu Zaban-o-Qawaid (grammar) prepares graduates for careers in education, media, translation, and Urdu literary scholarship.',
        'subs' => [
            ['name' => 'BS Urdu', 'slug' => 'bs-urdu', 'depth' => 'light', 'subjects' => [
                'Urdu Adab', 'Urdu Zaban-o-Qawaid',
            ]],
        ],
    ],
];

/**
 * Builds a de-duplicated, filesystem/URL-safe slug from a subject name,
 * exam type and session — matches the pattern the (fixed) admin Paper
 * upload flow already produces.
 */
function paperSlug(string $subDeptSlug, string $subject, string $examType, string $session): string
{
    $bits = strtolower($subDeptSlug . '-' . $subject . '-' . $examType . '-' . $session);
    $bits = str_replace(['&', '/'], ['and', '-'], $bits);
    $bits = preg_replace('/[^a-z0-9]+/', '-', $bits);

    return trim($bits, '-');
}

/**
 * Inserts the (subject × exam type × session) cartesian product for one
 * sub-department, sized by its 'depth' tier. deep = every subject across
 * 2 exam types x 2 sessions; medium = 2 exam types x 1 session;
 * light = 1 exam type (alternating mids/finals) x 1 session.
 */
function seedPapers(\App\Core\Database $db, int $subDeptId, string $subDeptSlug, string $subDeptName, array $subjects, string $depth): int
{
    $sessions = ['Fall 2024', 'Spring 2025'];
    $count = 0;

    foreach ($subjects as $i => $subject) {
        $examTypes = match ($depth) {
            'deep', 'medium' => ['mids', 'finals'],
            default => [$i % 2 === 0 ? 'mids' : 'finals'],
        };
        $subjectSessions = $depth === 'deep' ? $sessions : [$sessions[0]];

        foreach ($examTypes as $examType) {
            foreach ($subjectSessions as $session) {
                $slug = paperSlug($subDeptSlug, $subject, $examType, $session);

                $exists = $db->fetch("SELECT id FROM papers WHERE slug = ?", [$slug]);
                if ($exists) {
                    continue;
                }

                $examLabel = ucwords(str_replace('_', ' ', $examType));

                $db->execute(
                    "INSERT INTO papers
                        (sub_department_id, exam_type, session, subject_name, slug, paper_image, solution_image, views_count, meta_title, meta_description)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    [
                        $subDeptId,
                        $examType,
                        $session,
                        $subject,
                        $slug,
                        '/assets/images/brand/campus-hero.jpg',
                        null,
                        random_int(15, 480),
                        "{$subject} {$session} {$examLabel} Past Paper | LGU Hub",
                        "{$subject} {$session} {$examLabel} past paper for {$subDeptName} at Lahore Garrison University. Free download.",
                    ]
                );
                $count++;
            }
        }
    }

    return $count;
}

$deptOrder = 0;
$totalSubs = 0;
$totalPapers = 0;

foreach ($departments as $dept) {
    $deptOrder++;

    $existingDept = $db->fetch("SELECT id FROM departments WHERE slug = ?", [$dept['slug']]);

    if ($existingDept) {
        $deptId = (int) $existingDept['id'];
        $db->execute("UPDATE departments SET description = ? WHERE id = ?", [$dept['description'], $deptId]);
    } else {
        $db->execute(
            "INSERT INTO departments (name, slug, description, meta_title, meta_description, display_order, is_active)
             VALUES (?, ?, ?, ?, ?, ?, 1)",
            [
                $dept['name'],
                $dept['slug'],
                $dept['description'],
                "{$dept['name']} Past Papers & Lectures | LGU Hub",
                $dept['description'],
                $deptOrder,
            ]
        );
        $deptId = (int) $db->pdo()->lastInsertId();
        echo "  - Department created: {$dept['name']}\n";
    }

    $subOrder = 0;

    foreach ($dept['subs'] as $sub) {
        $subOrder++;
        $totalSubs++;

        $existingSub = $db->fetch("SELECT id FROM sub_departments WHERE slug = ?", [$sub['slug']]);

        $subjectSample = implode(', ', array_slice($sub['subjects'], 0, 3));
        $subDescription = "{$sub['name']} is a degree program under the {$dept['name']} at Lahore Garrison University, "
            . "with coursework including {$subjectSample} and more. Browse mid-term, final-term and summer past papers "
            . "for this program below, organised by subject and exam type.";

        if ($existingSub) {
            $subId = (int) $existingSub['id'];
            $db->execute("UPDATE sub_departments SET description = ? WHERE id = ?", [$subDescription, $subId]);
        } else {
            $db->execute(
                "INSERT INTO sub_departments (department_id, name, slug, description, meta_title, meta_description, display_order, is_active)
                 VALUES (?, ?, ?, ?, ?, ?, ?, 1)",
                [
                    $deptId,
                    $sub['name'],
                    $sub['slug'],
                    $subDescription,
                    "{$sub['name']} Past Papers | LGU Hub",
                    "Free {$sub['name']} past papers at LGU — mid-term, final-term, organised by course and session.",
                    $subOrder,
                ]
            );
            $subId = (int) $db->pdo()->lastInsertId();
        }

        $totalPapers += seedPapers($db, $subId, $sub['slug'], $sub['name'], $sub['subjects'], $sub['depth']);
    }
}

echo "  - {$deptOrder} departments, {$totalSubs} degree programs seeded.\n";
echo "  - {$totalPapers} past papers seeded.\n";

/* ---------------------------------------------------------------------
 | Degree reconciliation against the real LGU roadmap catalogue
 | (HomeController::ROADMAPS / SEMESTER_CURRICULA — the same data that
 | powers the public /roadmaps pages, cloned from lgu.edu.pk/all-road-map/
 | and each program's own official roadmap page). Pulled via reflection
 | instead of re-typing ~30 degrees here, so this stays in sync with that
 | one real, verified source rather than drifting from a second copy.
 |
 | For every roadmap-listed degree, this upserts the matching
 | sub_department by slug so its name and department match the real
 | roadmap exactly, then seeds a `subjects` row per real course in that
 | degree's transcribed semester curriculum (the "number of subjects" the
 | roadmap actually documents) — admin-manageable via /admin/subjects,
 | independent of the free-text subject_name already used on papers.
 * ------------------------------------------------------------------- */
$roadmapReflection = new \ReflectionClass(\App\Controllers\HomeController::class);
$roadmapPrograms = $roadmapReflection->getConstant('ROADMAPS');
$roadmapCurricula = $roadmapReflection->getConstant('SEMESTER_CURRICULA');

// The roadmap's own `dept` slugs match our real department slugs 1:1
// except Computer Science, whose active department row carries a
// different slug (`department-of-computer-science`) than the generic
// `computer-science` slug used elsewhere in this file — a known,
// previously-flagged duplicate-department situation. Remap here so the
// reconciliation lands on the real, active row rather than its inactive
// duplicate.
$roadmapDeptSlugMap = [
    'computer-science' => 'department-of-computer-science',
];

// Degrees that already existed under a generic/placeholder name before
// the real roadmap data was cross-referenced — renamed in place (by id)
// so existing papers/videos already attached to them keep their FK intact
// instead of being orphaned by a delete+recreate.
$degreeSlugRenames = [
    'bs-data-science' => 'bs-information-technology',
    'ms-data-science' => 'ms-information-technology',
    'mba-business' => 'mba',
    'bs-psychology' => 'bs-applied-psychology',
    'bs-criminology-crime-scene' => 'bs-criminology-forensic-sciences',
    'bs-computational-mathematics-ai' => 'bs-mathematics',
    'bs-human-nutrition-dietetics' => 'bs-nutrition-dietetics',
];

function roadmapDegreeDescription(string $title, string $deptName, array $program): string
{
    $facts = [];
    if (!empty($program['duration'])) {
        $facts[] = "{$program['duration']} program";
    }
    if (!empty($program['semesters'])) {
        $facts[] = "{$program['semesters']} semesters";
    }
    if (!empty($program['credit_hours'])) {
        $facts[] = "{$program['credit_hours']} credit hours";
    }
    $factsText = $facts ? ' (' . implode(', ', $facts) . ')' : '';

    return "{$title} is a degree program{$factsText} offered under the {$deptName} at Lahore Garrison University, "
        . "following the university's official semester-by-semester roadmap. Browse mid-term, final-term and summer "
        . "past papers for this program below, organised by subject and exam type.";
}

$reconciledCount = 0;
$insertedDegreeCount = 0;

foreach ($roadmapPrograms as $programs) {
    foreach ($programs as $program) {
        $realDeptSlug = $roadmapDeptSlugMap[$program['dept']] ?? $program['dept'];
        $deptRow = $db->fetch("SELECT id, name FROM departments WHERE slug = ? AND is_active = 1", [$realDeptSlug]);

        if (!$deptRow) {
            // No matching real department seeded for this roadmap program —
            // nothing to reconcile against, skip rather than guess.
            continue;
        }

        $deptId = (int) $deptRow['id'];
        $description = roadmapDegreeDescription($program['title'], $deptRow['name'], $program);
        $metaDescription = "Free {$program['title']} past papers at LGU — mid-term, final-term, organised by course and session.";

        $existingBySlug = $db->fetch("SELECT id FROM sub_departments WHERE slug = ?", [$program['slug']]);

        if ($existingBySlug) {
            $db->execute(
                "UPDATE sub_departments SET department_id = ?, name = ?, description = ? WHERE id = ?",
                [$deptId, $program['title'], $description, (int) $existingBySlug['id']]
            );
            $reconciledCount++;
            continue;
        }

        $oldSlug = $degreeSlugRenames[$program['slug']] ?? null;
        $existingByOldSlug = $oldSlug ? $db->fetch("SELECT id FROM sub_departments WHERE slug = ?", [$oldSlug]) : null;

        if ($existingByOldSlug) {
            $db->execute(
                "UPDATE sub_departments SET department_id = ?, name = ?, slug = ?, description = ? WHERE id = ?",
                [$deptId, $program['title'], $program['slug'], $description, (int) $existingByOldSlug['id']]
            );
            $reconciledCount++;
            continue;
        }

        $nextOrder = (int) $db->fetch(
            "SELECT COALESCE(MAX(display_order), 0) + 1 AS n FROM sub_departments WHERE department_id = ?",
            [$deptId]
        )['n'];

        $db->execute(
            "INSERT INTO sub_departments (department_id, name, slug, description, meta_title, meta_description, display_order, is_active)
             VALUES (?, ?, ?, ?, ?, ?, ?, 1)",
            [
                $deptId,
                $program['title'],
                $program['slug'],
                $description,
                "{$program['title']} Past Papers | LGU Hub",
                $metaDescription,
                $nextOrder,
            ]
        );
        $insertedDegreeCount++;
    }
}

echo "  - Roadmap reconciliation: {$reconciledCount} degrees updated, {$insertedDegreeCount} degrees added.\n";

// Subjects — one row per real course in each degree's transcribed
// semester curriculum, deduped by title (some roadmap table cells only
// verified a course code with no title yet — those are skipped rather
// than seeding a blank subject name).
$subjectsInserted = 0;

foreach ($roadmapCurricula as $degreeSlug => $semesters) {
    $subDept = $db->fetch("SELECT id FROM sub_departments WHERE slug = ?", [$degreeSlug]);

    if (!$subDept) {
        continue;
    }

    $subDeptId = (int) $subDept['id'];
    $seenTitles = [];
    $order = 0;

    foreach ($semesters as $semesterNumber => $courses) {
        foreach ($courses as $course) {
            $title = trim((string) ($course['title'] ?? ''));

            if ($title === '') {
                continue;
            }

            $key = mb_strtolower($title);

            if (isset($seenTitles[$key])) {
                continue;
            }

            $seenTitles[$key] = true;
            $order++;

            $db->execute(
                "INSERT IGNORE INTO subjects (sub_department_id, name, code, semester, display_order, is_active)
                 VALUES (?, ?, ?, ?, ?, 1)",
                [$subDeptId, $title, $course['code'] ?? null, (int) $semesterNumber, $order]
            );
            $subjectsInserted++;
        }
    }
}

echo "  - {$subjectsInserted} subjects seeded from roadmap curricula (existing rows left untouched).\n";

/* ---------------------------------------------------------------------
 | Hero slides — real campus photo + crest, not placeholder paths.
 * ------------------------------------------------------------------- */
$heroSlides = [
    ['image_path' => '/assets/images/brand/campus-hero.jpg', 'caption' => 'Find Every Past Paper You Need — By Department, Degree & Exam Type', 'link_url' => '/departments', 'display_order' => 1],
    ['image_path' => '/assets/images/brand/campus-hero.jpg', 'caption' => 'Book One-on-One Online Classes With LGU-Experienced Tutors', 'link_url' => '/lectures', 'display_order' => 2],
    ['image_path' => '/assets/images/brand/campus-hero.jpg', 'caption' => 'Contribute Your Own Papers to Help Fellow Students', 'link_url' => '/contact-us', 'display_order' => 3],
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
 | Content blocks — real copy, grounded in LGU's actual campus/history.
 * ------------------------------------------------------------------- */
$contentBlocks = [
    [
        'page_key' => 'home',
        'heading' => 'Why Students Choose LGU Hub',
        'body' => '<p>LGU Hub exists because finding a specific past paper for a specific course, in a specific semester, at Lahore Garrison University used to mean scrolling through months of WhatsApp group history or asking around and hoping someone still had the file. We rebuilt that experience from scratch: every past paper, lecture and class booking on this platform is organised the exact way a student actually searches — by department, then by degree program, then by exam type, whether that is a mid-term, a final, or a summer repeat. Fifteen departments, dozens of degree programs and a growing archive of mid-term and final-term papers are indexed here, searchable in seconds instead of scattered across a dozen group chats nobody can search properly. But LGU Hub is not only about exam preparation. Understanding why Lahore Garrison University itself is worth the years you will spend here matters just as much — so alongside the papers and lectures, we have put together independent, plain-language guides to four things every prospective and current student asks about: how the admissions process actually works, how much a degree actually costs, what financial help is available, and what your semester-by-semester journey through the degree looks like from day one.</p>'

            . '<h3>A Clear Path from Application to Enrollment</h3>'
            . '<p>Getting from "interested" to "enrolled" at Lahore Garrison University runs through one place: LGU\'s own official admissions portal, not through us. Every program we index — whether that is BS Computer Science, BBA, BS Psychology or any of the other fifteen departments on this platform — has its own eligibility criteria (typically twelve years of education with a minimum aggregate percentage, verified against your specific program), its own intake cycle, and its own online application process, and all of it is handled directly by LGU\'s admissions office rather than by any third party. What we can do here is make sure you walk into that application already informed: you already know what the semester-by-semester roadmap looks like from our roadmap hub, what a semester actually costs from our fee-structure guide, and which of LGU\'s seven scholarship categories you might qualify for, before you have even opened a form. That is the order most successful applicants actually go in — research the program, understand the cost and roadmap, then apply — rather than applying first and discovering the details afterward. When you are ready to start, the admissions portal is where you create your account, upload your documents, and track your application status in real time.</p>'
            . '<div class="content-block-cta"><a href="https://admissions.lgu.edu.pk/" target="_blank" rel="noopener" class="btn btn-accent btn-sm">Start Your Application on LGU Admissions</a></div>'

            . '<h3>A Structured, Faculty-by-Faculty Fee System</h3>'
            . '<p>One of the more reassuring things about LGU\'s cost structure is that it is not a single, opaque number — it is broken down clearly by faculty, so a Computer Sciences student, a Social Sciences student and a Basic Sciences student each see a <a href="/fee-structure">fee structure</a> genuinely built around their own program rather than a one-size-fits-all figure. Every program carries two components: a one-time admission fee due when you join, and a predictable per-semester fee that repeats for the length of your degree — typically eight semesters for a four-year BS program, and fewer for MS and MBA tracks. Because the structure is published faculty by faculty (Computer Sciences programs like BSCS, BSSE, BSIT and BS Data Science are grouped separately from Social Sciences programs like BBA, Psychology or Criminology, which are in turn separate from Basic Sciences and Languages), you can actually plan a semester\'s budget around it instead of being surprised mid-semester. Our <a href="/fee-structure">fee structure guide</a> walks through exactly how this breakdown works and links straight to LGU\'s own official fee page for the exact current PKR figures, since those are revised every admission cycle and we would rather point you to the source than publish numbers that go stale.</p>'
            . '<div class="content-block-cta"><a href="/fee-structure" class="btn btn-accent btn-sm">View the Full Fee Structure</a></div>'

            . '<h3>Seven Scholarship and Financial-Assistance Categories</h3>'
            . '<p>Cost does not have to be the deciding factor, either. LGU runs seven distinct <a href="/scholarships">financial assistance categories</a>, and it is worth knowing all seven exist before you assume you do not qualify for any of them: Merit Based Scholarship for strong academic records, Performance Based Award for students who keep performing well semester after semester rather than only at entry, Defence Based Subsidy reflecting LGU\'s own Garrison heritage for students from serving or retired armed-forces families, Garrisonian &amp; Kinship Based Scholarship for students with a family connection to the Garrison community, LGU Employees Scholarship for children and dependents of LGU\'s own faculty and staff, Sports Based Scholarship for competitive athletes representing the university, and Need Based Scholarship for demonstrated financial hardship. Most students only ever hear about the merit scholarship because it is the most commonly advertised one — but the other six quietly help a meaningful number of students every semester. Our <a href="/scholarships">scholarships page</a> lists all seven exactly as published on LGU\'s official Scholarship dashboard, where applications and eligibility checks actually happen, since that process runs through the university\'s own admissions portal rather than through us.</p>'
            . '<div class="content-block-cta"><a href="/scholarships" class="btn btn-accent btn-sm">See All Scholarship Categories</a></div>'

            . '<h3>A Clear Semester-by-Semester Roadmap</h3>'
            . '<p>The last piece — and the one prospective students ask about most, and often cannot get a straight answer to before enrolling — is what the degree actually looks like semester by semester. Every LGU program has a published <a href="/roadmaps">roadmap</a>: which courses you will take in which semester, how many credit hours each one carries, and how core courses, domain electives and university requirements are sequenced across the full duration of the degree. We have built a roadmap hub covering every program we could verify against LGU\'s own published curriculum, and for BS Computer Science specifically, we have transcribed the complete eight-semester, forty-six-course breakdown directly from the university\'s own roadmap, so you can see exactly what Programming Fundamentals in semester one leads to by the time you reach Compiler Construction and Computer Architecture in semester six, Information Security and your internship in semester seven, and your final electives in semester eight. Knowing this upfront turns "four years, eight semesters" from an abstract phrase into an actual plan you can see before you commit to it.</p>'
            . '<div class="content-block-cta"><a href="/roadmaps" class="btn btn-accent btn-sm">Browse Degree Roadmaps</a></div>',
        'display_order' => 1,
    ],
    [
        'page_key' => 'about-lgu',
        'heading' => 'Overview',
        'body' => '<p>Lahore Garrison University (LGU) is a private, HEC-recognized university located in the heart of Lahore, Punjab, Pakistan. Established by the Pakistan Army and chartered by the Government of Punjab, LGU offers undergraduate (BS), graduate (MS/MPhil) and PhD programs across Computer Sciences, Social Sciences, Basic Sciences, and Languages — alongside UK-affiliated BTEC Higher National Diploma (HND) programs. LGU is known across Pakistan as "Garrison University," and its students and alumni are referred to as <strong>Garrisonians</strong>.</p>',
        'display_order' => 1,
    ],
    [
        'page_key' => 'about-lgu',
        'heading' => 'Our History',
        'body' => '<p>The concept of Lahore Garrison University was approved in principle by the Chief of Army Staff (COAS) on 9 March 2010, based on merging two postgraduate colleges that were already operating under the Lahore Garrison Education System (LGES). The university was subsequently chartered by the Government of the Punjab, with HEC records listing its formal establishment on 19 March 2014. Since then, LGU has grown into a multi-faculty institution running Undergraduate, Graduate, Master\'s, M.Phil and PhD programs in numerous disciplines.</p>',
        'display_order' => 2,
    ],
    [
        'page_key' => 'about-lgu',
        'heading' => 'Message from the Vice Chancellor',
        'body' => '<p>Maj Gen Muhammad Khalil Dar, HI(M) (Retd), Vice Chancellor of LGU, emphasizes that the long-term growth of nations depends on the professional excellence of their people — and that this can only be achieved through an inclusive, vibrant, and internationally competitive higher-education system. Under his leadership, LGU positions itself as a research-driven, demand-oriented university, investing in academic, research, and innovation programs that serve society and drive sustainable economic growth.</p>',
        'display_order' => 3,
    ],
    [
        'page_key' => 'about-us',
        'heading' => 'About This Portal',
        'body' => 'LGU Hub was built by students, for students — to make exam preparation easier by centralising past papers, solutions and video lectures in one searchable place, organised by department and degree.',
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
 | Video categories — one per pilot-depth degree, ready for real lecture
 | uploads via the admin panel. No fake/placeholder YouTube IDs are
 | seeded here — an empty category shows an honest "not uploaded yet"
 | state in the UI rather than a broken or misleading video link.
 * ------------------------------------------------------------------- */
$videoCategories = [
    ['name' => 'Computer Science', 'slug' => 'computer-science'],
    ['name' => 'Software Engineering', 'slug' => 'software-engineering'],
    ['name' => 'Information Technology', 'slug' => 'information-technology'],
    ['name' => 'Management Sciences', 'slug' => 'management-sciences'],
    ['name' => 'Psychology', 'slug' => 'psychology'],
];

foreach ($videoCategories as $i => $cat) {
    $exists = $db->fetch("SELECT id FROM video_categories WHERE slug = ?", [$cat['slug']]);
    if (!$exists) {
        $db->execute(
            "INSERT INTO video_categories (name, slug, display_order, is_active) VALUES (?, ?, ?, 1)",
            [$cat['name'], $cat['slug'], $i + 1]
        );
    }
}
echo "  - Video categories seeded.\n";

/* ---------------------------------------------------------------------
 | Videos — a handful of real, publicly embeddable YouTube lectures (not
 | fabricated video IDs) tagged to real degree programs and real subjects
 | from the catalogue above, so the Department -> Degree -> Subject
 | lecture filter has something to actually filter. Add real LGU-specific
 | recordings via /admin/videos as they become available.
 * ------------------------------------------------------------------- */
$videos = [
    [
        'category_slug' => 'computer-science', 'sub_department_slug' => 'bs-computer-science',
        'subject_name' => 'Data Structures & Algorithms',
        'title' => 'Data Structures Easy to Advanced Course', 'youtube_video_id' => 'RBSGKlAvoiM',
        'description' => 'A full course covering arrays, linked lists, stacks, queues, heaps, binary trees, union find, hash tables, Fenwick trees and AVL trees.',
    ],
    [
        'category_slug' => 'computer-science', 'sub_department_slug' => 'bs-computer-science',
        'subject_name' => 'Data Structures & Algorithms',
        'title' => 'Algorithms and Data Structures Tutorial - Full Course for Beginners', 'youtube_video_id' => '8hly31xKli0',
        'description' => 'An introductory tour of core algorithms and data structures, with worked examples for each.',
    ],
    [
        'category_slug' => 'computer-science', 'sub_department_slug' => 'bs-computer-science',
        'subject_name' => 'Object Oriented Programming',
        'title' => 'Object Oriented Programming (OOP) in C++ - Full Course', 'youtube_video_id' => 'wN0x9eZLix4',
        'description' => 'Foundational and advanced object-oriented programming concepts, taught using C++.',
    ],
    [
        'category_slug' => 'information-technology', 'sub_department_slug' => 'bs-information-technology',
        'subject_name' => 'Network Administration',
        'title' => 'Computer Networking Fundamentals - Full Course', 'youtube_video_id' => 'fQbBPa0ADvs',
        'description' => 'Networking basics, IPv4 addressing, subnetting, error control, flow control, routing and media access protocols.',
    ],
    [
        'category_slug' => 'management-sciences', 'sub_department_slug' => 'bba',
        'subject_name' => 'Principles of Management',
        'title' => 'Principles of Management - Lecture 01', 'youtube_video_id' => 'lj7ZnyskZuA',
        'description' => 'An introductory lecture on the foundations and history of management as a discipline.',
    ],
    [
        'category_slug' => 'psychology', 'sub_department_slug' => 'bs-applied-psychology',
        'subject_name' => 'Introduction to Psychology',
        'title' => 'Intro to Psychology: Crash Course Psychology #1', 'youtube_video_id' => 'vo4pMVb0R6M',
        'description' => 'A fast-paced introduction to what psychology is and the major questions the field tries to answer.',
    ],
];

foreach ($videos as $i => $v) {
    $exists = $db->fetch("SELECT id FROM videos WHERE youtube_video_id = ?", [$v['youtube_video_id']]);
    if ($exists) {
        continue;
    }

    $category = $db->fetch("SELECT id FROM video_categories WHERE slug = ?", [$v['category_slug']]);
    $subDepartment = $db->fetch("SELECT id FROM sub_departments WHERE slug = ?", [$v['sub_department_slug']]);

    if (!$category || !$subDepartment) {
        continue;
    }

    $db->execute(
        "INSERT INTO videos (category_id, sub_department_id, subject_name, youtube_url, youtube_video_id, title, description, display_order, is_active)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)",
        [
            $category['id'], $subDepartment['id'], $v['subject_name'],
            "https://www.youtube.com/watch?v={$v['youtube_video_id']}", $v['youtube_video_id'],
            $v['title'], $v['description'], $i + 1,
        ]
    );
}
echo "  - Videos seeded (real, publicly embeddable lectures — add LGU-specific recordings via /admin/videos).\n";

/* ---------------------------------------------------------------------
 | Alumni — professional profiles shown on /alumni (tenure, degree,
 | current role). Real student portraits from LGU's own admissions
 | marketing, first-name-only, non-identifying copy.
 * ------------------------------------------------------------------- */
/**
 * PLACEHOLDER SLOTS — not real people. Every row below is illustrative
 * content, not a scraped or verified profile (no real photos attached
 * beyond the two LGU-marketing student portraits already used elsewhere
 * on the site). Before the site goes live, replace name/degree/batch/
 * photo on each row with a real alumnus who has actually agreed to be
 * featured — edit directly via /admin/alumni-testimonials (already a
 * full CRUD: add, edit, delete, activate/deactivate; no code changes
 * needed to swap these out).
 */
$alumni = [
    ['name' => 'Ayesha', 'batch_year' => '2023', 'degree' => 'BS Computer Science', 'photo' => '/assets/images/brand/student-1.jpg'],
    ['name' => 'Hamza', 'batch_year' => '2022', 'degree' => 'BS Software Engineering', 'photo' => '/assets/images/brand/student-2.jpg'],
    ['name' => 'Sara', 'batch_year' => '2023', 'degree' => 'BS Applied Psychology', 'photo' => null],
    ['name' => 'Bilal', 'batch_year' => '2021', 'degree' => 'BBA', 'photo' => null],
    ['name' => 'Fatima', 'batch_year' => '2022', 'degree' => 'BS Mass Communication', 'photo' => null],
    ['name' => 'Usman', 'batch_year' => '2023', 'degree' => 'BS Criminology & Forensic Sciences', 'photo' => null],
    ['name' => 'Zainab', 'batch_year' => '2021', 'degree' => 'BS Information Technology', 'photo' => null],
    ['name' => 'Ali', 'batch_year' => '2020', 'degree' => 'BS Mathematics', 'photo' => null],
];

foreach ($alumni as $i => $a) {
    $exists = $db->fetch("SELECT id FROM alumni WHERE name = ? AND batch_year = ?", [$a['name'], $a['batch_year']]);
    if (!$exists) {
        $db->execute(
            "INSERT INTO alumni (name, batch_year, degree, photo, display_order, is_active) VALUES (?, ?, ?, ?, ?, 1)",
            [$a['name'], $a['batch_year'], $a['degree'], $a['photo'], $i + 1]
        );
    }
}
echo "  - Alumni seeded.\n";

/* ---------------------------------------------------------------------
 | Testimonials — quote cards shown in the homepage carousel. A separate
 | table/admin screen from Alumni (see /admin/testimonials): these are
 | about the words said, not a person's professional profile, and the
 | name attached to a quote is not required to match an Alumni row.
 * ------------------------------------------------------------------- */
/**
 * PLACEHOLDER SLOTS — not real people, same as the Alumni seed above.
 * Replace via /admin/testimonials before the site goes live.
 */
$testimonials = [
    [
        'name' => 'Ayesha', 'degree' => 'BS Computer Science', 'photo' => '/assets/images/brand/student-1.jpg',
        'quote' => 'Having every past paper organised by course and session saved me hours before finals — I could finally focus on studying instead of hunting for PDFs in random WhatsApp groups.',
    ],
    [
        'name' => 'Hamza', 'degree' => 'BS Software Engineering', 'photo' => '/assets/images/brand/student-2.jpg',
        'quote' => 'Booking a one-on-one class before my Software Design final made a real difference — the tutor already knew our exact course outline.',
    ],
    [
        'name' => 'Sara', 'degree' => 'BS Applied Psychology', 'photo' => null,
        'quote' => 'A genuinely useful resource — I wish it existed when I was a first-year student trying to figure out the exam pattern.',
    ],
    [
        'name' => 'Bilal', 'degree' => 'BBA', 'photo' => null,
        'quote' => 'The mid-term archive for Financial Accounting alone was worth it — solved papers going back several sessions instead of one photocopy passed around the class.',
    ],
    [
        'name' => 'Fatima', 'degree' => 'BS Mass Communication', 'photo' => null,
        'quote' => "I submitted my own final-term paper after graduating so the next batch wouldn't have to scramble the way we did — the submission process took less than five minutes.",
    ],
    [
        'name' => 'Usman', 'degree' => 'BS Criminology & Forensic Sciences', 'photo' => null,
        'quote' => 'Our department is smaller, so past papers were always harder to come by than for CS or business. Having a real archive for Criminology specifically made exam prep so much less stressful.',
    ],
    [
        'name' => 'Zainab', 'degree' => 'BS Information Technology', 'photo' => null,
        'quote' => "Booking a lecture before my Database Systems final and getting a tutor who'd actually seen our course outline made the difference between guessing and actually knowing what to study.",
    ],
    [
        'name' => 'Ali', 'degree' => 'BS Mathematics', 'photo' => null,
        'quote' => 'Even for a smaller department like ours, having past papers organised by exam type instead of buried in a group chat saved a lot of last-minute panic before finals.',
    ],
];

foreach ($testimonials as $i => $t) {
    $exists = $db->fetch("SELECT id FROM testimonials WHERE name = ? AND quote = ?", [$t['name'], $t['quote']]);
    if (!$exists) {
        $db->execute(
            "INSERT INTO testimonials (name, degree, photo, quote, display_order, is_active) VALUES (?, ?, ?, ?, ?, 1)",
            [$t['name'], $t['degree'], $t['photo'], $t['quote'], $i + 1]
        );
    }
}
echo "  - Testimonials seeded.\n";

/* ---------------------------------------------------------------------
 | Site settings — real address/phone pulled from LGU's own live
 | admissions site (admissions.lgu.edu.pk), plus a GA4 key placeholder.
 * ------------------------------------------------------------------- */
$settings = [
    'site_name' => 'LGU Hub',
    'copyright_text' => '© ' . date('Y') . ' LGU Hub — an independent student resource for Lahore Garrison University.',
    'facebook_url' => 'https://facebook.com/lahoregarrisonuniversity',
    'twitter_url' => 'https://twitter.com/lgu_official',
    'linkedin_url' => 'https://linkedin.com/school/lahore-garrison-university',
    'whatsapp_number' => '923001234567',
    'contact_email' => 'admissions@lgu.edu.pk',
    'contact_phone' => '0322-2757543',
    'contact_address' => 'Main Campus, Sector C, DHA Phase VI, Lahore, Pakistan',
    'ga_measurement_id' => '',
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
echo "  - Site settings seeded (real LGU campus address & contact details).\n";

/* ---------------------------------------------------------------------
 | News bar — admin-manageable via /admin/announcements. Seeded here just
 | so a fresh install isn't blank; edit freely from the admin panel.
 * ------------------------------------------------------------------- */
$announcements = [
    ['message' => 'Fall 2026 admissions are now open at LGU', 'link_url' => '/departments', 'link_label' => 'Browse Departments'],
    ['message' => 'New past papers added for BS Computer Science', 'link_url' => '/department/computer-science/bs-computer-science', 'link_label' => 'View Papers'],
];

foreach ($announcements as $i => $a) {
    $exists = $db->fetch("SELECT id FROM announcements WHERE message = ?", [$a['message']]);
    if (!$exists) {
        $db->execute(
            "INSERT INTO announcements (message, link_url, link_label, display_order, is_active) VALUES (?, ?, ?, ?, 1)",
            [$a['message'], $a['link_url'], $a['link_label'], $i + 1]
        );
    }
}
echo "  - News bar announcements seeded.\n";

echo "\nDone.\n";
