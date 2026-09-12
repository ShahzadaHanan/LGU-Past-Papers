<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;
use App\Core\Session;
use App\Core\Request;
use App\Core\Response;
use App\Core\Csrf;


class HomeController extends Controller
{
    public function __construct(
        View $view,
        Session $session,
        Csrf $csrf,
        private Request $request,
        private Response $response,
        private \App\Services\NewsletterSubscriberService $newsletterService
    ) {
        parent::__construct($view, $session, $csrf);
    }

    private function getDb(): \App\Core\Database
    {
        return new \App\Core\Database();
    }

    /**
     * LGU's own site organizes its 15 departments under four faculties.
     * There's no `faculty` column on `departments` (and adding one would be
     * a schema change this purely-presentational grouping doesn't need) —
     * this maps the department slugs seeded in database/seed.php to their
     * real faculty for display on the departments hub.
     */
    private const FACULTIES = [
        'Faculty of Social Sciences' => [
            'management-sciences', 'psychology', 'criminology-forensic-sciences',
            'islamic-sciences', 'mass-communication', 'international-relations-political-science',
        ],
        'Faculty of Computer Sciences & IT' => [
            'computer-science', 'software-engineering', 'information-technology',
        ],
        'Faculty of Basic Sciences' => [
            'physics', 'mathematics', 'biological-sciences', 'allied-health-sciences',
        ],
        'Faculty of Languages & Literature' => [
            'english', 'urdu',
        ],
    ];

    /**
     * Real degree roadmaps published on lgu.edu.pk/all-road-map/, cloned
     * in full — every program listed on that page, in the same department
     * groupings, linking to the same official roadmap page. `dept` links
     * back to our own seeded department slug where one exists. Only
     * `bs-computer-science` has a fully-transcribed semester-by-semester
     * curriculum (from the real lgu.edu.pk/bs-cs-road-map/ page) — the rest
     * show program-level facts (duration/semesters by degree-level
     * convention, credit hours only where independently verified) and link
     * out to the official page for the full semester breakdown, rather
     * than guessing course lists we haven't verified.
     */
    private const ROADMAPS = [
        'Department of Computer Science' => [
            ['slug' => 'bs-computer-science', 'title' => 'BS Computer Science', 'dept' => 'computer-science', 'duration' => '4 Years', 'credit_hours' => 132, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bs-cs-road-map/'],
            ['slug' => 'ms-computer-science', 'title' => 'MS Computer Science', 'dept' => 'computer-science', 'duration' => '2 Years', 'credit_hours' => 30, 'semesters' => 4, 'official_url' => 'https://lgu.edu.pk/mscs-road-map/'],
            ['slug' => 'ms-artificial-intelligence', 'title' => 'MS Artificial Intelligence', 'dept' => 'computer-science', 'duration' => '2 Years', 'credit_hours' => 33, 'semesters' => 4, 'official_url' => 'https://lgu.edu.pk/master-of-science-in-artificial-intelligence/'],
            ['slug' => 'phd-computer-science', 'title' => 'PhD Computer Science', 'dept' => 'computer-science', 'duration' => '3-5 Years', 'credit_hours' => 48, 'semesters' => 6, 'official_url' => 'https://lgu.edu.pk/ph-d-cs-roadmap/'],
        ],
        'Department of Software Engineering' => [
            ['slug' => 'bs-software-engineering', 'title' => 'BS Software Engineering', 'dept' => 'software-engineering', 'duration' => '4 Years', 'credit_hours' => 129, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bs-se-roadmap/'],
        ],
        'Department of Information Technology' => [
            ['slug' => 'bs-data-science', 'title' => 'BS Data Science', 'dept' => 'information-technology', 'duration' => '4 Years', 'credit_hours' => 131, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bs-ds-roadmap/'],
            ['slug' => 'ms-data-science', 'title' => 'MS Data Science', 'dept' => 'information-technology', 'duration' => '2 Years', 'credit_hours' => 30, 'semesters' => 4, 'official_url' => 'https://lgu.edu.pk/ms-data-science-road-map/'],
            ['slug' => 'bs-cyber-security', 'title' => 'BS Cyber Security', 'dept' => 'information-technology', 'duration' => '4 Years', 'credit_hours' => 128, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bs-information-technology-road-map-2-cloned/'],
            ['slug' => 'bs-artificial-intelligence', 'title' => 'BS Artificial Intelligence', 'dept' => 'information-technology', 'duration' => '4 Years', 'credit_hours' => 131, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bs-information-technology-road-map-2/'],
        ],
        'Department of Management Sciences' => [
            ['slug' => 'bba', 'title' => 'BBA', 'dept' => 'management-sciences', 'duration' => '4 Years', 'credit_hours' => 140, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bba-roadmap/'],
            ['slug' => 'bs-accounting-finance', 'title' => 'BS Accounting & Finance', 'dept' => 'management-sciences', 'duration' => '4 Years', 'credit_hours' => 137, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bsaf-roadmap/'],
            ['slug' => 'bs-digital-business', 'title' => 'BS Digital Business', 'dept' => 'management-sciences', 'duration' => '4 Years', 'credit_hours' => 134, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/wp-content/uploads/2025/06/BDB-Annex-A.pdf'],
            ['slug' => 'bs-economics-data-analytics', 'title' => 'BS Economics with Data Analytics', 'dept' => 'management-sciences', 'duration' => '4 Years', 'credit_hours' => 136, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bs-economic/'],
            ['slug' => 'mba-business', 'title' => 'MBA (1.5 Years — Business Background)', 'dept' => 'management-sciences', 'duration' => '1.5 Years', 'credit_hours' => 30, 'semesters' => 3, 'official_url' => 'https://lgu.edu.pk/mba-1-5-years-business-roadmap/'],
            ['slug' => 'mba-nonbusiness', 'title' => 'MBA (2 Years — Non-Business Background)', 'dept' => 'management-sciences', 'duration' => '2 Years', 'credit_hours' => 60, 'semesters' => 4, 'official_url' => 'https://lgu.edu.pk/mba-nonbusiness-2-years-roadmap/'],
            ['slug' => 'phd-management-sciences', 'title' => 'PhD Management Science', 'dept' => 'management-sciences', 'duration' => '3-5 Years', 'credit_hours' => 48, 'semesters' => 3, 'official_url' => 'https://lgu.edu.pk/phd-management-sciences/'],
        ],
        'Department of Psychology' => [
            ['slug' => 'bs-psychology', 'title' => 'BS Psychology', 'dept' => 'psychology', 'duration' => '4 Years', 'credit_hours' => 131, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bs-psychology/'],
            ['slug' => 'bs-clinical-psychology', 'title' => 'BS Psychology (Clinical Psychology)', 'dept' => 'psychology', 'duration' => '4 Years', 'credit_hours' => 131, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bs-clinical-psychology-road-map/'],
            ['slug' => 'ms-psychology', 'title' => 'MS Psychology', 'dept' => 'psychology', 'duration' => '2 Years', 'credit_hours' => 38, 'semesters' => 4, 'official_url' => 'https://lgu.edu.pk/ms-psychology-road-map/'],
            ['slug' => 'ms-clinical-psychology', 'title' => 'MS Clinical Psychology', 'dept' => 'psychology', 'duration' => '2 Years', 'credit_hours' => 38, 'semesters' => 4, 'official_url' => 'https://lgu.edu.pk/ms-clinical-psychology-road-map/'],
        ],
        'Faculty of Basic & Applied Sciences' => [
            ['slug' => 'bs-human-nutrition-dietetics', 'title' => 'BS Human Nutrition & Dietetics', 'dept' => 'allied-health-sciences', 'duration' => '4 Years', 'credit_hours' => 140, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bachelor-of-science-in-nutrition-and-dietetics/'],
            ['slug' => 'bs-biotechnology', 'title' => 'BS Biotechnology', 'dept' => 'biological-sciences', 'duration' => '4 Years', 'credit_hours' => 137, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bachelor-of-science-in-biotechnology/'],
            ['slug' => 'bs-genetics', 'title' => 'BS Genetics', 'dept' => 'biological-sciences', 'duration' => '4 Years', 'credit_hours' => 132, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bachelor-of-science-in-genetics/'],
            ['slug' => 'bs-computational-mathematics-ai', 'title' => 'BS Computational Mathematics & Artificial Intelligence', 'dept' => 'mathematics', 'duration' => '4 Years', 'credit_hours' => 135, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bs-mathematics-in-computational-mathematics-and-artificial-intelligence/'],
            ['slug' => 'mphil-mathematics', 'title' => 'MPhil Mathematics', 'dept' => 'mathematics', 'duration' => '2 Years', 'credit_hours' => 30, 'semesters' => 4, 'official_url' => 'https://lgu.edu.pk/masters-of-philosophy-in-mathematics/'],
        ],
        'Department of Criminology & Forensic Sciences' => [
            ['slug' => 'bs-criminology-crime-scene', 'title' => 'BS Criminology — Crime Scene Investigation', 'dept' => 'criminology-forensic-sciences', 'duration' => '4 Years', 'credit_hours' => 134, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bs-criminology-crime-scene-investigation-road-map-2/'],
            ['slug' => 'bs-criminology-digital-forensics', 'title' => 'BS Criminology — Digital Forensics & Cyber Crime Investigation', 'dept' => 'criminology-forensic-sciences', 'duration' => '4 Years', 'credit_hours' => 134, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bs-criminology-digital-forensics-and-cyber-crime-investigation-road-map/'],
            ['slug' => 'bs-criminology-financial-crime', 'title' => 'BS Criminology — Financial Crime Investigation', 'dept' => 'criminology-forensic-sciences', 'duration' => '4 Years', 'credit_hours' => 131, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bs-criminology-financial-crime-investigation-road-map/'],
        ],
        'Department of Mass Communication' => [
            ['slug' => 'bs-mass-communication', 'title' => 'BS Media & Communication Studies', 'dept' => 'mass-communication', 'duration' => '4 Years', 'credit_hours' => null, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bachelor-of-science-in-mass-communication-road-map/'],
            ['slug' => 'mphil-mass-communication', 'title' => 'MPhil Mass Communication', 'dept' => 'mass-communication', 'duration' => '2 Years', 'credit_hours' => null, 'semesters' => 4, 'official_url' => null],
        ],
        'Department of English' => [
            ['slug' => 'bs-english', 'title' => 'BS English', 'dept' => 'english', 'duration' => '4 Years', 'credit_hours' => 133, 'semesters' => 8, 'official_url' => 'https://lgu.edu.pk/bs-english-road-map/'],
            ['slug' => 'mphil-english', 'title' => 'MPhil English', 'dept' => 'english', 'duration' => '2 Years', 'credit_hours' => 30, 'semesters' => 4, 'official_url' => 'https://lgu.edu.pk/mphil-english-literature/'],
        ],
    ];

    /**
     * Real semester-by-semester curricula transcribed from each program's
     * own lgu.edu.pk roadmap page, keyed by our roadmap slug. Only added
     * once each page's table structure has been manually verified — LGU's
     * roadmap pages don't share one consistent table layout (course codes,
     * column order and even semester/table pairing vary program to
     * program), so this is populated one verified program at a time rather
     * than scraped in bulk. Programs not listed here fall back to linking
     * out to the official roadmap page instead of guessing a curriculum.
     */
    private const SEMESTER_CURRICULA = [
        'bs-computer-science' => [
        1 => [
            ['code' => 'CC6101', 'title' => 'Programming Fundamentals', 'group' => 'Computing Core'],
            ['code' => 'COMPS6101', 'title' => null, 'group' => 'Computing Core'],
            ['code' => 'PHYS6103', 'title' => 'Natural Science (Applied Physics)', 'group' => 'Natural Science'],
            ['code' => 'PAK6101', 'title' => 'Ideology and Constitution of Pakistan', 'group' => 'University Core'],
            ['code' => 'MATH6101', 'title' => null, 'group' => 'Mathematics'],
            ['code' => 'EN6202', 'title' => 'Functional English', 'group' => 'Languages'],
        ],
        2 => [
            ['code' => 'CC6202', 'title' => 'Object Oriented Programming', 'group' => 'Computing Core'],
            ['code' => 'CC6203', 'title' => 'Database Systems', 'group' => 'Computing Core'],
            ['code' => 'CC6204', 'title' => 'Digital Logic Design', 'group' => 'Computing Core'],
            ['code' => 'MATH6608', 'title' => 'Linear Algebra', 'group' => 'Mathematics'],
            ['code' => 'ISL6101', 'title' => 'Islamic Studies', 'group' => 'University Core'],
            ['code' => 'CCE6101', 'title' => 'Civics and Community Engagement', 'group' => 'University Core'],
        ],
        3 => [
            ['code' => 'CC6305', 'title' => 'Data Structures', 'group' => 'Computing Core'],
            ['code' => 'MATH6406', 'title' => 'Discrete Structures', 'group' => 'Mathematics'],
            ['code' => 'CC6410', 'title' => null, 'group' => 'Computing Core'],
            ['code' => 'MATH6507', 'title' => 'Multivariable Calculus', 'group' => 'Mathematics'],
            ['code' => 'ALD6201', 'title' => null, 'group' => 'Allied Discipline'],
            ['code' => 'ALD6204', 'title' => 'Entrepreneurship', 'group' => 'Allied Discipline'],
        ],
        4 => [
            ['code' => 'MATH6608', 'title' => null, 'group' => 'Mathematics'],
            ['code' => 'CSC6402', 'title' => 'Advanced Database Management Systems', 'group' => 'Domain Core (Breadth)'],
            ['code' => 'CC6312', 'title' => 'Analysis of Algorithms', 'group' => 'Computing Core'],
            ['code' => 'EN6302', 'title' => 'Expository Writing', 'group' => 'Languages'],
            ['code' => 'CSC6301', 'title' => 'Theory of Automata', 'group' => 'Domain Core (Breadth)'],
            ['code' => 'ALD6206', 'title' => null, 'group' => 'Allied Discipline'],
        ],
        5 => [
            ['code' => 'CC6511', 'title' => 'Operating Systems', 'group' => 'Computing Core'],
            ['code' => 'CSC6503', 'title' => null, 'group' => 'Domain Core (Breadth)'],
            ['code' => 'CC6307', 'title' => 'Artificial Intelligence', 'group' => 'Computing Core'],
            ['code' => 'CSE6501', 'title' => null, 'group' => 'Domain Elective (Depth)'],
            ['code' => 'CSE6502', 'title' => null, 'group' => 'Domain Elective (Depth)'],
            ['code' => 'CC6309', 'title' => 'Software Engineering', 'group' => 'Computing Core'],
        ],
        6 => [
            ['code' => 'CSC6605', 'title' => 'Compiler Construction', 'group' => 'Domain Core (Breadth)'],
            ['code' => 'EN6304', 'title' => null, 'group' => 'Languages'],
            ['code' => 'CSC6606', 'title' => null, 'group' => 'Domain Core (Breadth)'],
            ['code' => 'CSC6504', 'title' => 'Computer Architecture', 'group' => 'Domain Core (Breadth)'],
            ['code' => 'CSC404', 'title' => null, 'group' => 'Domain Elective'],
            ['code' => 'CC6308', 'title' => 'Computer Networks', 'group' => 'Computing Core'],
        ],
        7 => [
            ['code' => 'CC6306', 'title' => 'Information Security', 'group' => 'Computing Core'],
            ['code' => 'CSE6504', 'title' => null, 'group' => 'Domain Elective (Depth)'],
            ['code' => 'CSE6505', 'title' => null, 'group' => 'Domain Elective (Depth)'],
            ['code' => 'CSE6506', 'title' => null, 'group' => 'Domain Elective (Depth)'],
            ['code' => 'CC6713', 'title' => null, 'group' => 'Computing Core'],
            ['code' => 'CC6705', 'title' => 'Internship', 'group' => 'University Core'],
        ],
        8 => [
            ['code' => 'CC6814', 'title' => null, 'group' => 'Computing Core'],
            ['code' => 'CSE6503', 'title' => null, 'group' => 'Domain Elective (Depth)'],
            ['code' => 'ALD6205', 'title' => 'Elective Supporting Course', 'group' => 'Allied Discipline'],
            ['code' => 'PAKS6101', 'title' => 'Pakistan Studies', 'group' => 'University Core'],
        ],
        ],
        'mba-business' => [
            1 => [
                ['code' => 'BMT711', 'title' => 'Business Research Methods', 'group' => 'Core'],
                ['code' => 'BMT739', 'title' => 'Corporate Finance', 'group' => 'Core'],
                ['code' => 'BMT726', 'title' => 'Advances in Accounting', 'group' => 'Core'],
                ['code' => 'BMT727', 'title' => 'Strategic Marketing', 'group' => 'Core'],
            ],
            2 => [
                ['code' => 'BMT728', 'title' => 'Managerial Economics', 'group' => 'Core'],
                ['code' => null, 'title' => 'Elective 1', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Elective 2', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Elective 3', 'group' => 'Elective'],
            ],
            3 => [
                ['code' => null, 'title' => 'Elective 4', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Elective 5', 'group' => 'Elective'],
            ],
        ],
        'mphil-english' => [
            1 => [
                ['code' => 'ENG716', 'title' => 'Advanced Literary-Cultural Research Methodology', 'group' => 'Core'],
                ['code' => 'ENG717', 'title' => 'Critical Theories', 'group' => 'Core'],
                ['code' => 'ENG718', 'title' => 'Shakespearean Studies', 'group' => 'Elective'],
                ['code' => 'ENG719', 'title' => 'Postmodern American Literature', 'group' => 'Elective'],
            ],
            2 => [
                ['code' => 'ENG725', 'title' => 'World Literature & Translation', 'group' => 'Elective'],
                ['code' => 'ENG726', 'title' => 'Pakistani Writings in English', 'group' => 'Core'],
                ['code' => 'ENG727', 'title' => 'Literature and Film Studies', 'group' => 'Elective'],
                ['code' => 'ENG728', 'title' => 'Diasporic Literatures', 'group' => 'Elective'],
            ],
            3 => [
                ['code' => 'ENG73T', 'title' => 'Research Thesis (Semesters III & IV)', 'group' => 'Thesis'],
            ],
        ],
        'bs-english' => [
            1 => [
                ['code' => 'ENG 6101', 'title' => 'Introduction to Linguistics', 'group' => 'Major'],
                ['code' => 'ENG 6103', 'title' => 'History of English Literature', 'group' => 'Major'],
                ['code' => 'ENG 6102', 'title' => 'Functional English', 'group' => 'General Education'],
                ['code' => 'MATH 6101', 'title' => 'Quantitative Reasoning 1', 'group' => 'General Education'],
                ['code' => 'ISL 6101/ETH 6102', 'title' => 'Islamic Studies / Ethics + The Quranic Learning', 'group' => 'General Education'],
                ['code' => 'COMPS 6101', 'title' => 'Application to Information Communication and Technologies', 'group' => 'General Education'],
            ],
            2 => [
                ['code' => 'ENG 6201', 'title' => 'English for Specific Purposes', 'group' => 'Major'],
                ['code' => 'ENG 6202', 'title' => 'Phonetics and Phonology', 'group' => 'Major'],
                ['code' => 'BIO 6104', 'title' => 'Food and Nutrition', 'group' => 'General Education'],
                ['code' => 'CCE-6101', 'title' => 'Civics & Community Engagement', 'group' => 'General Education'],
                ['code' => 'ENG 6203', 'title' => 'Transnational Literature & Culture', 'group' => 'General Education'],
                ['code' => 'PAK 6101', 'title' => 'Ideology and Constitution of Pakistan', 'group' => 'General Education'],
            ],
            3 => [
                ['code' => 'ENG 6301', 'title' => 'Morphology and Syntax', 'group' => 'Major'],
                ['code' => 'ENG 6302', 'title' => 'Expository Writing', 'group' => 'General Education'],
                ['code' => 'MATH 6102', 'title' => 'Quantitative Reasoning 2', 'group' => 'General Education'],
                ['code' => 'PSY 6101', 'title' => 'Introduction to Psychology', 'group' => 'General Education'],
                ['code' => 'BMT 6305', 'title' => 'Entrepreneurship', 'group' => 'General Education'],
                ['code' => 'MCN 6801', 'title' => 'Digital Story Telling', 'group' => 'Interdisciplinary'],
            ],
            4 => [
                ['code' => 'ENG 6401', 'title' => 'Rise of the Novel (17th–19th c.)', 'group' => 'Major'],
                ['code' => 'ENG 6402', 'title' => 'Short Fictional Narratives', 'group' => 'Major'],
                ['code' => 'ENG 6403', 'title' => 'Classical Poetry', 'group' => 'Major'],
                ['code' => 'ENG 6404', 'title' => 'Second Language Acquisition', 'group' => 'Major'],
                ['code' => 'ENG 6405', 'title' => 'Research Methodology', 'group' => 'Major'],
                ['code' => 'BMT 6802', 'title' => 'Business Ethics and Corporate Governance', 'group' => 'Interdisciplinary'],
            ],
            5 => [
                ['code' => 'ENG 6501', 'title' => 'Classical and Renaissance Drama', 'group' => 'Major'],
                ['code' => 'ENG 6502', 'title' => 'Sociolinguistics', 'group' => 'Major'],
                ['code' => 'ENG 6503', 'title' => 'Semantics and Pragmatics', 'group' => 'Major'],
                ['code' => 'ENG 6504', 'title' => 'Popular and Children\'s Fiction', 'group' => 'Major'],
                ['code' => 'ENG 6505', 'title' => 'Foundations of Literary Theory and Criticism', 'group' => 'Major'],
                ['code' => 'Urdu 6701', 'title' => 'Urdu Drama', 'group' => 'Interdisciplinary'],
            ],
            6 => [
                ['code' => 'ENG 6601', 'title' => 'Modern to Contemporary Drama', 'group' => 'Major'],
                ['code' => 'ENG 6602', 'title' => 'Creative Nonfiction', 'group' => 'Major'],
                ['code' => 'ENG 6603', 'title' => 'Introduction to Philosophy', 'group' => 'Major'],
                ['code' => 'ENG 6604', 'title' => 'Modern Novel', 'group' => 'Major'],
                ['code' => 'ENG 6605', 'title' => 'Discourse Studies', 'group' => 'Major'],
                ['code' => 'PG 6011', 'title' => 'Political Geography', 'group' => 'Interdisciplinary'],
            ],
            7 => [
                ['code' => 'ENG 6701', 'title' => 'Global Poetry', 'group' => 'Major'],
                ['code' => 'ENG 6702', 'title' => 'South Asian Literature', 'group' => 'Major'],
                ['code' => 'ENG 6703', 'title' => 'Trauma and Partition Literature', 'group' => 'Major'],
                ['code' => 'ENG 6704', 'title' => 'Applied Linguistics', 'group' => 'Major'],
                ['code' => 'ENG 6705', 'title' => 'Computational Linguistics', 'group' => 'Major'],
            ],
            8 => [
                ['code' => 'ENG 6801', 'title' => 'Stylistics', 'group' => 'Major'],
                ['code' => 'ENG 6802', 'title' => 'Contemporary World Fiction', 'group' => 'Major'],
                ['code' => 'ENG 6803', 'title' => 'American Literature', 'group' => 'Major'],
                ['code' => 'ENG 6804', 'title' => 'World Englishes', 'group' => 'Major'],
                ['code' => 'ENG 6805', 'title' => 'Capstone Project (Thesis)', 'group' => 'FYP'],
            ],
        ],
        'bs-criminology-financial-crime' => [
            1 => [
                ['code' => null, 'title' => 'Quantitative Reasoning', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Functional English', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Ideology and Constitution of Pakistan', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Introduction to Criminology', 'group' => 'Major'],
                ['code' => null, 'title' => 'Islamic Studies', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Introduction to Philosophy / Ethics (Optional)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Civics and Community Engagement', 'group' => 'General Education'],
            ],
            2 => [
                ['code' => null, 'title' => 'Quantitative Reasoning 2 / Statistics', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Expository Writing', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Entrepreneurship', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Chemical Sciences Applications', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Applications of Information Communication Technologies', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Criminal Justice System', 'group' => 'Major'],
            ],
            3 => [
                ['code' => null, 'title' => 'The Quranic Learning', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Ethics of Social Services (Optional)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Computer Forensics Essentials', 'group' => 'Major'],
                ['code' => null, 'title' => 'Sociology & Psychology of White-Collar Crime', 'group' => 'Major'],
                ['code' => null, 'title' => 'Criminal Law', 'group' => 'Major'],
                ['code' => null, 'title' => 'Banking Operations & Global Payments (SWIFT, Correspondent Banking, Settlement)', 'group' => 'Major'],
                ['code' => null, 'title' => 'Contract Law', 'group' => 'Major'],
            ],
            4 => [
                ['code' => null, 'title' => 'Investigative Techniques', 'group' => 'Major'],
                ['code' => null, 'title' => 'Forensic Psychology for Investigators', 'group' => 'Major'],
                ['code' => null, 'title' => 'Corporate Governance', 'group' => 'Major'],
                ['code' => null, 'title' => 'Ethics & Professional Standards in Investigations', 'group' => 'Major'],
                ['code' => null, 'title' => 'Organizational Misconduct & Control Environments', 'group' => 'Major'],
            ],
            5 => [
                ['code' => null, 'title' => 'OSINT & Analytics for Investigators', 'group' => 'Major'],
                ['code' => null, 'title' => 'Financial Accounting for Investigators I', 'group' => 'Major'],
                ['code' => null, 'title' => 'Tax Compliance & Evasion Typologies', 'group' => 'Major'],
                ['code' => null, 'title' => 'Investigative Auditing, Assurance & Financial-Statement Analysis', 'group' => 'Major'],
                ['code' => null, 'title' => 'Cyber-Financial Fraud & Social Engineering (Phishing, BEC, Account Takeover)', 'group' => 'Major'],
                ['code' => null, 'title' => 'Financial Regulation & Open-Source Financial Intelligence (Lab)', 'group' => 'Major'],
            ],
            6 => [
                ['code' => null, 'title' => 'Research Methods in Criminology', 'group' => 'Major'],
                ['code' => null, 'title' => 'Patterns of Crime', 'group' => 'Major'],
                ['code' => null, 'title' => 'Financial Accounting for Investigators II', 'group' => 'Major'],
                ['code' => null, 'title' => 'Virtual Assets & Blockchain Investigations', 'group' => 'Major'],
                ['code' => null, 'title' => 'Investigating Procurement Fraud', 'group' => 'Major'],
                ['code' => null, 'title' => 'Financial Transactions and Fraud Scheme', 'group' => 'Major'],
            ],
            7 => [
                ['code' => null, 'title' => 'Anti-Money Laundering & CFT (incl. FATF International Standards)', 'group' => 'Major'],
                ['code' => null, 'title' => 'White-Collar Crime, AML/CFT & Asset-Recovery Law in Pakistan (NAB, SECP, FMU/STR)', 'group' => 'Major'],
                ['code' => null, 'title' => 'Forensic Data Analytics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Sanctions, Export Controls & TBML Investigations', 'group' => 'Major'],
                ['code' => null, 'title' => 'Asset Tracing, Freezing & Recovery', 'group' => 'Major'],
                ['code' => null, 'title' => 'Evidence Law (incl. ADR & NAB Plea-Bargaining module)', 'group' => 'Major'],
            ],
            8 => [
                ['code' => null, 'title' => 'Advanced Forensic Accounting', 'group' => 'Major'],
                ['code' => null, 'title' => 'Interviewing and Interrogation Techniques', 'group' => 'Major'],
                ['code' => null, 'title' => 'Case Building & Disclosure Management', 'group' => 'Major'],
                ['code' => null, 'title' => 'Financial Crime Practicum', 'group' => 'Internship'],
                ['code' => null, 'title' => 'Digital Evidence in Financial Crime', 'group' => 'Major'],
                ['code' => null, 'title' => 'Capstone Project', 'group' => 'FYP'],
            ],
        ],
        'bs-criminology-crime-scene' => [
            1 => [
                ['code' => null, 'title' => 'Quantitative Reasoning', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Functional English', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Ideology and Constitution of Pakistan', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Introduction to Criminology', 'group' => 'Major'],
                ['code' => null, 'title' => 'Islamic Studies', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Introduction to Philosophy / Ethics (Optional)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Civics and Community Engagement', 'group' => 'General Education'],
            ],
            2 => [
                ['code' => null, 'title' => 'Quantitative Reasoning 2 / Statistics', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Expository Writing', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Entrepreneurship', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Chemical Sciences Applications', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Applications of Information Communication Technologies', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Criminal Justice System', 'group' => 'Major'],
            ],
            3 => [
                ['code' => null, 'title' => 'The Quranic Learning', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Ethics of Social Services (Optional)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Computer Forensics Essentials', 'group' => 'Major'],
                ['code' => null, 'title' => 'Criminal Psychology', 'group' => 'Major'],
                ['code' => null, 'title' => 'Forensic Chemistry', 'group' => 'Major'],
                ['code' => null, 'title' => 'Forensic Photography & Crime Scene Documentation (Lab)', 'group' => 'Major'],
                ['code' => null, 'title' => 'Forensic Biology & Serology', 'group' => 'Major'],
            ],
            4 => [
                ['code' => null, 'title' => 'Investigative Techniques', 'group' => 'Major'],
                ['code' => null, 'title' => 'Fingerprint Analysis (Lab)', 'group' => 'Major'],
                ['code' => null, 'title' => 'Crime Scene Management & Processing (Lab)', 'group' => 'Major'],
                ['code' => null, 'title' => 'Trace Evidence & Microscopy (Lab)', 'group' => 'Major'],
                ['code' => null, 'title' => 'Victimology', 'group' => 'Major'],
            ],
            5 => [
                ['code' => null, 'title' => 'Physical Evidence Recognition, Collection & Preservation (Lab)', 'group' => 'Major'],
                ['code' => null, 'title' => 'Forensic Laboratory Quality Assurance & Accreditation (ISO/IEC 17025)', 'group' => 'Major'],
                ['code' => null, 'title' => 'Criminal Law for Investigators', 'group' => 'Major'],
                ['code' => null, 'title' => 'Firearms & Toolmarks (Lab)', 'group' => 'Major'],
                ['code' => null, 'title' => 'Forensic Toxicology & Controlled Substances', 'group' => 'Major'],
            ],
            6 => [
                ['code' => null, 'title' => 'Questioned Documents Analysis (Lab)', 'group' => 'Major'],
                ['code' => null, 'title' => 'Research Methods & Statistics in Forensic Science (Lab)', 'group' => 'Major'],
                ['code' => null, 'title' => 'Medicolegal Death Investigation', 'group' => 'Major'],
                ['code' => null, 'title' => 'Forensic Anthropology, Odontology, Entomology & Disaster Victim Identification (Lab)', 'group' => 'Major'],
            ],
            7 => [
                ['code' => null, 'title' => 'Forensic Psychology', 'group' => 'Major'],
                ['code' => null, 'title' => 'Advanced Crime Scene Reconstruction & Cold-Case Re-examination (Lab)', 'group' => 'Major'],
                ['code' => null, 'title' => 'Courtroom Testimony, Expert Witness Skills & Ethics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Fire & Arson Investigation (Lab)', 'group' => 'Major'],
                ['code' => null, 'title' => 'Forensic Evidence in Pakistani Criminal Trials', 'group' => 'Major'],
            ],
            8 => [
                ['code' => null, 'title' => 'Criminal Profiling', 'group' => 'Major'],
                ['code' => null, 'title' => 'Digital Evidence and E-Discovery', 'group' => 'Major'],
                ['code' => null, 'title' => 'DNA Analysis', 'group' => 'Major'],
                ['code' => null, 'title' => 'Bloodstain Pattern Analysis', 'group' => 'Major'],
                ['code' => null, 'title' => 'CSI Internship', 'group' => 'Internship'],
                ['code' => null, 'title' => 'Capstone Project', 'group' => 'FYP'],
            ],
        ],
        'bs-criminology-digital-forensics' => [
            1 => [
                ['code' => null, 'title' => 'Quantitative Reasoning', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Functional English', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Ideology and Constitution of Pakistan', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Introduction to Criminology', 'group' => 'Major'],
                ['code' => null, 'title' => 'Islamic Studies', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Introduction to Philosophy / Ethics (Optional)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Civics and Community Engagement', 'group' => 'General Education'],
            ],
            2 => [
                ['code' => null, 'title' => 'Quantitative Reasoning 2 / Statistics', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Expository Writing', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Entrepreneurship', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Chemical Sciences Applications', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Applications of Information Communication Technologies', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Criminal Justice System', 'group' => 'Major'],
            ],
            3 => [
                ['code' => null, 'title' => 'The Quranic Learning', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Computer Forensics Essentials', 'group' => 'Major'],
                ['code' => null, 'title' => 'Ethics of Social Services (Optional)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Criminal Psychology & Cyber-Victimology', 'group' => 'Major'],
                ['code' => null, 'title' => 'Operating Systems Fundamentals (Lab)', 'group' => 'Major'],
                ['code' => null, 'title' => 'Criminal Law', 'group' => 'Major'],
                ['code' => null, 'title' => 'Network Fundamentals & TCP/IP (Lab)', 'group' => 'Major'],
            ],
            4 => [
                ['code' => null, 'title' => 'Investigative Techniques', 'group' => 'Major'],
                ['code' => null, 'title' => 'Forensic Psychology for Investigators', 'group' => 'Major'],
                ['code' => null, 'title' => 'Database Management & Database Forensics (Lab)', 'group' => 'Major'],
                ['code' => null, 'title' => 'Qanun-e-Shahadat & Digital Evidence (Pakistan)', 'group' => 'Major'],
                ['code' => null, 'title' => 'Python Programming (Lab)', 'group' => 'Major'],
            ],
            5 => [
                ['code' => null, 'title' => 'Introduction to IoT Forensics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Shell Scripting', 'group' => 'Major'],
                ['code' => null, 'title' => 'Network Forensics', 'group' => 'Major'],
                ['code' => null, 'title' => 'OSINT & Analytics for Investigators', 'group' => 'Major'],
                ['code' => null, 'title' => 'Cyber Law in Pakistan: PECA 2016 & Electronic Crimes Procedure', 'group' => 'Major'],
                ['code' => null, 'title' => 'Advanced Computer Forensics', 'group' => 'Major'],
            ],
            6 => [
                ['code' => null, 'title' => 'Incident Response for Investigators', 'group' => 'Major'],
                ['code' => null, 'title' => 'Mobile Device Forensics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Video & Image Forensics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Research Methods in Criminology', 'group' => 'Major'],
                ['code' => null, 'title' => 'Cloud Forensics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Digital Ethics & Privacy Laws', 'group' => 'Major'],
            ],
            7 => [
                ['code' => null, 'title' => 'Cryptocurrency & Blockchain Forensics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Technical & Legal Report Writing and Expert Testimony for Investigators', 'group' => 'Major'],
                ['code' => null, 'title' => 'Anti-Money Laundering and Counter Financing of Terrorism', 'group' => 'Major'],
                ['code' => null, 'title' => 'Cyber Terrorism & Online Extremism', 'group' => 'Major'],
                ['code' => null, 'title' => 'Internship', 'group' => 'Internship'],
            ],
            8 => [
                ['code' => null, 'title' => 'Malware Forensics & Reverse Engineering (Lab)', 'group' => 'Major'],
                ['code' => null, 'title' => 'International and Federal INFOSEC Standards and Regulations', 'group' => 'Major'],
                ['code' => null, 'title' => 'Ethical Hacking for Investigators', 'group' => 'Major'],
                ['code' => null, 'title' => 'AI in Cyber Security', 'group' => 'Major'],
                ['code' => null, 'title' => 'Capstone Project', 'group' => 'FYP'],
            ],
        ],
        'mphil-mathematics' => [
            1 => [
                ['code' => null, 'title' => 'Advanced Group Theory', 'group' => 'Core Course'],
                ['code' => null, 'title' => 'Advanced Ordinary Differential Equations', 'group' => 'Core Course'],
                ['code' => null, 'title' => 'Advanced Numerical Analysis', 'group' => 'Core Course'],
                ['code' => null, 'title' => 'Applied Functional Analysis', 'group' => 'Core Course'],
            ],
            2 => [
                ['code' => null, 'title' => 'Numerical Solution of Integral Equations', 'group' => 'Core Course'],
                ['code' => null, 'title' => 'Advanced Complex Analysis', 'group' => 'Core Course'],
                ['code' => null, 'title' => 'Elective Course 1', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Elective Course 2', 'group' => 'Elective'],
            ],
            3 => [
                ['code' => null, 'title' => 'Thesis', 'group' => 'Thesis'],
            ],
            4 => [
                ['code' => null, 'title' => 'Thesis', 'group' => 'Thesis'],
            ],
        ],
        'bs-computational-mathematics-ai' => [
            1 => [
                ['code' => 'CMAI-6101', 'title' => 'Calculus & Analytical Geometry', 'group' => 'Major'],
                ['code' => 'CMAI-6402', 'title' => 'Artificial Intelligence', 'group' => 'Major'],
                ['code' => 'MATH-6101', 'title' => 'Quantitative Reasoning I', 'group' => 'General Education'],
                ['code' => 'COMPS6101', 'title' => 'Application of ICT', 'group' => 'General Education'],
                ['code' => 'ISL-6101/ETH-6102', 'title' => 'Islamic Studies / Ethics', 'group' => 'General Education'],
                ['code' => 'CCE-6101', 'title' => 'Civics & Community Engagement', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Foreign Language (Chinese)', 'group' => 'General Education'],
            ],
            2 => [
                ['code' => 'CMAI-6201', 'title' => 'Linear Algebra', 'group' => 'Major'],
                ['code' => 'CC-6101', 'title' => 'Programming Fundamentals', 'group' => 'Interdisciplinary'],
                ['code' => 'CMAI-6203', 'title' => 'Applied Statistics for AI', 'group' => 'Major'],
                ['code' => 'MATH-6102', 'title' => 'Quantitative Reasoning II', 'group' => 'General Education'],
                ['code' => 'EN-6202', 'title' => 'Functional English', 'group' => 'General Education'],
                ['code' => 'PAKS-6101', 'title' => 'Pakistan Studies', 'group' => 'General Education'],
            ],
            3 => [
                ['code' => 'CMAI-6301', 'title' => 'Discrete Mathematics', 'group' => 'Major'],
                ['code' => 'CMAI-6302', 'title' => 'Applied Probability for AI', 'group' => 'Major'],
                ['code' => 'CC-6202', 'title' => 'Object-Oriented Programming (C++)', 'group' => 'Interdisciplinary'],
                ['code' => 'EN-6302', 'title' => 'Expository Writing', 'group' => 'General Education'],
                ['code' => 'PSY-6101', 'title' => 'Introduction to Psychology', 'group' => 'General Education'],
                ['code' => 'PAK-6101', 'title' => 'Ideology and Constitution of Pakistan', 'group' => 'General Education'],
                ['code' => 'TQL-6405', 'title' => 'The Quranic Learning I', 'group' => 'General Education'],
            ],
            4 => [
                ['code' => 'CMAI-6401', 'title' => 'Multivariable Calculus', 'group' => 'Major'],
                ['code' => 'CMAI-6405', 'title' => 'Optimization Theory', 'group' => 'Major'],
                ['code' => 'CMAI-6404', 'title' => 'Machine Learning', 'group' => 'Major'],
                ['code' => 'CMAI-6403', 'title' => 'Modern Programming (Python)', 'group' => 'Major'],
                ['code' => 'TQL-6406', 'title' => 'The Quranic Learning II', 'group' => 'General Education'],
                ['code' => 'PHYS-6101', 'title' => 'Role of Physics in Modern Technologies', 'group' => 'General Education'],
                ['code' => 'BMT-6305', 'title' => 'Entrepreneurship', 'group' => 'General Education'],
            ],
            5 => [
                ['code' => 'CMAI-6501', 'title' => 'Ordinary Differential Equations', 'group' => 'Major'],
                ['code' => 'CC-6305', 'title' => 'Data Structures & Algorithms', 'group' => 'Interdisciplinary'],
                ['code' => 'CMAI-6504', 'title' => 'Programming for AI', 'group' => 'Major'],
                ['code' => 'CMAI-6503', 'title' => 'Numerical Computing', 'group' => 'Major'],
                ['code' => 'CMAI-6502', 'title' => 'Fuzzy Logic', 'group' => 'Major'],
            ],
            6 => [
                ['code' => 'CMAI-6601', 'title' => 'ANN & Deep Learning', 'group' => 'Major'],
                ['code' => 'CC-6203', 'title' => 'Database Systems', 'group' => 'Interdisciplinary'],
                ['code' => 'CMAI-6602', 'title' => 'Partial Differential Equations', 'group' => 'Major'],
                ['code' => 'CMAI-6603', 'title' => 'Cryptography', 'group' => 'Major'],
                ['code' => 'CMAI-6604', 'title' => 'Analysis of Algorithms', 'group' => 'Major'],
            ],
            7 => [
                ['code' => 'CMAI-6701', 'title' => 'SAP Data Intelligence', 'group' => 'Major'],
                ['code' => 'CMAI-6702', 'title' => 'Computational Graph Theory', 'group' => 'Major'],
                ['code' => null, 'title' => 'Domain Elective I', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Domain Elective II', 'group' => 'Elective'],
                ['code' => 'CMAI-6708', 'title' => 'Capstone Project I', 'group' => 'Major'],
            ],
            8 => [
                ['code' => null, 'title' => 'Research Methodology', 'group' => 'Interdisciplinary'],
                ['code' => 'CMAI-6801', 'title' => 'Professional Practices', 'group' => 'Major'],
                ['code' => 'CMAI-6802', 'title' => 'Generative AI', 'group' => 'Major'],
                ['code' => null, 'title' => 'Domain Elective III', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Domain Elective IV', 'group' => 'Elective'],
                ['code' => 'CMAI-6808', 'title' => 'Capstone Project II', 'group' => 'Major'],
            ],
        ],
        'bs-genetics' => [
            1 => [
                ['code' => null, 'title' => 'Cell Biology', 'group' => 'Major'],
                ['code' => null, 'title' => 'Quantitative Reasoning-I', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Functional English', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Biological Sciences', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Applications of Information & Communication Technologies', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Understanding of Holy Quran-I', 'group' => 'General Education'],
            ],
            2 => [
                ['code' => null, 'title' => 'Principles of Genetics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Biochemistry-I', 'group' => 'Major'],
                ['code' => null, 'title' => 'Quantitative Reasoning II', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Islamic Studies', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Professional Practices', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Expository Writing', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Pakistan Studies', 'group' => 'General Education'],
            ],
            3 => [
                ['code' => null, 'title' => 'Entrepreneurship', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Cytogenetics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Biochemistry II', 'group' => 'Major'],
                ['code' => null, 'title' => 'Introduction to Psychology', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Microbiology', 'group' => 'Interdisciplinary'],
                ['code' => null, 'title' => 'Plant and Animal Sciences', 'group' => 'Interdisciplinary'],
                ['code' => null, 'title' => 'Civics and Community Engagement', 'group' => 'General Education'],
            ],
            4 => [
                ['code' => null, 'title' => 'Biometry-I', 'group' => 'Major'],
                ['code' => null, 'title' => 'Evolutionary Genetics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Molecular Genetics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Molecular Biology', 'group' => 'Major'],
                ['code' => null, 'title' => 'Introduction to Biotechnology', 'group' => 'Major'],
                ['code' => null, 'title' => 'Understanding of Holy Quran-II', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Ideology and Constitution of Pakistan', 'group' => 'General Education'],
            ],
            5 => [
                ['code' => null, 'title' => 'Analytical Chemistry and Instrumentation', 'group' => 'Interdisciplinary'],
                ['code' => null, 'title' => 'Physiological Genetics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Genetic Engineering', 'group' => 'Major'],
                ['code' => null, 'title' => 'Microbial Genetics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Biometry-II', 'group' => 'Major'],
                ['code' => null, 'title' => 'Introduction to Forensics', 'group' => 'Interdisciplinary'],
            ],
            6 => [
                ['code' => null, 'title' => 'Bioinformatics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Biosafety and Bioethics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Developmental Genetics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Immunogenetics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Genetic Resources and Conservation', 'group' => 'Major'],
                ['code' => null, 'title' => 'Human Genetics', 'group' => 'Major'],
            ],
            7 => [
                ['code' => null, 'title' => 'Research Techniques', 'group' => 'Major'],
                ['code' => null, 'title' => 'Principles of Breeding', 'group' => 'Major'],
                ['code' => null, 'title' => 'Research Methodology', 'group' => 'Major'],
                ['code' => null, 'title' => 'Population Genetics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Field Experience / Internship', 'group' => 'Internship'],
            ],
            8 => [
                ['code' => null, 'title' => 'Capstone Project', 'group' => 'FYP'],
                ['code' => null, 'title' => 'Genetic Counselling', 'group' => 'Major'],
                ['code' => null, 'title' => 'Seminar', 'group' => 'Major'],
                ['code' => null, 'title' => 'Genomics and Proteomics', 'group' => 'Major'],
            ],
        ],
        'bs-biotechnology' => [
            1 => [
                ['code' => 'ALD-6201', 'title' => 'Professional Practices', 'group' => 'General Education'],
                ['code' => 'MATH-6101', 'title' => 'Quantitative Reasoning-I', 'group' => 'General Education'],
                ['code' => 'ISL-6101/ETH-6102', 'title' => 'Islamic Studies / Ethics', 'group' => 'General Education'],
                ['code' => 'COMPS-6101', 'title' => 'Applications of Information Communication Technologies', 'group' => 'General Education'],
                ['code' => 'BIO-6102', 'title' => 'Biological Sciences', 'group' => 'General Education'],
                ['code' => 'CCE-6101', 'title' => 'Civics and Community Engagement', 'group' => 'General Education'],
            ],
            2 => [
                ['code' => 'ENG-6102', 'title' => 'Functional English', 'group' => 'General Education'],
                ['code' => 'PAK-6101', 'title' => 'Ideology and Constitution of Pakistan', 'group' => 'General Education'],
                ['code' => 'BMT-6305', 'title' => 'Entrepreneurship', 'group' => 'General Education'],
                ['code' => 'UHQ-6405', 'title' => 'Understanding of Holy Quran-I', 'group' => 'General Education'],
                ['code' => 'BT-6201', 'title' => 'Structure and Functions of Biomolecules', 'group' => 'Major'],
                ['code' => 'BT-6202', 'title' => 'Microbiology', 'group' => 'Major'],
                ['code' => 'BT-6203', 'title' => 'Genetics', 'group' => 'Major'],
            ],
            3 => [
                ['code' => 'PSY-6101', 'title' => 'Introduction to Psychology', 'group' => 'General Education'],
                ['code' => 'ENG-6304', 'title' => 'Expository Writing', 'group' => 'General Education'],
                ['code' => 'BT-6301', 'title' => 'Metabolism of Biomolecules', 'group' => 'Major'],
                ['code' => 'BT-6302', 'title' => 'Cell Biology', 'group' => 'Major'],
                ['code' => 'BT-6303', 'title' => 'Molecular Biology', 'group' => 'Major'],
                ['code' => 'BT-6304', 'title' => 'Biotechnology', 'group' => 'Major'],
                ['code' => 'UHQ-6406', 'title' => 'Understanding of Holy Quran-II', 'group' => 'General Education'],
            ],
            4 => [
                ['code' => 'MATH-6102', 'title' => 'Quantitative Reasoning-II', 'group' => 'General Education'],
                ['code' => 'BT-6401', 'title' => 'Immunology', 'group' => 'Major'],
                ['code' => 'BT-6402', 'title' => 'Fundamentals of Ecology and Biodiversity', 'group' => 'Major'],
                ['code' => 'BT-6403', 'title' => 'Microbial Biotechnology', 'group' => 'Major'],
                ['code' => 'BT-6404', 'title' => 'Biosafety and Bioethics', 'group' => 'Major'],
                ['code' => 'BT-6405', 'title' => 'Food Biotechnology', 'group' => 'Major'],
                ['code' => 'PAKS-6101', 'title' => 'Pakistan Studies', 'group' => 'General Education'],
            ],
            5 => [
                ['code' => 'BT-6501', 'title' => 'Methods in Molecular Biology', 'group' => 'Major'],
                ['code' => 'BT-6502', 'title' => 'Genetic Resources and Conservation', 'group' => 'Major'],
                ['code' => 'BT-6503', 'title' => 'Industrial Biotechnology', 'group' => 'Major'],
                ['code' => 'BT-6504', 'title' => 'Health Biotechnology', 'group' => 'Major'],
                ['code' => 'BT-6505', 'title' => 'Environmental Biotechnology', 'group' => 'Major'],
                ['code' => 'CHEM-6105', 'title' => 'Principles of Organic Chemistry', 'group' => 'Interdisciplinary'],
            ],
            6 => [
                ['code' => 'BT-6601', 'title' => 'Fungal Biotechnology', 'group' => 'Major'],
                ['code' => 'BT-6602', 'title' => 'Bioinformatics', 'group' => 'Major'],
                ['code' => 'BT-6603', 'title' => 'Research Methodology and Skill Enhancement', 'group' => 'Major'],
                ['code' => 'BT-6604', 'title' => 'Molecular Diagnostics', 'group' => 'Major'],
                ['code' => 'BT-6605', 'title' => 'Pharmaceutical Biotechnology', 'group' => 'Major'],
                ['code' => 'CHEM-6203', 'title' => 'Principles of Inorganic Chemistry', 'group' => 'Interdisciplinary'],
            ],
            7 => [
                ['code' => 'BT-6701', 'title' => 'Recombinant DNA Technology', 'group' => 'Major'],
                ['code' => 'BT-6702', 'title' => 'Genomics and Proteomics', 'group' => 'Major'],
                ['code' => 'BT-6703', 'title' => 'Agriculture Biotechnology', 'group' => 'Major'],
                ['code' => 'BT-6704', 'title' => 'Animal Biotechnology', 'group' => 'Major'],
                ['code' => 'BT-6705', 'title' => 'Internship', 'group' => 'Major'],
                ['code' => 'PHYS-6707', 'title' => 'Biological Physics', 'group' => 'Interdisciplinary'],
            ],
            8 => [
                ['code' => 'BT-6801', 'title' => 'Nanobiotechnology', 'group' => 'Major'],
                ['code' => 'BT-6802', 'title' => 'Capstone Project', 'group' => 'Major'],
                ['code' => 'BT-6803', 'title' => 'Principles of Biochemical Engineering', 'group' => 'Major'],
                ['code' => 'BMT-6303', 'title' => 'Principles of Marketing', 'group' => 'Interdisciplinary'],
                ['code' => 'BT-6804', 'title' => 'Artificial Intelligence in Biotechnology', 'group' => 'Major'],
            ],
        ],
        'bs-human-nutrition-dietetics' => [
            1 => [
                ['code' => null, 'title' => 'Professional Practices', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Quantitative Reasoning-I', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Islamic Studies / Ethics', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Applications of ICT', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Biological Sciences', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Civics & Community Engagement', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Human Anatomy', 'group' => 'Major'],
            ],
            2 => [
                ['code' => null, 'title' => 'Quantitative Reasoning-II', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Ideology and Constitution of Pakistan', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Functional English', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Understanding of Holy Quran-I', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Macro and Micro Nutrients', 'group' => 'Major'],
                ['code' => null, 'title' => 'Fundamentals of Human Nutrition & Dietetics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Human Physiology', 'group' => 'Major'],
            ],
            3 => [
                ['code' => null, 'title' => 'Introduction to Psychology', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Expository Writing', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Pakistan Studies', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Understanding of Holy Quran-II', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Functional Foods & Nutraceuticals', 'group' => 'Major'],
                ['code' => null, 'title' => 'Food Safety & Quality Management', 'group' => 'Major'],
                ['code' => null, 'title' => 'Food Microbiology and Biotechnology', 'group' => 'Major'],
            ],
            4 => [
                ['code' => null, 'title' => 'Entrepreneurship', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Fundamentals of Food Systems', 'group' => 'Major'],
                ['code' => null, 'title' => 'Introduction to Food Science and Technology', 'group' => 'Major'],
                ['code' => null, 'title' => 'Food Service Management', 'group' => 'Major'],
                ['code' => null, 'title' => 'Clinical Biochemistry', 'group' => 'Major'],
                ['code' => null, 'title' => 'General Pathology', 'group' => 'Major'],
            ],
            5 => [
                ['code' => null, 'title' => 'Analytical Techniques in Food and Nutrition', 'group' => 'Major'],
                ['code' => null, 'title' => 'Community and Public Health Nutrition', 'group' => 'Major'],
                ['code' => null, 'title' => 'Nutrition through Life Cycle', 'group' => 'Major'],
                ['code' => null, 'title' => 'Meal Planning and Management', 'group' => 'Major'],
                ['code' => null, 'title' => 'Nutritional Assessment', 'group' => 'Major'],
                ['code' => null, 'title' => 'Dietetics-I', 'group' => 'Major'],
            ],
            6 => [
                ['code' => null, 'title' => 'Food & Drug Laws and Regulations', 'group' => 'Major'],
                ['code' => null, 'title' => 'Food Product Development', 'group' => 'Major'],
                ['code' => null, 'title' => 'Dietetics-II', 'group' => 'Major'],
                ['code' => null, 'title' => 'Supervised Practicum-I', 'group' => 'Internship'],
                ['code' => null, 'title' => 'Sports Nutrition and Exercise', 'group' => 'Major'],
                ['code' => null, 'title' => 'Nutritional Practices in Critical Care', 'group' => 'Major'],
                ['code' => null, 'title' => 'Diet Therapy for Individuals with Special Needs', 'group' => 'Major'],
            ],
            7 => [
                ['code' => null, 'title' => 'AI in Food and Nutrition', 'group' => 'Major'],
                ['code' => null, 'title' => 'Research Methods in Food and Nutrition', 'group' => 'Major'],
                ['code' => null, 'title' => 'Food and Nutrition Certifications', 'group' => 'Major'],
                ['code' => null, 'title' => 'Supervised Practicum-II', 'group' => 'Internship'],
                ['code' => null, 'title' => 'Nutrition Education & Counseling', 'group' => 'Major'],
                ['code' => null, 'title' => 'Preventive Nutrition', 'group' => 'Major'],
                ['code' => null, 'title' => 'Nutrition through Social Protection', 'group' => 'Major'],
            ],
            8 => [
                ['code' => null, 'title' => 'Food and Nutrition Policies', 'group' => 'Major'],
                ['code' => null, 'title' => 'Nutritional Epidemiology', 'group' => 'Major'],
                ['code' => null, 'title' => 'Food and Nutrition Entrepreneurship', 'group' => 'Major'],
                ['code' => null, 'title' => 'Nutrition in Emergencies', 'group' => 'Major'],
                ['code' => null, 'title' => 'Capstone Project', 'group' => 'FYP'],
            ],
        ],
        'bs-economics-data-analytics' => [
            1 => [
                ['code' => 'ECO', 'title' => 'Microeconomics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Data Science Fundamentals', 'group' => 'Allied Discipline'],
                ['code' => 'ENG6102', 'title' => 'Functional English', 'group' => 'General Education'],
                ['code' => 'MATHS6101', 'title' => 'Quantitative Reasoning I', 'group' => 'General Education'],
                ['code' => 'COMPS6101', 'title' => 'Applications of ICT', 'group' => 'General Education'],
                ['code' => 'ISL6101/ISL6102', 'title' => 'Islamic Studies / Ethics', 'group' => 'General Education'],
                ['code' => 'TQL6405/ESS6406', 'title' => 'The Quranic Learning / Ethics of Social Services', 'group' => 'General Education'],
            ],
            2 => [
                ['code' => 'ECO', 'title' => 'Macroeconomics', 'group' => 'Major'],
                ['code' => 'MATHS6102', 'title' => 'Quantitative Reasoning II', 'group' => 'General Education'],
                ['code' => 'ECO', 'title' => 'Statistics', 'group' => 'Major'],
                ['code' => 'PAK6101', 'title' => 'Ideology and Constitution of Pakistan', 'group' => 'General Education'],
                ['code' => 'ENG6304', 'title' => 'Expository Writing', 'group' => 'General Education'],
                ['code' => 'CCE6101', 'title' => 'Civics and Community Engagement', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Pakistan Studies', 'group' => 'General Education'],
            ],
            3 => [
                ['code' => 'ECO', 'title' => 'Advanced Microeconomics', 'group' => 'Major'],
                ['code' => 'ECO', 'title' => 'Data Analysis and Statistical Methods', 'group' => 'Major'],
                ['code' => 'ECO', 'title' => 'Issues in Pakistan Economy', 'group' => 'Major'],
                ['code' => null, 'title' => 'Artificial Intelligence', 'group' => 'Allied Discipline'],
                ['code' => null, 'title' => 'Mathematical Economics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Entrepreneurship', 'group' => 'General Education'],
            ],
            4 => [
                ['code' => 'ECO', 'title' => 'Advanced Macroeconomics', 'group' => 'Major'],
                ['code' => 'ECO', 'title' => 'Islamic Economics', 'group' => 'Major'],
                ['code' => 'ECO', 'title' => 'Econometrics I', 'group' => 'Major'],
                ['code' => 'BIO6005/BIO6003', 'title' => 'Ecology / Environmental Sciences', 'group' => 'General Education'],
                ['code' => 'ECO', 'title' => 'Introduction to Environmental Economics', 'group' => 'Major'],
                ['code' => 'CHINESE6401', 'title' => 'Chinese Language', 'group' => 'General Education'],
            ],
            5 => [
                ['code' => 'ECO', 'title' => 'International Economics', 'group' => 'Major'],
                ['code' => 'ECO', 'title' => 'Research Methods', 'group' => 'Major'],
                ['code' => 'ECO', 'title' => 'Applied Econometrics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Data Visualization Technique for Economist', 'group' => 'Minor'],
                ['code' => null, 'title' => 'Resource Economics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Development Economics', 'group' => 'Major'],
            ],
            6 => [
                ['code' => 'ECO', 'title' => 'Monetary Theory', 'group' => 'Major'],
                ['code' => 'ECO', 'title' => 'Project Appraisal', 'group' => 'Major'],
                ['code' => 'ECO', 'title' => 'Managerial Economics', 'group' => 'Major'],
                ['code' => 'ECO', 'title' => 'Economics of Digital Innovation', 'group' => 'Major'],
                ['code' => null, 'title' => 'Programming Fundamentals', 'group' => 'Minor'],
                ['code' => 'COMS6105', 'title' => 'Management Information System', 'group' => 'Allied'],
            ],
            7 => [
                ['code' => 'ECO', 'title' => 'Banking and Finance', 'group' => 'Major'],
                ['code' => 'ECO', 'title' => 'Introduction to Game Theory', 'group' => 'Major'],
                ['code' => 'ECO', 'title' => 'Agriculture Economics and Food Security', 'group' => 'Major'],
                ['code' => 'ECO', 'title' => 'Behavioral Economics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Data Structures and Algorithms', 'group' => 'Minor'],
                ['code' => 'FYP6700', 'title' => 'Final Year Project 1', 'group' => 'FYP'],
            ],
            8 => [
                ['code' => 'ECO', 'title' => 'Green Innovation and Digital Sustainability', 'group' => 'Major'],
                ['code' => 'ECO', 'title' => 'Fiscal Policy and Public Finance', 'group' => 'Major'],
                ['code' => 'ECO', 'title' => 'WTO, Globalization & Economic Integration', 'group' => 'Major'],
                ['code' => null, 'title' => 'Platform and Architecture for Data Science', 'group' => 'Minor'],
                ['code' => 'FYP6700', 'title' => 'Final Year Project 2', 'group' => 'FYP'],
            ],
        ],
        'bs-accounting-finance' => [
            1 => [
                ['code' => 'BAF6101', 'title' => 'Principles of Accounting', 'group' => 'Major'],
                ['code' => 'BAF6102', 'title' => 'Business Fundamentals and Applications', 'group' => 'Major'],
                ['code' => 'ENG6102', 'title' => 'Functional English', 'group' => 'General Education'],
                ['code' => 'MATH6101', 'title' => 'Quantitative Reasoning (I)', 'group' => 'General Education'],
                ['code' => 'COMPS6101', 'title' => 'Applications of Information and Communication Technologies', 'group' => 'General Education'],
                ['code' => 'ISL6101/ISL6102', 'title' => 'Islamic Studies / Ethics', 'group' => 'General Education'],
                ['code' => 'TQL6405/ESS6406', 'title' => 'The Quranic Learning / Ethics of Social Services', 'group' => 'General Education'],
            ],
            2 => [
                ['code' => 'BAF6201', 'title' => 'Microeconomics', 'group' => 'Major'],
                ['code' => 'BAF6202', 'title' => 'Management Theory and Practice', 'group' => 'Major'],
                ['code' => 'BAF6203', 'title' => 'Financial Accounting', 'group' => 'Major'],
                ['code' => 'PAK6101', 'title' => 'Ideology and Constitution of Pakistan', 'group' => 'General Education'],
                ['code' => 'ENG6304', 'title' => 'Expository Writing', 'group' => 'General Education'],
                ['code' => 'CCE6101', 'title' => 'Civics and Community Engagement', 'group' => 'General Education'],
            ],
            3 => [
                ['code' => 'BAF6301', 'title' => 'Human Resource Management', 'group' => 'Major'],
                ['code' => 'BAF6302', 'title' => 'Financial Reporting', 'group' => 'Major'],
                ['code' => 'BAF6303', 'title' => 'Marketing Management', 'group' => 'Major'],
                ['code' => 'BAF6304', 'title' => 'Financial Management', 'group' => 'Major'],
                ['code' => 'PSY6101', 'title' => 'Introduction to Psychology', 'group' => 'General Education'],
                ['code' => 'BMT6305', 'title' => 'Entrepreneurship', 'group' => 'General Education'],
            ],
            4 => [
                ['code' => 'BAF6401', 'title' => 'Financial Markets and Institutions', 'group' => 'Major'],
                ['code' => 'BAF6402', 'title' => 'Business Taxation', 'group' => 'Major'],
                ['code' => 'BAF6403', 'title' => 'Macroeconomics', 'group' => 'Major'],
                ['code' => 'BIO6005/BIO6003', 'title' => 'Ecology / Environmental Sciences', 'group' => 'General Education'],
                ['code' => 'MATH6102', 'title' => 'Quantitative Reasoning (II)', 'group' => 'General Education'],
                ['code' => 'CHINESE6401', 'title' => 'Chinese Language', 'group' => 'General Education'],
            ],
            5 => [
                ['code' => 'BAF6501', 'title' => 'Corporate Finance', 'group' => 'Major'],
                ['code' => 'BAF6502', 'title' => 'Cost Accounting', 'group' => 'Major'],
                ['code' => 'BAF6503', 'title' => 'Principles of Risk Management & Insurance', 'group' => 'Major'],
                ['code' => null, 'title' => 'Minor 1', 'group' => 'Minor'],
                ['code' => 'MCN6404', 'title' => 'Fundamentals of Public Relations and Advertising', 'group' => 'Allied'],
                ['code' => 'COMPS6104', 'title' => 'E-Commerce', 'group' => 'Allied'],
            ],
            6 => [
                ['code' => 'BAF6601', 'title' => 'Auditing', 'group' => 'Major'],
                ['code' => 'BAF6602', 'title' => 'Project Management', 'group' => 'Major'],
                ['code' => 'BAF6603', 'title' => 'Business Research Methods', 'group' => 'Major'],
                ['code' => null, 'title' => 'Minor 2', 'group' => 'Minor'],
                ['code' => 'IRT6501', 'title' => 'International Trade', 'group' => 'Allied'],
                ['code' => 'COMS6105', 'title' => 'Management Information System', 'group' => 'Allied'],
            ],
            7 => [
                ['code' => 'BAF6701', 'title' => 'Entrepreneurial Finance', 'group' => 'Major'],
                ['code' => 'BAF6702', 'title' => 'Investment & Portfolio Management', 'group' => 'Major'],
                ['code' => 'BAF6703', 'title' => 'Islamic Banking Practices', 'group' => 'Major'],
                ['code' => 'BAF6704', 'title' => 'Corporate Governance', 'group' => 'Major'],
                ['code' => null, 'title' => 'Minor 3', 'group' => 'Minor'],
                ['code' => 'FYP6700', 'title' => 'Final Year Project', 'group' => 'FYP'],
            ],
            8 => [
                ['code' => 'BAF6801', 'title' => 'Corporate and Legal Affairs', 'group' => 'Major'],
                ['code' => 'BAF6802', 'title' => 'International Finance', 'group' => 'Major'],
                ['code' => 'BAF6803', 'title' => 'Accounting Information System', 'group' => 'Major'],
                ['code' => 'BAF6804', 'title' => 'FinTech', 'group' => 'Major'],
                ['code' => null, 'title' => 'Minor 4', 'group' => 'Minor'],
                ['code' => 'FYP6700', 'title' => 'Final Year Project (Continued)', 'group' => 'FYP'],
            ],
        ],
        'bba' => [
            1 => [
                ['code' => 'BMT6101', 'title' => 'Principles of Accounting', 'group' => 'Major'],
                ['code' => 'BMT6102', 'title' => 'Business Fundamentals and Applications', 'group' => 'Major'],
                ['code' => 'ENG6102', 'title' => 'Functional English', 'group' => 'General Education'],
                ['code' => 'MATH6101', 'title' => 'Quantitative Reasoning (I)', 'group' => 'General Education'],
                ['code' => 'COMPS6101', 'title' => 'Applications of Information and Communication Technologies', 'group' => 'General Education'],
                ['code' => 'ISL6101/ISL6102', 'title' => 'Islamic Studies / Ethics', 'group' => 'General Education'],
                ['code' => 'TQL6405/ESS6406', 'title' => 'The Quranic Learning / Ethics of Social Services', 'group' => 'General Education'],
            ],
            2 => [
                ['code' => 'BMT6201', 'title' => 'Microeconomics', 'group' => 'Major'],
                ['code' => 'BMT6202', 'title' => 'Management Theory and Practice', 'group' => 'Major'],
                ['code' => 'BMT6203', 'title' => 'Financial Accounting', 'group' => 'Major'],
                ['code' => 'PAK6101', 'title' => 'Ideology and Constitution of Pakistan', 'group' => 'General Education'],
                ['code' => 'ENG6304', 'title' => 'Expository Writing', 'group' => 'General Education'],
                ['code' => 'CCE6101', 'title' => 'Civics and Community Engagement', 'group' => 'General Education'],
            ],
            3 => [
                ['code' => 'BMT6301', 'title' => 'Human Resource Management', 'group' => 'Major'],
                ['code' => 'BMT6302', 'title' => 'Supply Chain Essentials', 'group' => 'Major'],
                ['code' => 'BMT6303', 'title' => 'Marketing Management', 'group' => 'Major'],
                ['code' => 'BMT6304', 'title' => 'Financial Management', 'group' => 'Major'],
                ['code' => 'PSY6101', 'title' => 'Introduction to Psychology', 'group' => 'General Education'],
                ['code' => 'BMT6305', 'title' => 'Entrepreneurship', 'group' => 'General Education'],
            ],
            4 => [
                ['code' => 'BMT6401', 'title' => 'Contemporary Practices in Business', 'group' => 'Major'],
                ['code' => 'BMT6402', 'title' => 'Digital Marketing', 'group' => 'Major'],
                ['code' => 'BMT6403', 'title' => 'Macroeconomics', 'group' => 'Major'],
                ['code' => 'BIO6005/BIO6003', 'title' => 'Ecology / Environmental Sciences', 'group' => 'General Education'],
                ['code' => 'MATH6102', 'title' => 'Quantitative Reasoning (II)', 'group' => 'General Education'],
                ['code' => 'CHINESE6401', 'title' => 'Chinese Language', 'group' => 'General Education'],
            ],
            5 => [
                ['code' => 'BMT6501', 'title' => 'Organizational Behavior', 'group' => 'Major'],
                ['code' => 'BMT6502', 'title' => 'Cost Accounting', 'group' => 'Major'],
                ['code' => 'BMT6503', 'title' => 'Total Quality Management', 'group' => 'Major'],
                ['code' => null, 'title' => 'Minor 1', 'group' => 'Minor'],
                ['code' => 'MCN6404', 'title' => 'Fundamentals of Public Relations and Advertising', 'group' => 'Allied'],
                ['code' => 'COMPS6104', 'title' => 'E-Commerce', 'group' => 'Allied'],
            ],
            6 => [
                ['code' => 'BMT6601', 'title' => 'Operations Management', 'group' => 'Major'],
                ['code' => 'BMT6602', 'title' => 'New Venture Creation', 'group' => 'Major'],
                ['code' => 'BMT6603', 'title' => 'Business Research Methods', 'group' => 'Major'],
                ['code' => null, 'title' => 'Minor 2', 'group' => 'Minor'],
                ['code' => 'IRT6501', 'title' => 'International Trade', 'group' => 'Allied'],
                ['code' => 'COMS6105', 'title' => 'Management Information System', 'group' => 'Allied'],
            ],
            7 => [
                ['code' => 'BMT6701', 'title' => 'Strategic Management', 'group' => 'Major'],
                ['code' => 'BMT6702', 'title' => 'Project Management', 'group' => 'Major'],
                ['code' => null, 'title' => 'Specialization 1', 'group' => 'Specialization'],
                ['code' => null, 'title' => 'Specialization 2', 'group' => 'Specialization'],
                ['code' => null, 'title' => 'Minor 3', 'group' => 'Minor'],
                ['code' => 'FYP6700', 'title' => 'Final Year Project', 'group' => 'FYP'],
            ],
            8 => [
                ['code' => 'BMT6801', 'title' => 'Corporate and Legal Affairs', 'group' => 'Major'],
                ['code' => 'BMT6802', 'title' => 'Business Ethics and Corporate Governance', 'group' => 'Major'],
                ['code' => null, 'title' => 'Specialization 3', 'group' => 'Specialization'],
                ['code' => null, 'title' => 'Specialization 4', 'group' => 'Specialization'],
                ['code' => null, 'title' => 'Minor 4', 'group' => 'Minor'],
                ['code' => 'FYP6700', 'title' => 'Final Year Project (Continued)', 'group' => 'FYP'],
            ],
        ],
        'phd-management-sciences' => [
            1 => [
                ['code' => null, 'title' => 'Quantitative Methods in Management Research', 'group' => 'Core Course'],
                ['code' => null, 'title' => 'Theories in Management Sciences', 'group' => 'Core Course'],
                ['code' => null, 'title' => 'Academic Writing', 'group' => 'Core Course'],
            ],
            2 => [
                ['code' => null, 'title' => 'Qualitative Methods in Management Research', 'group' => 'Core Course'],
                ['code' => null, 'title' => 'Emerging Issues in Management Research', 'group' => 'Core Course'],
                ['code' => null, 'title' => 'Elective (e.g. Econometrics, Work & Organizational Psychology, Corporate Strategy)', 'group' => 'Elective'],
            ],
            3 => [
                ['code' => null, 'title' => 'Comprehensive Examination, Research Proposal & Dissertation (Semester 3 onward, 30 credit hours)', 'group' => 'Research'],
            ],
        ],
        'mba-nonbusiness' => [
            1 => [
                ['code' => 'BMT735', 'title' => 'Financial Accounting', 'group' => 'Core'],
                ['code' => 'BMT732', 'title' => 'Business Economics — Theory & Application', 'group' => 'Core'],
                ['code' => 'BMT733', 'title' => 'Business Mathematics and Statistics', 'group' => 'Core'],
                ['code' => 'BMT722', 'title' => 'Organizational Behavior', 'group' => 'Core'],
                ['code' => 'BMT734', 'title' => 'Marketing Management', 'group' => 'Core'],
            ],
            2 => [
                ['code' => 'BMT740', 'title' => 'Supply Chain Management', 'group' => 'Core'],
                ['code' => 'BMT736', 'title' => 'Financial Management', 'group' => 'Core'],
                ['code' => 'BMT711', 'title' => 'Business Research Methods', 'group' => 'Core'],
                ['code' => 'BMT729', 'title' => 'Advances in Accounting', 'group' => 'Core'],
                ['code' => null, 'title' => 'Elective 1', 'group' => 'Elective'],
            ],
            3 => [
                ['code' => 'BMT739', 'title' => 'Corporate Finance', 'group' => 'Core'],
                ['code' => 'BMT727', 'title' => 'Strategic Marketing', 'group' => 'Core'],
                ['code' => 'BMT728', 'title' => 'Managerial Economics', 'group' => 'Core'],
                ['code' => null, 'title' => 'Elective 2', 'group' => 'Elective'],
                ['code' => 'FYP785', 'title' => 'Final Year Project', 'group' => 'FYP'],
            ],
            4 => [
                ['code' => null, 'title' => 'Elective 3', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Elective 4', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Elective 5', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Elective 6', 'group' => 'Elective'],
                ['code' => 'FYP785', 'title' => 'Final Year Project', 'group' => 'FYP'],
            ],
        ],
        'bs-digital-business' => [
            1 => [
                ['code' => 'ENG6102', 'title' => 'Functional English', 'group' => 'General Education'],
                ['code' => 'MATH6101', 'title' => 'Quantitative Reasoning (I)', 'group' => 'General Education'],
                ['code' => 'COMPS6101', 'title' => 'Applications of ICT', 'group' => 'General Education'],
                ['code' => 'ISL6101/ISL6102', 'title' => 'Islamic Studies / Ethics', 'group' => 'General Education'],
                ['code' => 'TQL6405/ESS6406', 'title' => 'The Quranic Learning / Ethics of Social Services', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Introduction to Business & Digital Economy', 'group' => 'Major'],
                ['code' => null, 'title' => 'Fundamentals of Programming (Python for Business)', 'group' => 'Major'],
            ],
            2 => [
                ['code' => 'ENG6304', 'title' => 'Expository Writing', 'group' => 'General Education'],
                ['code' => 'MATH6102', 'title' => 'Quantitative Reasoning (II)', 'group' => 'General Education'],
                ['code' => 'PAK6101', 'title' => 'Ideology & Constitution of Pakistan', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Financial Accounting', 'group' => 'Major'],
                ['code' => null, 'title' => 'E-Commerce, Digital Business Models & Strategy', 'group' => 'Major'],
                ['code' => 'CCE6101', 'title' => 'Civics & Community Engagement', 'group' => 'General Education'],
            ],
            3 => [
                ['code' => 'CHINESE6401', 'title' => 'Chinese Language', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Principles of Management', 'group' => 'Major'],
                ['code' => null, 'title' => 'Introduction to AI & Machine Learning', 'group' => 'Major'],
                ['code' => null, 'title' => 'Financial Markets & Fintech', 'group' => 'Major'],
                ['code' => 'PSY6101', 'title' => 'Introduction to Psychology', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Digital Marketing & Social Media Strategy', 'group' => 'Major'],
            ],
            4 => [
                ['code' => null, 'title' => 'Entrepreneurship', 'group' => 'Major'],
                ['code' => 'BIO6005', 'title' => 'Ecology / Environmental Sciences', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Management and Economics of Digital Innovation', 'group' => 'Major'],
                ['code' => null, 'title' => 'Data Visualization & Business Intelligence', 'group' => 'Major'],
                ['code' => null, 'title' => 'Cybersecurity & Digital Forensics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Digital Innovation & Design Thinking', 'group' => 'Major'],
            ],
            5 => [
                ['code' => null, 'title' => 'Project Management in Digital Business', 'group' => 'Major'],
                ['code' => null, 'title' => 'Blockchain & Cryptocurrency', 'group' => 'Major'],
                ['code' => null, 'title' => 'Business Process Automation', 'group' => 'Major'],
                ['code' => null, 'title' => 'Cloud Computing & SaaS', 'group' => 'Major'],
                ['code' => null, 'title' => 'Specialization Elective', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective', 'group' => 'Elective'],
            ],
            6 => [
                ['code' => null, 'title' => 'Digital Transformation & Change Management', 'group' => 'Major'],
                ['code' => null, 'title' => 'Legal & Regulatory Aspects of Digital Business', 'group' => 'Major'],
                ['code' => null, 'title' => 'Managerial Economics', 'group' => 'Major'],
                ['code' => null, 'title' => 'Business Communication in Digital Age', 'group' => 'Major'],
                ['code' => null, 'title' => 'Specialization Elective', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective', 'group' => 'Elective'],
            ],
            7 => [
                ['code' => null, 'title' => 'Industry Internship', 'group' => 'Internship'],
                ['code' => null, 'title' => 'User Experience (UX) & Digital Product Development', 'group' => 'Major'],
                ['code' => null, 'title' => 'Predictive Analytics & AI', 'group' => 'Major'],
                ['code' => null, 'title' => 'Specialization Elective', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective', 'group' => 'Elective'],
            ],
            8 => [
                ['code' => 'BDB FYP', 'title' => 'Capstone Project', 'group' => 'FYP'],
                ['code' => null, 'title' => 'International Digital Business', 'group' => 'Major'],
                ['code' => null, 'title' => 'Digital Ethics & Governance', 'group' => 'Major'],
                ['code' => null, 'title' => 'Specialization Elective', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective', 'group' => 'Elective'],
            ],
        ],
        'ms-data-science' => [
            1 => [
                ['code' => 'DSC7101', 'title' => 'Tools and Techniques in Data Science', 'group' => 'Core Course'],
                ['code' => 'DSC7102', 'title' => 'Statistical and Mathematical Methods for Data Science', 'group' => 'Core Course'],
                ['code' => 'CC7101', 'title' => 'Research Methodology', 'group' => 'Core Course'],
                ['code' => null, 'title' => 'Elective I', 'group' => 'Elective'],
            ],
            2 => [
                ['code' => 'DSC7103', 'title' => 'Machine Learning', 'group' => 'Core Course'],
                ['code' => null, 'title' => 'Elective I', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective I', 'group' => 'Elective'],
            ],
            3 => [
                ['code' => null, 'title' => 'Specialization Elective II', 'group' => 'Elective'],
                ['code' => 'DSE7301', 'title' => 'MS Thesis I', 'group' => 'Thesis'],
            ],
            4 => [
                ['code' => 'DSE7401', 'title' => 'Thesis II', 'group' => 'Thesis'],
            ],
        ],
        'ms-artificial-intelligence' => [
            1 => [
                ['code' => null, 'title' => 'Advanced Artificial Intelligence', 'group' => 'Core Course'],
                ['code' => null, 'title' => 'Advanced Machine Learning', 'group' => 'Core Course'],
                ['code' => null, 'title' => 'Research Methodology', 'group' => 'Core Course'],
                ['code' => null, 'title' => 'Advanced Cloud Computing', 'group' => 'Core Course'],
            ],
            2 => [
                ['code' => null, 'title' => 'Optimization Techniques', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Philosophy of Artificial Intelligence', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Advanced Image Processing', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Digital Forensics', 'group' => 'Elective'],
            ],
            3 => [
                ['code' => null, 'title' => 'Data Mining', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Thesis (Partial Registration) – I', 'group' => 'Thesis'],
            ],
            4 => [
                ['code' => null, 'title' => 'Thesis (Partial Registration) – II', 'group' => 'Thesis'],
            ],
        ],
        'ms-computer-science' => [
            1 => [
                ['code' => null, 'title' => 'Core Course – I', 'group' => 'Core Course'],
                ['code' => null, 'title' => 'Core Course – II', 'group' => 'Core Course'],
                ['code' => 'CC7101', 'title' => 'Research Methodology', 'group' => 'Core Course'],
                ['code' => null, 'title' => 'Elective – I', 'group' => 'Elective'],
            ],
            2 => [
                ['code' => null, 'title' => 'Core Course – III', 'group' => 'Core Course'],
                ['code' => null, 'title' => 'Core Course – IV', 'group' => 'Core Course'],
                ['code' => null, 'title' => 'Elective – II', 'group' => 'Elective'],
            ],
            3 => [
                ['code' => null, 'title' => 'Elective – III', 'group' => 'Elective'],
                ['code' => 'CSE7401', 'title' => 'Thesis I', 'group' => 'Thesis'],
            ],
            4 => [
                ['code' => 'CSE7402', 'title' => 'Thesis II', 'group' => 'Thesis'],
            ],
        ],
        'bs-cyber-security' => [
            1 => [
                ['code' => null, 'title' => 'Programming Fundamentals', 'group' => 'Major (CC 1)'],
                ['code' => null, 'title' => 'Introduction to Cyber Security', 'group' => 'Major (CYS 1)'],
                ['code' => null, 'title' => 'Applied Physics (Natural Science)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Functional English', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Applications of Information and Communication Technologies', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Islamic Studies / Ethics', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Fundamental of Math – I', 'group' => 'GER'],
            ],
            2 => [
                ['code' => null, 'title' => 'Object Oriented Programming', 'group' => 'Major (CC 2)'],
                ['code' => null, 'title' => 'Digital Logic Design', 'group' => 'Major (CC 3)'],
                ['code' => null, 'title' => 'Expository Writing', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Quantitative Reasoning – I', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Professional Practices (Arts and Humanities)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Ideology and Constitution of Pakistan', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Fundamental of Math – II', 'group' => 'GER'],
            ],
            3 => [
                ['code' => null, 'title' => 'Data Structures and Algorithms', 'group' => 'Major (CC 4)'],
                ['code' => null, 'title' => 'Computer Networks', 'group' => 'Major (CC 5)'],
                ['code' => null, 'title' => 'Information Security Management', 'group' => 'Major (CYS 2)'],
                ['code' => null, 'title' => 'Discrete Structures (IDS – I)', 'group' => 'Interdisciplinary'],
                ['code' => null, 'title' => 'Fehm-e-Quran – I (for Muslim Students)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Quantitative Reasoning – II', 'group' => 'General Education'],
            ],
            4 => [
                ['code' => null, 'title' => 'Operating Systems', 'group' => 'Major (CC 6)'],
                ['code' => null, 'title' => 'Network Security', 'group' => 'Major (CYS 3)'],
                ['code' => null, 'title' => 'Calculus and Analytical Geometry (IDS – II)', 'group' => 'Interdisciplinary'],
                ['code' => null, 'title' => 'Civics and Community Engagement', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Entrepreneurship', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Fehm-e-Quran – II (for Muslim Students)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Internship', 'group' => 'Field Experience'],
            ],
            5 => [
                ['code' => null, 'title' => 'Computer Organization and Architecture', 'group' => 'Major (CC 7)'],
                ['code' => null, 'title' => 'Cryptography', 'group' => 'Major (CYS 4)'],
                ['code' => null, 'title' => 'Secure Software Design and Development', 'group' => 'Major (CYS 5)'],
                ['code' => null, 'title' => 'Specialization Elective – I', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Artificial Intelligence (IDS – III)', 'group' => 'Interdisciplinary'],
                ['code' => null, 'title' => 'Professional Certification', 'group' => 'Certification'],
            ],
            6 => [
                ['code' => null, 'title' => 'Vulnerability Assessment and Penetration Testing', 'group' => 'Major (CYS 6)'],
                ['code' => null, 'title' => 'Digital Forensics and Incident Response', 'group' => 'Major (CYS 7)'],
                ['code' => null, 'title' => 'Database Systems', 'group' => 'Major (CC 8)'],
                ['code' => null, 'title' => 'Specialization Elective – II', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Introduction to Management (Social Science)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Pakistan Studies', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Final Year Project I (Proposal Defense)', 'group' => 'FYP/Capstone Project'],
            ],
            7 => [
                ['code' => null, 'title' => 'Malware Analysis and Reverse Engineering', 'group' => 'Major (CYS 8)'],
                ['code' => null, 'title' => 'Cloud and Application Security', 'group' => 'Major (CYS 9)'],
                ['code' => null, 'title' => 'Specialization Elective – III', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective – IV', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Digital Marketing and E-Commerce (IDS – IV)', 'group' => 'Interdisciplinary'],
                ['code' => null, 'title' => 'National Skill Competency Test (HEC)', 'group' => 'Mandatory'],
                ['code' => null, 'title' => 'Final Year Project II (Design and Development)', 'group' => 'FYP/Capstone Project'],
            ],
            8 => [
                ['code' => null, 'title' => 'Specialization Elective – V', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective – VI', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Final Year Project III (Demonstration and Presentation)', 'group' => 'FYP/Capstone Project'],
            ],
        ],
        'bs-artificial-intelligence' => [
            1 => [
                ['code' => null, 'title' => 'Programming Fundamentals', 'group' => 'Major (CC 1)'],
                ['code' => null, 'title' => 'Applied Physics (Natural Science)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Functional English', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Applications of Information and Communication Technologies', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Islamic Studies / Ethics', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Fundamental of Math – I', 'group' => 'GER'],
            ],
            2 => [
                ['code' => null, 'title' => 'Object Oriented Programming', 'group' => 'Major (CC 2)'],
                ['code' => null, 'title' => 'Digital Logic Design', 'group' => 'Major (CC 3)'],
                ['code' => null, 'title' => 'Expository Writing', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Quantitative Reasoning – I', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Professional Practices (Arts and Humanities)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Ideology and Constitution of Pakistan', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Fundamental of Math – II', 'group' => 'GER'],
            ],
            3 => [
                ['code' => null, 'title' => 'Data Structures and Algorithms', 'group' => 'Major (CC 4)'],
                ['code' => null, 'title' => 'Database Systems', 'group' => 'Major (CC 5)'],
                ['code' => null, 'title' => 'Artificial Intelligence', 'group' => 'Major (CC 6)'],
                ['code' => null, 'title' => 'Fehm-e-Quran – I (for Muslim Students)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Quantitative Reasoning – II', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Calculus and Analytical Geometry (Mandatory)', 'group' => 'Interdisciplinary'],
            ],
            4 => [
                ['code' => null, 'title' => 'Software Engineering', 'group' => 'Major (CC 7)'],
                ['code' => null, 'title' => 'Design and Analysis of Algorithms', 'group' => 'Major (CC 8)'],
                ['code' => null, 'title' => 'Linear Algebra (Mandatory)', 'group' => 'Interdisciplinary'],
                ['code' => null, 'title' => 'Civics and Community Engagement', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Entrepreneurship', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Fehm-e-Quran – II (for Muslim Students)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Professional Certification', 'group' => 'Certification'],
            ],
            5 => [
                ['code' => null, 'title' => 'Computer Organization and Architecture', 'group' => 'Major (CC 11)'],
                ['code' => null, 'title' => 'Computer Networks', 'group' => 'Major (CC 9)'],
                ['code' => null, 'title' => 'Operating Systems', 'group' => 'Major (CC 10)'],
                ['code' => null, 'title' => 'Specialization Elective – I', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective – II', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Internship', 'group' => 'Field Experience'],
            ],
            6 => [
                ['code' => null, 'title' => 'Information Security', 'group' => 'Major (CC 12)'],
                ['code' => null, 'title' => 'Theory of Automata', 'group' => 'Major (CC 13)'],
                ['code' => null, 'title' => 'Project Management', 'group' => 'Interdisciplinary'],
                ['code' => null, 'title' => 'Specialization Elective – III', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Introduction to Management (Social Science)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Pakistan Studies', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Final Year Project I (Proposal Defense)', 'group' => 'FYP/Capstone Project'],
            ],
            7 => [
                ['code' => null, 'title' => 'Cloud Computing', 'group' => 'Major (CC 14)'],
                ['code' => null, 'title' => 'Specialization Elective – IV', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective – V', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective – VI', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Digital Marketing and E-Commerce', 'group' => 'Interdisciplinary'],
                ['code' => null, 'title' => 'National Skill Competency Test (HEC)', 'group' => 'Mandatory'],
                ['code' => null, 'title' => 'Final Year Project II (Design and Development)', 'group' => 'FYP/Capstone Project'],
            ],
            8 => [
                ['code' => null, 'title' => 'Specialization Elective – VII', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective – VIII', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Final Year Project III (Demonstration and Presentation)', 'group' => 'FYP/Capstone Project'],
            ],
        ],
        'bs-software-engineering' => [
            1 => [
                ['code' => null, 'title' => 'Programming Fundamentals', 'group' => 'Major (CC 1)'],
                ['code' => null, 'title' => 'Applied Physics (Natural Science)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Functional English', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Applications of Information and Communication Technologies', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Islamic Studies / Ethics', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Fundamental of Math – I', 'group' => 'GER'],
            ],
            2 => [
                ['code' => null, 'title' => 'Object Oriented Programming', 'group' => 'Major (CC 2)'],
                ['code' => null, 'title' => 'Digital Logic Design', 'group' => 'Major (CC 3)'],
                ['code' => null, 'title' => 'Expository Writing', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Quantitative Reasoning – I', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Professional Practices (Arts and Humanities)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Ideology and Constitution of Pakistan', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Fundamental of Math – II', 'group' => 'GER'],
            ],
            3 => [
                ['code' => null, 'title' => 'Data Structures and Algorithms', 'group' => 'Major (CC 4)'],
                ['code' => null, 'title' => 'Database Systems', 'group' => 'Major (CC 5)'],
                ['code' => null, 'title' => 'Artificial Intelligence', 'group' => 'Major (CC 6)'],
                ['code' => null, 'title' => 'Fehm-e-Quran – I (for Muslim Students)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Quantitative Reasoning – II', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Calculus and Analytical Geometry (Mandatory)', 'group' => 'Interdisciplinary'],
            ],
            4 => [
                ['code' => null, 'title' => 'Software Engineering', 'group' => 'Major (CC 7)'],
                ['code' => null, 'title' => 'Design and Analysis of Algorithms', 'group' => 'Major (CC 8)'],
                ['code' => null, 'title' => 'Linear Algebra (Mandatory)', 'group' => 'Interdisciplinary'],
                ['code' => null, 'title' => 'Civics and Community Engagement', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Entrepreneurship', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Fehm-e-Quran – II (for Muslim Students)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Professional Certification', 'group' => 'Certification'],
            ],
            5 => [
                ['code' => null, 'title' => 'Computer Organization and Architecture', 'group' => 'Major (CC 11)'],
                ['code' => null, 'title' => 'Computer Networks', 'group' => 'Major (CC 9)'],
                ['code' => null, 'title' => 'Operating Systems', 'group' => 'Major (CC 10)'],
                ['code' => null, 'title' => 'Specialization Elective – I', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective – II', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Internship', 'group' => 'Field Experience'],
            ],
            6 => [
                ['code' => null, 'title' => 'Information Security', 'group' => 'Major (CC 12)'],
                ['code' => null, 'title' => 'Theory of Automata', 'group' => 'Major (CC 13)'],
                ['code' => null, 'title' => 'Project Management', 'group' => 'Interdisciplinary'],
                ['code' => null, 'title' => 'Specialization Elective – III', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Introduction to Management (Social Science)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Pakistan Studies', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Final Year Project I (Proposal Defense)', 'group' => 'FYP/Capstone Project'],
            ],
            7 => [
                ['code' => null, 'title' => 'Cloud Computing', 'group' => 'Major (CC 14)'],
                ['code' => null, 'title' => 'Specialization Elective – IV', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective – V', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective – VI', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Digital Marketing and E-Commerce', 'group' => 'Interdisciplinary'],
                ['code' => null, 'title' => 'National Skill Competency Test (HEC)', 'group' => 'Mandatory'],
                ['code' => null, 'title' => 'Final Year Project II (Design and Development)', 'group' => 'FYP/Capstone Project'],
            ],
            8 => [
                ['code' => null, 'title' => 'Specialization Elective – VII', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective – VIII', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Final Year Project III (Demonstration and Presentation)', 'group' => 'FYP/Capstone Project'],
            ],
        ],
        // LGU's own bs-ds-roadmap page is content-identical to bs-se-roadmap
        // course-for-course (same titles/categories every semester) — that
        // appears to be a real duplication on their site, not an error in
        // this transcription; only semester 6's published Total differs.
        'bs-data-science' => [
            1 => [
                ['code' => null, 'title' => 'Programming Fundamentals', 'group' => 'Major (CC 1)'],
                ['code' => null, 'title' => 'Applied Physics (Natural Science)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Functional English', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Applications of Information and Communication Technologies', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Islamic Studies / Ethics', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Fundamental of Math – I', 'group' => 'GER'],
            ],
            2 => [
                ['code' => null, 'title' => 'Object Oriented Programming', 'group' => 'Major (CC 2)'],
                ['code' => null, 'title' => 'Digital Logic Design', 'group' => 'Major (CC 3)'],
                ['code' => null, 'title' => 'Expository Writing', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Quantitative Reasoning – I', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Professional Practices (Arts and Humanities)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Ideology and Constitution of Pakistan', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Fundamental of Math – II', 'group' => 'GER'],
            ],
            3 => [
                ['code' => null, 'title' => 'Data Structures and Algorithms', 'group' => 'Major (CC 4)'],
                ['code' => null, 'title' => 'Database Systems', 'group' => 'Major (CC 5)'],
                ['code' => null, 'title' => 'Artificial Intelligence', 'group' => 'Major (CC 6)'],
                ['code' => null, 'title' => 'Fehm-e-Quran – I (for Muslim Students)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Quantitative Reasoning – II', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Calculus and Analytical Geometry (Mandatory)', 'group' => 'Interdisciplinary'],
            ],
            4 => [
                ['code' => null, 'title' => 'Software Engineering', 'group' => 'Major (CC 7)'],
                ['code' => null, 'title' => 'Design and Analysis of Algorithms', 'group' => 'Major (CC 8)'],
                ['code' => null, 'title' => 'Linear Algebra (Mandatory)', 'group' => 'Interdisciplinary'],
                ['code' => null, 'title' => 'Civics and Community Engagement', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Entrepreneurship', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Fehm-e-Quran – II (for Muslim Students)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Professional Certification', 'group' => 'Certification'],
            ],
            5 => [
                ['code' => null, 'title' => 'Computer Organization and Architecture', 'group' => 'Major (CC 11)'],
                ['code' => null, 'title' => 'Computer Networks', 'group' => 'Major (CC 9)'],
                ['code' => null, 'title' => 'Operating Systems', 'group' => 'Major (CC 10)'],
                ['code' => null, 'title' => 'Specialization Elective – I', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective – II', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Internship', 'group' => 'Field Experience'],
            ],
            6 => [
                ['code' => null, 'title' => 'Information Security', 'group' => 'Major (CC 12)'],
                ['code' => null, 'title' => 'Theory of Automata', 'group' => 'Major (CC 13)'],
                ['code' => null, 'title' => 'Project Management', 'group' => 'Interdisciplinary'],
                ['code' => null, 'title' => 'Specialization Elective – III', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Introduction to Management (Social Science)', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Pakistan Studies', 'group' => 'General Education'],
                ['code' => null, 'title' => 'Final Year Project I (Proposal Defense)', 'group' => 'FYP/Capstone Project'],
            ],
            7 => [
                ['code' => null, 'title' => 'Cloud Computing', 'group' => 'Major (CC 14)'],
                ['code' => null, 'title' => 'Specialization Elective – IV', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective – V', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective – VI', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Digital Marketing and E-Commerce', 'group' => 'Interdisciplinary'],
                ['code' => null, 'title' => 'National Skill Competency Test (HEC)', 'group' => 'Mandatory'],
                ['code' => null, 'title' => 'Final Year Project II (Design and Development)', 'group' => 'FYP/Capstone Project'],
            ],
            8 => [
                ['code' => null, 'title' => 'Specialization Elective – VII', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Specialization Elective – VIII', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Final Year Project III (Demonstration and Presentation)', 'group' => 'FYP/Capstone Project'],
            ],
        ],
        'phd-computer-science' => [
            1 => [
                ['code' => null, 'title' => 'Elective – I', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Elective – II', 'group' => 'Elective'],
                ['code' => 'CC7101', 'title' => 'Elective – III', 'group' => 'Elective'],
            ],
            2 => [
                ['code' => null, 'title' => 'Elective – IV', 'group' => 'Elective'],
                ['code' => null, 'title' => 'Elective – V', 'group' => 'Elective'],
                ['code' => 'CC7101', 'title' => 'Elective – VI', 'group' => 'Elective'],
            ],
            3 => [
                ['code' => null, 'title' => 'Thesis I', 'group' => 'Thesis'],
                ['code' => null, 'title' => 'Thesis II', 'group' => 'Thesis'],
                ['code' => null, 'title' => 'Thesis III', 'group' => 'Thesis'],
            ],
            4 => [
                ['code' => null, 'title' => 'Thesis IV', 'group' => 'Thesis'],
                ['code' => null, 'title' => 'Thesis V', 'group' => 'Thesis'],
                ['code' => null, 'title' => 'Thesis VI', 'group' => 'Thesis'],
            ],
            5 => [
                ['code' => null, 'title' => 'Thesis VII', 'group' => 'Thesis'],
                ['code' => null, 'title' => 'Thesis VIII', 'group' => 'Thesis'],
            ],
            6 => [
                ['code' => null, 'title' => 'Thesis IX', 'group' => 'Thesis'],
                ['code' => null, 'title' => 'Thesis X', 'group' => 'Thesis'],
            ],
        ],
        'bs-psychology' => [
            1 => [
                ['code' => 'ENG101', 'title' => 'English-I', 'group' => 'General Education'],
                ['code' => 'ISL101', 'title' => 'Islamic Studies', 'group' => 'General Education'],
                ['code' => 'MATH103', 'title' => 'Mathematics', 'group' => 'General Education'],
                ['code' => 'MCN311', 'title' => 'Introduction to Mass Communication', 'group' => 'General Education'],
                ['code' => 'SOCY102', 'title' => 'Introduction to Sociology', 'group' => 'General Education'],
                ['code' => 'APSY103', 'title' => 'Introduction to Psychology', 'group' => 'Major'],
            ],
            2 => [
                ['code' => 'ENG102', 'title' => 'English-II', 'group' => 'General Education'],
                ['code' => 'PAK101', 'title' => 'Pakistan Studies', 'group' => 'General Education'],
                ['code' => 'CSC101', 'title' => 'Introduction to Computers', 'group' => 'General Education'],
                ['code' => 'APSY-324', 'title' => 'Logical and Critical Thinking', 'group' => 'Major'],
                ['code' => 'ECO101', 'title' => 'Introduction to Economics', 'group' => 'General Education'],
                ['code' => 'APSY-326', 'title' => 'Applied Areas of Psychology', 'group' => 'Major'],
                ['code' => 'APSY-327', 'title' => 'Experiments in Psychology', 'group' => 'Major'],
            ],
            3 => [
                ['code' => 'ENG119', 'title' => 'English-IV', 'group' => 'General Education'],
                ['code' => 'BMT107', 'title' => 'Introduction to Management', 'group' => 'General Education'],
                ['code' => 'EDU103', 'title' => 'Teaching and Learning Skills', 'group' => 'General Education'],
                ['code' => 'APSY-345', 'title' => 'Community Work OR Active Citizenship', 'group' => 'Major'],
                ['code' => 'APSY-346', 'title' => 'Positive Psychology', 'group' => 'Major'],
                ['code' => 'APSY-347', 'title' => 'Gender Issues in Psychology', 'group' => 'Major'],
            ],
            4 => [
                ['code' => 'ENG103', 'title' => 'Communication Skills (English-III)', 'group' => 'General Education'],
                ['code' => 'BIOL101', 'title' => 'Biology', 'group' => 'General Education'],
                ['code' => 'HECO333', 'title' => 'Food and Nutrition', 'group' => 'General Education'],
                ['code' => 'SOCY109', 'title' => 'Introduction to Social Work', 'group' => 'General Education'],
                ['code' => 'APSY-335', 'title' => 'Cognitive Psychology', 'group' => 'Major'],
                ['code' => 'APSY-336', 'title' => 'Environmental Psychology', 'group' => 'Major'],
            ],
            5 => [
                ['code' => 'APSY-351', 'title' => 'Psycho-Pathology', 'group' => 'Major'],
                ['code' => 'APSY-352', 'title' => 'Schools and Perspectives in Psychology', 'group' => 'Major'],
                ['code' => 'APSY-353', 'title' => 'Psychological Assessment', 'group' => 'Major'],
                ['code' => 'APSY-354', 'title' => 'Psychological Assessment Practical', 'group' => 'Major'],
                ['code' => 'APSY-355', 'title' => 'Developmental Psychology', 'group' => 'Major'],
                ['code' => 'APSY-356', 'title' => 'Research Methods – I', 'group' => 'Major'],
                ['code' => 'APSY-357', 'title' => 'Statistics in Psychology', 'group' => 'Major'],
            ],
            6 => [
                ['code' => 'APSY-349', 'title' => 'Theories of Personality', 'group' => 'Major'],
                ['code' => 'APSY-362', 'title' => 'Biological Basis of Behavior', 'group' => 'Major'],
                ['code' => 'APSY-363', 'title' => 'Research Methods – II', 'group' => 'Major'],
                ['code' => 'APSY-364', 'title' => 'Group Research', 'group' => 'Major'],
                ['code' => 'STAT116', 'title' => 'Data Analysis using SPSS', 'group' => 'Major'],
                ['code' => 'APSY-366', 'title' => 'Ethical Issues in Psychology', 'group' => 'Major'],
                ['code' => 'APSY-367', 'title' => 'Health Psychology', 'group' => 'Major'],
            ],
            7 => [
                ['code' => 'APSY-371', 'title' => 'Experimental Psychology', 'group' => 'Major'],
                ['code' => 'APSY-372', 'title' => 'Lab Experiments', 'group' => 'Major'],
                ['code' => 'APSY-373', 'title' => 'Social Psychology', 'group' => 'Major'],
                ['code' => 'APSY-374', 'title' => 'Elective I — Clinical / Counseling / Organizational Psychology', 'group' => 'Elective'],
                ['code' => 'APSY-375', 'title' => 'Specialization I/II/III (Case Reports)', 'group' => 'Elective'],
                ['code' => 'APSY-380', 'title' => 'Thesis', 'group' => 'Thesis'],
            ],
            8 => [
                ['code' => 'APSY-384', 'title' => 'Cross Cultural Psychology', 'group' => 'Major'],
                ['code' => 'APSY-383', 'title' => 'Peace Psychology', 'group' => 'Major'],
                ['code' => 'APSY-382', 'title' => 'Elective III — Educational Psychology', 'group' => 'Elective'],
                ['code' => 'APSY-385', 'title' => 'Elective IV — Forensic Psychology OR HRM', 'group' => 'Elective'],
                ['code' => 'APSY-380', 'title' => 'Research Thesis', 'group' => 'Thesis'],
            ],
        ],
        'bs-clinical-psychology' => [
            1 => [
                ['code' => 'ENG101', 'title' => 'Eng Comprehension-I', 'group' => 'General Education'],
                ['code' => 'Pak101', 'title' => 'Pak Studies', 'group' => 'General Education'],
                ['code' => 'MATH103', 'title' => 'Mathematics', 'group' => 'General Education'],
                ['code' => 'CS101', 'title' => 'Introduction to Computer', 'group' => 'General Education'],
                ['code' => 'BSCP101', 'title' => 'Introduction to Psychology', 'group' => 'Major'],
                ['code' => 'BSCP102', 'title' => 'Areas of Psychology', 'group' => 'Major'],
            ],
            2 => [
                ['code' => 'ENG102', 'title' => 'Eng Comprehension-II', 'group' => 'General Education'],
                ['code' => 'ISL101', 'title' => 'Islamic Studies', 'group' => 'General Education'],
                ['code' => 'MCN311', 'title' => 'Mass Communication', 'group' => 'General Education'],
                ['code' => 'BSCP103', 'title' => 'Abnormal Psychology', 'group' => 'Major'],
                ['code' => 'BSCP104', 'title' => 'Experiments in Psychology', 'group' => 'Major'],
                ['code' => 'BSCP105', 'title' => 'Perspectives in Clinical Psychology', 'group' => 'Major'],
            ],
            3 => [
                ['code' => 'ENG119', 'title' => 'Language (UN: Arabic, English, etc.)', 'group' => 'General Education'],
                ['code' => 'BSCP106', 'title' => 'Cognitive Psychology', 'group' => 'Major'],
                ['code' => 'BSCP107', 'title' => 'Developmental Psychology', 'group' => 'Major'],
                ['code' => 'BSCP108', 'title' => 'Psychology of Addictive Behaviour', 'group' => 'Major'],
                ['code' => 'BSCP109', 'title' => 'Clinical Psychology-I (Clinical Case Report Practical)', 'group' => 'Major'],
                ['code' => 'BSCP110', 'title' => 'School Psychology', 'group' => 'Major'],
            ],
            4 => [
                ['code' => 'ENG103', 'title' => 'Communication Skills', 'group' => 'General Education'],
                ['code' => 'BSCP111', 'title' => 'Positive Psychology', 'group' => 'Major'],
                ['code' => 'BSCP112', 'title' => 'Psychological Testing and Assessment', 'group' => 'Major'],
                ['code' => 'BSCP113', 'title' => 'Psychological Testing and Assessment (Practical)', 'group' => 'Major'],
                ['code' => 'BSCP114', 'title' => 'Logic and Reasoning', 'group' => 'Major'],
                ['code' => 'BSCP115', 'title' => 'Speech and Language Pathology', 'group' => 'Major'],
            ],
            5 => [
                ['code' => 'BSCP116', 'title' => 'Biological Basis of Behavior', 'group' => 'Major'],
                ['code' => 'BSCP117', 'title' => 'Psychopathology — Child', 'group' => 'Major'],
                ['code' => 'BSCP118', 'title' => 'Therapeutic Approaches-I', 'group' => 'Major'],
                ['code' => 'BSCP119', 'title' => 'Quantitative Research Methods', 'group' => 'Major'],
                ['code' => 'BSCP120', 'title' => 'Psychodiagnostics', 'group' => 'Major'],
                ['code' => 'BSCP121', 'title' => 'Statistics + Data Analysis in Clinical Psychology-I', 'group' => 'Major'],
            ],
            6 => [
                ['code' => 'BSCP122', 'title' => 'Ethical Issues in Clinical Psychology', 'group' => 'Major'],
                ['code' => 'BSCP123', 'title' => 'Psychopathology — Adult', 'group' => 'Major'],
                ['code' => 'BSCP124', 'title' => 'Therapeutic Approaches-II', 'group' => 'Major'],
                ['code' => 'BSCP125', 'title' => 'Qualitative Research Methods', 'group' => 'Major'],
                ['code' => 'BSCP126', 'title' => 'Health Psychology', 'group' => 'Major'],
                ['code' => 'BSCP127', 'title' => 'Data Analysis in Clinical Psychology-II & Group Research', 'group' => 'Major'],
            ],
            7 => [
                ['code' => 'BSCP128', 'title' => 'Professional Placement — Child / Practicum / Case Report-I (200 hrs)', 'group' => 'Internship'],
                ['code' => 'BSCP129', 'title' => 'Psycho-Pharmacology', 'group' => 'Major'],
                ['code' => 'BSCP130', 'title' => 'Social Psychology', 'group' => 'Major'],
                ['code' => 'BSCP131–133', 'title' => 'Elective (Counselling / Organizational Psychology) & Thesis', 'group' => 'Elective'],
            ],
            8 => [
                ['code' => 'BSCP134', 'title' => 'Professional Placement — Adult Practicum / Case Reports-II (200 hrs)', 'group' => 'Internship'],
                ['code' => 'BSCP135', 'title' => 'Culture and Psychology', 'group' => 'Major'],
                ['code' => 'BSCP136–137', 'title' => 'Elective (Forensic Psychology / Sports Psychology)', 'group' => 'Elective'],
                ['code' => 'BSCP138', 'title' => 'Thesis', 'group' => 'Thesis'],
            ],
        ],
        'ms-psychology' => [
            1 => [
                ['code' => 'PSAP7101', 'title' => 'Advance Theories of Psychology', 'group' => 'Core Course'],
                ['code' => 'PSAP7102', 'title' => 'Counselling Psychology', 'group' => 'Elective'],
                ['code' => 'PSAP7103', 'title' => 'Psychometrics', 'group' => 'Elective'],
                ['code' => 'PSAP7104', 'title' => 'Specialization in Organizational Psychology', 'group' => 'Elective'],
            ],
            2 => [
                ['code' => 'PSAP7201', 'title' => 'Advance Research Methods in Psychology', 'group' => 'Core Course'],
                ['code' => 'PSAP7202', 'title' => 'Advance Statistics and Data Analysis', 'group' => 'Elective'],
                ['code' => 'PSAP7203/7204', 'title' => 'Specialization in Forensic OR School Psychology', 'group' => 'Elective'],
                ['code' => 'UHQ7107', 'title' => 'Understanding of Holy Quran I', 'group' => 'Mandatory'],
                ['code' => 'PSAP7205', 'title' => 'Counseling (Child/Adult/Community)', 'group' => 'Internship'],
            ],
            3 => [
                ['code' => 'PSAP7301', 'title' => 'Advance Review of Published Research', 'group' => 'Elective'],
                ['code' => 'UHQ7108', 'title' => 'Understanding of Holy Quran II', 'group' => 'Mandatory'],
                ['code' => 'PSAP7302', 'title' => 'Thesis-I', 'group' => 'Thesis'],
                ['code' => 'PSAP7303', 'title' => 'Educational/Forensic Internship', 'group' => 'Internship'],
            ],
            4 => [
                ['code' => 'PSAP7401', 'title' => 'Industrial/Organizational Internship', 'group' => 'Internship'],
                ['code' => 'PSAP7402', 'title' => 'Thesis-II', 'group' => 'Thesis'],
            ],
        ],
        'ms-clinical-psychology' => [
            1 => [
                ['code' => 'PSCP7101', 'title' => 'Advance Theories of Psychology', 'group' => 'Core Course'],
                ['code' => 'PSCP7102', 'title' => 'Psycho-Diagnostic-I', 'group' => 'Core Course'],
                ['code' => 'PSCP7103', 'title' => 'Child Psychopathology', 'group' => 'Core Course'],
                ['code' => 'PSCP7104', 'title' => 'Therapeutic Approach', 'group' => 'Core Course'],
            ],
            2 => [
                ['code' => 'PSCP7201', 'title' => 'Advance Research Method in Psychology', 'group' => 'Core Course'],
                ['code' => 'PSCP7202', 'title' => 'Psycho-Diagnosis-II', 'group' => 'Core Course'],
                ['code' => 'PSCP7203', 'title' => 'Data Analysis in Clinical Psychology', 'group' => 'Core Course'],
                ['code' => 'PSCP7204', 'title' => 'Adult Psychopathology', 'group' => 'Core Course'],
                ['code' => 'PSCP7205', 'title' => 'Placement (Child)', 'group' => 'Internship'],
            ],
            3 => [
                ['code' => 'PSCP7301', 'title' => 'Neuropsychology and Psycho-Pharmacology', 'group' => 'Core Course'],
                ['code' => 'PSCP7302', 'title' => 'Ethical Standards and Legal Issues', 'group' => 'Core Course'],
                ['code' => 'UHQ7107/ESS7107', 'title' => 'Understanding of Holy Quran I / Ethics of Social Services I', 'group' => 'Mandatory'],
                ['code' => 'PSCP7303', 'title' => 'Thesis', 'group' => 'Thesis'],
                ['code' => 'PSCP7304', 'title' => 'Placement (Adult)', 'group' => 'Internship'],
            ],
            4 => [
                ['code' => 'PSCP7401', 'title' => 'Forensic Psychology', 'group' => 'Core Course'],
                ['code' => 'UHQ7108/ESS7208', 'title' => 'Understanding of Holy Quran II / Ethics of Social Services II', 'group' => 'Mandatory'],
                ['code' => 'PSCP7402', 'title' => 'Placement-II (Community)', 'group' => 'Internship'],
                ['code' => 'PSCP7403', 'title' => 'Thesis (Continued)', 'group' => 'Thesis'],
            ],
        ],
    ];

    public function index(): void
    {
        $db = $this->getDb();

        $slides = $db->fetchAll("SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY display_order ASC");
        $blocks = $db->fetchAll("SELECT * FROM content_blocks WHERE page_key = 'home' AND is_active = 1 ORDER BY display_order ASC");
        $departments = $db->fetchAll("SELECT * FROM departments WHERE is_active = 1 ORDER BY display_order ASC");
        $testimonials = $db->fetchAll("SELECT * FROM testimonials WHERE is_active = 1 ORDER BY display_order ASC, id DESC");

        $stats = [
            'departments' => (int) $db->fetch("SELECT COUNT(*) AS c FROM departments WHERE is_active = 1")['c'],
            'degrees' => (int) $db->fetch("SELECT COUNT(*) AS c FROM sub_departments WHERE is_active = 1")['c'],
            'papers' => (int) $db->fetch("SELECT COUNT(*) AS c FROM papers")['c'],
            'lectures' => (int) $db->fetch("SELECT COUNT(*) AS c FROM videos WHERE is_active = 1")['c'],
        ];

        $this->render(
            'home/index',
            [
                'title' => 'LGU Past Papers, Lectures & Class Bookings | LGU Hub',
                'seo' => (new \App\Services\SeoService())
                    ->description('Find LGU past papers by department, degree and exam type, watch online lectures mapped to your course, and book paid classes with LGU-experienced tutors.')
                    ->jsonLd([
                        '@context' => 'https://schema.org',
                        '@type' => 'WebSite',
                        'name' => 'LGU Hub',
                        'url' => rtrim(env('APP_URL', ''), '/') . '/',
                    ]),
                'slides' => $slides,
                'blocks' => $blocks,
                'departments' => $departments,
                'stats' => $stats,
                'testimonials' => $testimonials,
            ]
        );
    }

    public function departments(): void
    {
        $db = $this->getDb();
        $departments = $db->fetchAll("
            SELECT d.*, (SELECT COUNT(*) FROM sub_departments sd WHERE sd.department_id = d.id AND sd.is_active = 1) AS degree_count
            FROM departments d
            WHERE d.is_active = 1
            ORDER BY d.display_order ASC, d.name ASC
        ");

        $query = trim((string) $this->request->input('q', ''));

        // Search mode: hero/search-bar submissions land here with ?q=.
        // Matches against department name and description; a flat result
        // list (not grouped by faculty) since the point is "did you find
        // what you searched for", not browsing structure.
        if ($query !== '') {
            $needle = mb_strtolower($query);
            $results = array_values(array_filter($departments, function ($dept) use ($needle) {
                return str_contains(mb_strtolower($dept['name']), $needle)
                    || str_contains(mb_strtolower(strip_tags($dept['description'] ?? '')), $needle);
            }));

            $this->render(
                'pages/departments',
                [
                    'title' => 'Search: ' . htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . ' | LGU Hub',
                    'seo' => (new \App\Services\SeoService())->robots('noindex, follow'),
                    'departments' => $departments,
                    'groupedDepartments' => null,
                    'searchQuery' => $query,
                    'searchResults' => $results,
                ]
            );
            return;
        }

        $bySlug = [];
        foreach ($departments as $dept) {
            $bySlug[$dept['slug']] = $dept;
        }

        $grouped = [];
        foreach (self::FACULTIES as $faculty => $slugs) {
            foreach ($slugs as $slug) {
                if (isset($bySlug[$slug])) {
                    $grouped[$faculty][] = $bySlug[$slug];
                    unset($bySlug[$slug]);
                }
            }
        }

        // Anything seeded outside the known faculty map still shows up.
        if ($bySlug) {
            $grouped['Other Departments'] = array_values($bySlug);
        }

        $this->render(
            'pages/departments',
            [
                'title' => 'All 15 LGU Departments | Past Papers & Lectures',
                'seo' => (new \App\Services\SeoService())
                    ->description('Browse all Lahore Garrison University departments — Computer Sciences, Management Sciences, Psychology, Criminology and more. Find past papers and lectures by department.'),
                'departments' => $departments,
                'groupedDepartments' => $grouped,
                'searchQuery' => '',
                'searchResults' => null,
            ]
        );
    }

    public function department(string $slug): void
    {
        $db = $this->getDb();
        $department = $db->fetch("SELECT * FROM departments WHERE slug = ? AND is_active = 1", [$slug]);

        if (!$department) {
            $this->notFound();
            return;
        }

        // Fetch sub departments (degrees)
        $degrees = $db->fetchAll("SELECT * FROM sub_departments WHERE department_id = ? AND is_active = 1 ORDER BY display_order ASC", [$department['id']]);

        // Fetch department opportunities / career content blocks
        $blocks = $db->fetchAll("SELECT * FROM content_blocks WHERE page_key = ? AND is_active = 1 ORDER BY display_order ASC", ["dept-{$department['id']}"]);

        $this->render(
            'pages/department',
            [
                'title' => $department['meta_title'] ?: ($department['name'] . ' Past Papers & Lectures | LGU Hub'),
                'seo' => (new \App\Services\SeoService())
                    ->description($department['meta_description'] ?: ('Past papers, degree programs and lectures for the ' . $department['name'] . ' department at Lahore Garrison University.')),
                'department' => $department,
                'degrees' => $degrees,
                'blocks' => $blocks
            ]
        );
    }

    public function degree(string $deptSlug, string $subSlug): void
    {
        $db = $this->getDb();
        $department = $db->fetch("SELECT * FROM departments WHERE slug = ? AND is_active = 1", [$deptSlug]);
        $degree = $db->fetch("SELECT * FROM sub_departments WHERE slug = ? AND is_active = 1", [$subSlug]);

        if (!$department || !$degree) {
            $this->notFound();
            return;
        }

        // Fetch papers grouped by exam types
        $papers = $db->fetchAll("SELECT * FROM papers WHERE sub_department_id = ? ORDER BY session DESC, subject_name ASC", [$degree['id']]);
        
        $groupedPapers = [
            'mids' => [],
            'finals' => [],
            'summer_mids' => [],
            'summer_finals' => []
        ];

        foreach ($papers as $paper) {
            $groupedPapers[$paper['exam_type']][] = $paper;
        }

        $this->render(
            'pages/degree',
            [
                'title' => $degree['meta_title'] ?: ($degree['name'] . ' Past Papers | LGU Hub'),
                'seo' => (new \App\Services\SeoService())
                    ->description($degree['meta_description'] ?: ('Mid-term and final-term past papers for ' . $degree['name'] . ' at Lahore Garrison University, organised by course.')),
                'department' => $department,
                'degree' => $degree,
                'groupedPapers' => $groupedPapers
            ]
        );
    }

    public function paper(string $slug): void
    {
        $db = $this->getDb();
        $paper = $db->fetch("SELECT * FROM papers WHERE slug = ?", [$slug]);

        if (!$paper) {
            $this->notFound();
            return;
        }

        // Increment views
        $db->execute("UPDATE papers SET views_count = views_count + 1 WHERE id = ?", [$paper['id']]);

        // Get degree & department for breadcrumbs
        $degree = $db->fetch("SELECT * FROM sub_departments WHERE id = ?", [$paper['sub_department_id']]);
        $department = $db->fetch("SELECT * FROM departments WHERE id = ?", [$degree['department_id']]);

        // Fetch related papers in the same degree and exam type
        $related = $db->fetchAll("
            SELECT * FROM papers 
            WHERE sub_department_id = ? AND exam_type = ? AND id != ? 
            LIMIT 4
        ", [$paper['sub_department_id'], $paper['exam_type'], $paper['id']]);

        $examLabel = str_replace('_', ' ', $paper['exam_type']);

        $this->render(
            'pages/paper',
            [
                'title' => $paper['meta_title'] ?: ($paper['subject_name'] . ' ' . $paper['session'] . ' ' . ucwords($examLabel) . ' Past Paper | LGU Hub'),
                'seo' => (new \App\Services\SeoService())
                    ->description($paper['meta_description'] ?: ($paper['subject_name'] . ' ' . $paper['session'] . ' ' . ucwords($examLabel) . ' past paper for ' . $degree['name'] . ' at Lahore Garrison University. Free download.'))
                    ->jsonLd([
                        '@context' => 'https://schema.org',
                        '@type' => 'LearningResource',
                        'name' => $paper['subject_name'] . ' — ' . ucwords($examLabel) . ' (' . $paper['session'] . ')',
                        'educationalLevel' => $degree['name'],
                        'learningResourceType' => 'Past exam paper',
                    ]),
                'paper' => $paper,
                'degree' => $degree,
                'department' => $department,
                'related' => $related
            ]
        );
    }

    public function lectures(): void
    {
        $db = $this->getDb();

        $departments = $db->fetchAll("SELECT id, name, slug FROM departments WHERE is_active = 1 ORDER BY display_order ASC, name ASC");
        $subDepartments = $db->fetchAll("SELECT id, department_id, name, slug FROM sub_departments WHERE is_active = 1 ORDER BY name ASC");

        $subjectRows = $db->fetchAll(
            "SELECT DISTINCT sub_department_id, subject_name FROM videos
             WHERE is_active = 1 AND sub_department_id IS NOT NULL AND subject_name IS NOT NULL AND subject_name != ''
             ORDER BY subject_name ASC"
        );
        $subjectsBySubDepartment = [];
        foreach ($subjectRows as $row) {
            $subjectsBySubDepartment[(int) $row['sub_department_id']][] = $row['subject_name'];
        }

        $selectedDept = trim((string) $this->request->input('department', ''));
        $selectedDegree = trim((string) $this->request->input('degree', ''));
        $selectedSubject = trim((string) $this->request->input('subject', ''));
        $page = max(1, (int) $this->request->input('page', 1));
        $perPage = 8;
        $offset = ($page - 1) * $perPage;

        $where = " WHERE v.is_active = 1";
        $params = [];

        if ($selectedDegree !== '') {
            $where .= " AND sd.slug = ?";
            $params[] = $selectedDegree;
        } elseif ($selectedDept !== '') {
            $where .= " AND d.slug = ?";
            $params[] = $selectedDept;
        }

        if ($selectedSubject !== '') {
            $where .= " AND v.subject_name = ?";
            $params[] = $selectedSubject;
        }

        $baseSelect = "SELECT v.*, vc.name AS category_name, sd.name AS sub_department_name, d.name AS department_name
                        FROM videos v
                        LEFT JOIN video_categories vc ON vc.id = v.category_id
                        LEFT JOIN sub_departments sd ON sd.id = v.sub_department_id
                        LEFT JOIN departments d ON d.id = sd.department_id";

        $videos = $db->fetchAll(
            "{$baseSelect}{$where} ORDER BY v.display_order ASC, v.id DESC LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $total = (int) $db->fetch(
            "SELECT COUNT(*) AS total FROM videos v
             LEFT JOIN sub_departments sd ON sd.id = v.sub_department_id
             LEFT JOIN departments d ON d.id = sd.department_id{$where}",
            $params
        )['total'];

        $totalPages = (int) ceil($total / $perPage);

        $query = array_filter([
            'department' => $selectedDept,
            'degree' => $selectedDegree,
            'subject' => $selectedSubject,
        ]);
        $baseUrl = rtrim(env('APP_URL', ''), '/') . '/lectures' . (!empty($query) ? '?' . http_build_query($query) : '');

        $this->render(
            'pages/lectures',
            [
                'title' => 'Online Lectures & Live Classes | LGU Hub',
                'seo' => (new \App\Services\SeoService())
                    ->description('Watch recorded LGU course lectures filtered by department, degree program and subject, or book a live online class with an experienced tutor.')
                    ->canonical($page > 1 ? "{$baseUrl}" . (str_contains($baseUrl, '?') ? '&' : '?') . "page={$page}" : $baseUrl),
                'departments' => $departments,
                'subDepartments' => $subDepartments,
                'subjectsBySubDepartment' => $subjectsBySubDepartment,
                'selectedDept' => $selectedDept,
                'selectedDegree' => $selectedDegree,
                'selectedSubject' => $selectedSubject,
                'videos' => $videos,
                'page' => $page,
                'totalPages' => $totalPages,
            ]
        );
    }

    public function bookClass(): void
    {
        if ($this->request->isPost()) {
            if (!$this->verifyCsrf()) {
                $this->response->redirect('/lectures/book');
                return;
            }

            $db = $this->getDb();
            try {
                $name = trim($this->request->input('name', ''));
                $email = trim($this->request->input('email', ''));
                $whatsapp = trim($this->request->input('whatsapp', ''));
                $subject = trim($this->request->input('subject', ''));
                $topic = trim($this->request->input('topic', ''));
                $feeType = trim($this->request->input('fee_type', ''));
                $description = trim($this->request->input('description', ''));

                if (empty($name) || empty($email) || empty($whatsapp) || empty($subject)) {
                    throw new \Exception('Please fill in all required fields.');
                }

                $db->execute("
                    INSERT INTO class_bookings (name, email, whatsapp, subject, topic, fee_type, description, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'new')
                ", [$name, $email, $whatsapp, $subject, $topic, $feeType, $description]);

                // Send email to admin using MailService if configured
                // For WhatsApp, we will return a wa.me redirect link if requested by user,
                // or just flash a success message and direct to WhatsApp chat.
                $this->session->flash('success', 'Your booking request has been submitted successfully! We will contact you soon.');
            } catch (\Exception $e) {
                $this->session->flash('error', $e->getMessage());
            }
        }
        $this->response->redirect('/lectures/book');
    }

    public function bookClassPage(): void
    {
        $this->render(
            'pages/book_class',
            [
                'title' => 'Book an Online Class | LGU Hub',
                'seo' => (new \App\Services\SeoService())
                    ->description('Book a live one-on-one online class with an LGU-experienced tutor — a single topic session or complete-semester support, matched to your exact subject.'),
            ]
        );
    }

    public function subscribeNewsletter(): void
    {
        if ($this->request->isPost()) {
            if (!$this->verifyCsrf()) {
                $this->response->redirect('/');
                return;
            }

            $db = $this->getDb();
            try {
                $email = trim($this->request->input('email', ''));
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception('Please enter a valid email address.');
                }

                $existing = $db->fetch("SELECT * FROM newsletter_subscribers WHERE email = ?", [$email]);
                if ($existing) {
                    $this->session->flash('success', 'You are already subscribed to our newsletter!');
                } else {
                    $token = bin2hex(random_bytes(16));
                    $db->execute("
                        INSERT INTO newsletter_subscribers (email, is_verified, unsubscribe_token)
                        VALUES (?, 1, ?)
                    ", [$email, $token]);
                    $this->session->flash('success', 'Thank you for subscribing to our newsletter alerts!');
                }
            } catch (\Exception $e) {
                $this->session->flash('error', $e->getMessage());
            }
        }
        $this->response->redirect('/');
    }

    public function unsubscribeNewsletter(): void
    {
        $token = trim((string) $this->request->input('token', ''));

        if ($token !== '') {
            $this->newsletterService->unsubscribeByToken($token);
            $this->session->flash('success', 'You have been unsubscribed from LGU Hub update alerts.');
        } else {
            $this->session->flash('error', 'Invalid unsubscribe link.');
        }

        $this->response->redirect('/');
    }

    public function submitPaper(): void
    {
        if ($this->request->isPost()) {
            if (!$this->verifyCsrf()) {
                $this->response->redirect('/contact-us');
                return;
            }

            $db = $this->getDb();
            try {
                $name = trim($this->request->input('name', ''));
                $email = trim($this->request->input('email', ''));
                $whatsapp = trim($this->request->input('whatsapp', ''));
                $subject = trim($this->request->input('subject', ''));
                $description = trim($this->request->input('description', ''));

                if (empty($name) || empty($email) || empty($whatsapp) || empty($subject) || empty($_FILES['paper_image']['name'])) {
                    throw new \Exception('Please fill in all required fields and upload the paper image.');
                }

                // File upload using raw helper or file service
                $uploadsService = new \App\Services\FileUploadService();
                $imagePath = $uploadsService->upload($_FILES['paper_image'], 'submissions');

                $db->execute("
                    INSERT INTO paper_submissions (name, email, whatsapp, subject, description, image_path, status)
                    VALUES (?, ?, ?, ?, ?, ?, 'pending')
                ", [$name, $email, $whatsapp, $subject, $description, $imagePath]);

                $this->session->flash('success', 'Your paper has been submitted successfully for admin moderation!');
            } catch (\Exception $e) {
                $this->session->flash('error', $e->getMessage());
            }
        }
        $this->response->redirect('/contact-us');
    }

    /**
     * FAQ pairs also feed the FAQPage JSON-LD below — kept as one array so
     * the visible accordion and the schema can never drift out of sync.
     */
    private const ABOUT_LGU_FAQS = [
        [
            'q' => 'Is Lahore Garrison University (LGU) HEC recognized?',
            'a' => 'Yes. LGU is chartered by the Government of Punjab and recognized by the Higher Education Commission (HEC) of Pakistan.',
        ],
        [
            'q' => "Where is LGU's main campus located?",
            'a' => 'LGU\'s main campus is in Sector C, Avenue III, DHA Phase VI, Lahore, on a 65-kanal site accessible via Lahore Ring Road.',
        ],
        [
            'q' => 'What programs does LGU offer?',
            'a' => 'LGU offers BS (undergraduate), MS/MPhil, and PhD programs across Computer Sciences, Social Sciences, Basic Sciences, and Languages, plus UK-affiliated BTEC HND diplomas.',
        ],
        [
            'q' => 'Who is the Vice Chancellor of LGU?',
            'a' => 'Maj Gen Muhammad Khalil Dar, HI(M) (Retd) is the current Vice Chancellor of Lahore Garrison University.',
        ],
        [
            'q' => 'When was LGU established?',
            'a' => 'The concept was approved in principle by the Chief of Army Staff on 9 March 2010, and the university was formally chartered by the Government of Punjab in 2014.',
        ],
    ];

    public function aboutLgu(): void
    {
        $db = $this->getDb();
        $blocks = $db->fetchAll("SELECT * FROM content_blocks WHERE page_key = 'about-lgu' AND is_active = 1 ORDER BY display_order ASC");

        $this->render(
            'pages/about_lgu',
            [
                'title' => 'About LGU – Lahore Garrison University, Lahore, Pakistan',
                'seo' => (new \App\Services\SeoService())
                    ->description('Learn about Lahore Garrison University (LGU) — an HEC-recognized private university in DHA Phase VI, Lahore, offering BS, MS/MPhil & PhD programs.')
                    ->ogImage(rtrim(env('APP_URL', ''), '/') . asset('images/brand/campus-hero.jpg'))
                    ->jsonLd([
                        '@context' => 'https://schema.org',
                        '@type' => 'CollegeOrUniversity',
                        'name' => 'Lahore Garrison University',
                        'alternateName' => 'LGU',
                        'url' => 'https://lgu.edu.pk/',
                        'foundingDate' => '2014',
                        'description' => 'Lahore Garrison University (LGU) is a private, HEC-recognized university in Lahore, Pakistan, established by the Pakistan Army, offering undergraduate, graduate, MPhil and PhD programs across Computer Sciences, Social Sciences, Basic Sciences and Languages.',
                        'address' => [
                            '@type' => 'PostalAddress',
                            'streetAddress' => 'Sector C, Avenue III, DHA Phase VI',
                            'addressLocality' => 'Lahore',
                            'addressRegion' => 'Punjab',
                            'addressCountry' => 'PK',
                        ],
                        'geo' => [
                            '@type' => 'GeoCoordinates',
                            'latitude' => '31.463979',
                            'longitude' => '74.442694',
                        ],
                        'telephone' => '+92-42-37181821',
                        'sameAs' => [
                            'https://en.wikipedia.org/wiki/Lahore_Garrison_University',
                            'https://www.facebook.com/LGUOFFICIALCAMPUS',
                            'https://www.instagram.com/lguofficialcampus/',
                            'https://pk.linkedin.com/company/lahore-garrison-university',
                        ],
                    ])
                    ->jsonLd([
                        '@context' => 'https://schema.org',
                        '@type' => 'FAQPage',
                        'mainEntity' => array_map(fn($f) => [
                            '@type' => 'Question',
                            'name' => $f['q'],
                            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
                        ], self::ABOUT_LGU_FAQS),
                    ]),
                'blocks' => $blocks,
                'faqs' => self::ABOUT_LGU_FAQS,
            ]
        );
    }

    public function aboutUs(): void
    {
        $db = $this->getDb();
        $blocks = $db->fetchAll("SELECT * FROM content_blocks WHERE page_key = 'about-us' AND is_active = 1 ORDER BY display_order ASC");

        $this->render(
            'pages/about_us',
            [
                'title' => 'About LGU Hub | Independent Student Resource',
                'seo' => (new \App\Services\SeoService())
                    ->description('LGU Hub is a student-built resource for Lahore Garrison University past papers, lectures and class bookings, organised by department and degree.'),
                'blocks' => $blocks
            ]
        );
    }

    public function alumni(): void
    {
        $db = $this->getDb();
        $alumni = $db->fetchAll("SELECT * FROM alumni WHERE is_active = 1 ORDER BY display_order ASC");

        $this->render(
            'pages/alumni',
            [
                'title' => 'Alumni Testimonials | LGU Hub',
                'seo' => (new \App\Services\SeoService())
                    ->description('Hear from Lahore Garrison University graduates about their degree programs, campus life and career outcomes.'),
                'alumni' => $alumni
            ]
        );
    }

    public function contactUs(): void
    {
        $db = $this->getDb();
        $departments = $db->fetchAll("SELECT name, slug FROM departments WHERE is_active = 1 ORDER BY display_order ASC");

        $this->render(
            'pages/contact_us',
            [
                'title' => 'Contact Us & Submit a Past Paper | LGU Hub',
                'seo' => (new \App\Services\SeoService())
                    ->description('Get in touch with LGU Hub, or submit your own past paper to help other Lahore Garrison University students.'),
                'departments' => $departments,
            ]
        );
    }

    public function feeStructure(): void
    {
        $data = require dirname(__DIR__) . '/data/FeeStructureData.php';
        $panels = $data['panels'];

        $this->render(
            'pages/fee_structure',
            [
                'title' => 'LGU Fee Structure — All Programs (FY 2026-27) | LGU Hub',
                'seo' => (new \App\Services\SeoService())
                    ->description('Complete LGU fee structure for FY 2026-27: Graduation, M.Phil/MS & PhD, and BTEC HND programs by faculty — admission fee, per-semester tuition and estimated total charges.')
                    ->jsonLd([
                        '@context' => 'https://schema.org',
                        '@type' => 'FAQPage',
                        'mainEntity' => array_map(fn($p) => [
                            '@type' => 'Question',
                            'name' => "What is the {$p['title']}?",
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => "See the {$p['heading']} fee breakdown on this page.",
                            ],
                        ], $panels),
                    ]),
                'panels' => $panels,
            ]
        );
    }

    public function scholarships(): void
    {
        $this->render(
            'pages/scholarships',
            [
                'title' => 'LGU Scholarships — All 7 Categories | LGU Hub',
                'seo' => (new \App\Services\SeoService())
                    ->description('All 7 LGU financial assistance categories, exactly as published on the official Scholarship dashboard — Merit, Performance, Defence, Garrisonian & Kinship, Employees, Sports and Need based.'),
            ]
        );
    }

    public function roadmaps(): void
    {
        $this->render(
            'pages/roadmaps',
            [
                'title' => 'LGU Degree Roadmaps — All Programs | LGU Hub',
                'seo' => (new \App\Services\SeoService())
                    ->description('Semester-by-semester degree roadmaps for LGU programs — duration, credit hours and curriculum structure by department.'),
                'roadmaps' => self::ROADMAPS,
            ]
        );
    }

    public function roadmapDetail(string $slug): void
    {
        $program = null;
        $department = null;

        foreach (self::ROADMAPS as $deptName => $programs) {
            foreach ($programs as $p) {
                if ($p['slug'] === $slug) {
                    $program = $p;
                    $department = $deptName;
                    break 2;
                }
            }
        }

        if (!$program) {
            $this->notFound();
            return;
        }

        $this->render(
            'pages/roadmap_detail',
            [
                'title' => $program['title'] . ' Roadmap | LGU Hub',
                'seo' => (new \App\Services\SeoService())
                    ->description($program['title'] . ' degree roadmap at Lahore Garrison University — duration, credit hours and semester structure.'),
                'program' => $program,
                'departmentName' => $department,
                'semesters' => self::SEMESTER_CURRICULA[$slug] ?? null,
            ]
        );
    }
}