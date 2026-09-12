<?php

declare(strict_types=1);

/**
 * Real LGU fee structure, transcribed verbatim from lgu.edu.pk/fee-structure/
 * (verified by the user against the live page, not scraped/guessed).
 *
 * Structure matches the real page exactly: a FLAT list of 13 accordion
 * panels (not a nested Group->Faculty tree) — "BS Level Fee Structure of
 * Faculty of X", "M.Phil / MS Level Fee Structure of Faculty of X",
 * "Ph.D Level Fee Structure of Faculty of X" (x4 each) plus one BTEC
 * panel, in that exact order. Each panel opens onto a centered department
 * heading, then one or more program tables. Two "NOTE SECTION" blocks
 * (plain text, not accordion items) sit after the 4th and 12th panel,
 * matching the source page's placement.
 *
 * Row shape: ['no' => string, 'head' => string, 'value' => string,
 *   'head_bold' => bool, 'value_bold' => bool, 'shaded' => bool]
 * `shaded` marks the named subtotal rows (Total One Time Charges, At the
 * Time of Admission, Total Charges (Estimated), Total Fee (Estimated)) —
 * both head and value bold *and* a shaded row background. A few rows
 * (e.g. "Tuition Fee (Per Credit Hour)") are bold-head-only in the source
 * and are transcribed that way rather than promoted to full subtotal
 * styling.
 */

if (!function_exists('feeRow')) {
    // Guarded: this file is require()'d fresh on every /fee-structure hit,
    // and a bare `function feeRow()` would fatal with "cannot redeclare"
    // if anything ever required it twice in the same PHP process.
    function feeRow(string $no, string $head, string $value, bool $headBold = false, bool $valueBold = false, bool $shaded = false): array
    {
        return ['no' => $no, 'head' => $head, 'value' => $value, 'head_bold' => $headBold, 'value_bold' => $valueBold, 'shaded' => $shaded];
    }
}

return [
    'panels' => [
        [
            'title' => 'BS Level Fee Structure of Faculty of Computer Sciences',
            'heading' => 'Faculty of Computer Sciences',
            'subheading' => 'Dept. of Computer Science, Information Technology & Software Engineering',
            'tables' => [
                [
                    'label' => 'BSCS, BSSE, BSIT, BSDS, BSAI, BS CySec',
                    'rows' => [
                        feeRow('1', 'Admission Fee', '17,500'),
                        feeRow('', 'Total One Time Charges', '17,500', true, true, true),
                        feeRow('2', 'Tuition Fee (1st Semester)', '139,392'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '6,360'),
                        feeRow('', 'At the Time of Admission', '163,252', true, true, true),
                        feeRow('', 'Tuition Fee (Per Credit Hour)', '7,744', true),
                        feeRow('', '1st Semester Credit Hours (Estimated)', '18', false, true),
                        feeRow('', '2nd Semester Credit Hours (Estimated)', '18', false, true),
                        feeRow('', '2nd Semester Fee (Estimated)', '144,892', false, true),
                        feeRow('', 'Total Charges (Estimated)', '1,086,848', true, true, true),
                    ],
                ],
            ],
        ],
        [
            'title' => 'BS Level Fee Structure of Faculty of Social Sciences',
            'heading' => 'Deptt. of Humanities, Islamic Studies, Mass Comm, Mgmt Sciences & Psychology',
            'subheading' => null,
            'tables' => [
                [
                    'label' => 'BS A&F (Accounting & Finance) | Mass Comm | Home Econ | IR | Econ with Data Analytics',
                    'rows' => [
                        feeRow('1', 'Admission Fee', '17,500'),
                        feeRow('', 'Total One Time Charges', '17,500', true, true, true),
                        feeRow('2', 'Tuition Fee (1st Semester)', '120,510'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '5,360'),
                        feeRow('', 'At the Time of Admission', '143,370', true, true, true),
                        feeRow('', 'Tuition Fee (Per Credit Hour)', '6,695', true),
                        feeRow('', '1st Semester Credit Hours (Estimated)', '18', false, true),
                        feeRow('', '2nd Semester Credit Hours (Estimated)', '18', false, true),
                        feeRow('', '2nd Semester Fee (Estimated)', '125,010', false, true),
                        feeRow('', 'Total Charges (Estimated)', '940,380', true, true, true),
                    ],
                ],
                [
                    'label' => 'BBA',
                    'rows' => [
                        feeRow('1', 'Admission Fee', '17,500'),
                        feeRow('', 'Total One Time Charges', '17,500', true, true, true),
                        feeRow('2', 'Tuition Fee (1st Semester)', '133,056'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '5,360'),
                        feeRow('', 'At the Time of Admission', '155,916', true, true, true),
                        feeRow('', 'Tuition Fee (Per Credit Hour)', '7,392', true),
                        feeRow('', '1st Semester Credit Hours (Estimated)', '18', false, true),
                        feeRow('', '2nd Semester Credit Hours (Estimated)', '18', false, true),
                        feeRow('', '2nd Semester Fee (Estimated)', '137,556', false, true),
                        feeRow('', 'Total Charges (Estimated)', '1,032,384', true, true, true),
                    ],
                ],
                [
                    'label' => 'BS Applied Psychology | BS CP',
                    'rows' => [
                        feeRow('1', 'Admission Fee', '17,500'),
                        feeRow('', 'Total One Time Charges', '17,500', true, true, true),
                        feeRow('2', 'Tuition Fee (1st Semester)', '133,056'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '7,860'),
                        feeRow('', 'At the Time of Admission', '158,416', true, true, true),
                        feeRow('', 'Tuition Fee (Per Credit Hour)', '7,392', true),
                        feeRow('', '1st Semester Credit Hours (Estimated)', '18', false, true),
                        feeRow('', '2nd Semester Credit Hours (Estimated)', '18', false, true),
                        feeRow('', '2nd Semester Fee (Estimated)', '140,056', false, true),
                        feeRow('', 'Total Charges (Estimated)', '1,052,384', true, true, true),
                    ],
                ],
                [
                    'label' => 'BS DF&CS | Criminology | WCCI',
                    'rows' => [
                        feeRow('1', 'Admission Fee', '17,500'),
                        feeRow('', 'Total One Time Charges', '17,500', true, true, true),
                        feeRow('2', 'Tuition Fee (1st Semester)', '133,056'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '5,360'),
                        feeRow('', 'At the Time of Admission', '155,916', true, true, true),
                        feeRow('', 'Tuition Fee (Per Credit Hour)', '7,392', true),
                        feeRow('', '1st Semester Credit Hours (Estimated)', '18', false, true),
                        feeRow('', '2nd Semester Credit Hours (Estimated)', '18', false, true),
                        feeRow('', '2nd Semester Fee (Estimated)', '137,556', false, true),
                        feeRow('', 'Total Charges (Estimated)', '1,032,384', true, true, true),
                    ],
                ],
                [
                    'label' => 'BS Islamic Studies',
                    'rows' => [
                        feeRow('1', 'Admission Fee', '17,500'),
                        feeRow('', 'Total One Time Charges', '17,500', true, true, true),
                        feeRow('2', 'Tuition Fee (1st Semester)', '92,880'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '5,360'),
                        feeRow('', 'At the Time of Admission', '115,740', true, true, true),
                        feeRow('', 'Tuition Fee (Per Credit Hour)', '5,160', true),
                        feeRow('', '1st Semester Credit Hours (Estimated)', '18', false, true),
                        feeRow('', '2nd Semester Credit Hours (Estimated)', '18', false, true),
                        feeRow('', '2nd Semester Fee (Estimated)', '97,380', false, true),
                        feeRow('', 'Total Charges (Estimated)', '737,760', true, true, true),
                    ],
                ],
            ],
        ],
        [
            'title' => 'BS Level Fee Structure of Faculty of Basic Sciences',
            'heading' => 'Faculty of Basic Sciences',
            'subheading' => null,
            'tables' => [
                [
                    'label' => 'Dept. of Biology — BS Bio Chemistry, Bio Tech, Micro Biology, Botany, Zoology, Nutrition & Diet',
                    'rows' => [
                        feeRow('1', 'Admission Fee', '17,500'),
                        feeRow('', 'Total One Time Charges', '17,500', true, true, true),
                        feeRow('2', 'Tuition Fee (1st Semester)', '120,510'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '7,860'),
                        feeRow('', 'At the Time of Admission', '145,870', true, true, true),
                        feeRow('', 'Tuition Fee (Per Credit Hour)', '6,695', true),
                        feeRow('', '1st Semester Credit Hours (Estimated)', '18', false, true),
                        feeRow('', '2nd Semester Credit Hours (Estimated)', '18', false, true),
                        feeRow('', '2nd Semester Fee (Estimated)', '127,510', false, true),
                        feeRow('', 'Total Charges (Estimated)', '960,380', false, true),
                    ],
                ],
                [
                    'label' => 'Dept. of Chemistry, Mathematics, Physics — BS Chemistry, Math, Statistics, Physics, CMAI, CPhyDS, MPhy',
                    'rows' => [
                        feeRow('1', 'Admission Fee', '17,500'),
                        feeRow('', 'Total One Time Charges', '17,500', true, true, true),
                        feeRow('2', 'Tuition Fee (1st Semester)', '115,254'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '7,860'),
                        feeRow('', 'At the Time of Admission', '140,614', true, true, true),
                        feeRow('', 'Tuition Fee (Per Credit Hour)', '6,403', true),
                        feeRow('', '1st Semester Credit Hours (Estimated)', '18', false, true),
                        feeRow('', '2nd Semester Credit Hours (Estimated)', '18', false, true),
                        feeRow('', '2nd Semester Fee (Estimated)', '122,254', false, true),
                        feeRow('', 'Total Charges (Estimated)', '921,836', false, true),
                    ],
                ],
            ],
        ],
        [
            'title' => 'BS Level Fee Structure of Faculty of Languages',
            'heading' => 'Deptt. of English & Urdu',
            'subheading' => null,
            'tables' => [
                [
                    'label' => 'BS English and BS Urdu',
                    'rows' => [
                        feeRow('1', 'Admission Fee', '17,500'),
                        feeRow('', 'Total One Time Charges', '17,500', true, true, true),
                        feeRow('2', 'Tuition Fee (1st Semester)', '87,720'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '5,360'),
                        feeRow('', 'At the Time of Admission', '110,580', true, true, true),
                        feeRow('', 'Tuition Fee (Per Credit Hour)', '5,160', true),
                        feeRow('', '1st Semester Credit Hours (Estimated)', '17', false, true),
                        feeRow('', '2nd Semester Credit Hours (Estimated)', '17', false, true),
                        feeRow('', '2nd Semester Fee (Estimated)', '92,220', false, true),
                        feeRow('', 'Total Charges (Estimated)', '737,760', true, true, true),
                    ],
                ],
            ],
            'notes_after' => [
                'heading' => 'Graduation Programs',
                'items' => [
                    'The following dues are also applicable as per the Program/Scheme of Studies: Practical Fee Per Semester — 2,500; CS Lab Fee Per Semester — 1,000.',
                    'Shaheeds means those who embraced shahadat in any war, laid down their lives in any action for the defence of the country, duly declared by W and E Dte GHQ. Only Rs 5,000 shall be charged as security (Refundable), 3-SF Category (Free).',
                    'Tuition Fee, Exam Fee, Society Fund, Magazine Charges, Misc. Charges and Enrollment Fee shall be charged on a semester and annual basis, respectively.',
                    'All fees are non-refundable and can be changed without prior notice.',
                    'Defence-based scholarship will be awarded to 100 students on merit basis.',
                ],
            ],
        ],
        [
            'title' => 'M.Phil / MS Level Fee Structure of Faculty of Computer Sciences',
            'heading' => 'M.Phil / MS – Faculty of Computer Sciences',
            'subheading' => 'Programs: M.Phil Computer Science, Information Technology, Data Sciences',
            'tables' => [
                [
                    'label' => null,
                    'rows' => [
                        feeRow('1', 'Admission Fee', '20,000'),
                        feeRow('', 'Total One Time Charges', '20,000', true, true, true),
                        feeRow('2', 'Tuition Fee (Per Credit Hour)', '11,816'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '3,600'),
                        feeRow('', 'Fee at Time of Admission', '165,392', true, true, true),
                        feeRow('4', '2nd Semester Fee', '144,792'),
                        feeRow('', 'Total Fee (Estimated)', '387,580', true, true, true),
                        feeRow('6', 'Credit Hours (1st Semester)', '12'),
                        feeRow('7', 'Total Credit Hours', '30'),
                    ],
                ],
            ],
        ],
        [
            'title' => 'M.Phil / MS Level Fee Structure of Faculty of Social Sciences',
            'heading' => 'M.Phil / MS – Faculty of Social Sciences',
            'subheading' => null,
            'tables' => [
                [
                    'label' => 'MSBA, MBA (2 Years), Mass Comm, App Psy, ADCP, IR',
                    'rows' => [
                        feeRow('1', 'Admission Fee', '20,000'),
                        feeRow('', 'Total One Time Charges', '20,000', true, true, true),
                        feeRow('2', 'Tuition Fee (Per Credit Hour)', '11,278'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '3,600'),
                        feeRow('', 'Fee at the Time of Admission', '158,936', true, true, true),
                        feeRow('4', '2nd Semester Fee', '138,336'),
                        feeRow('', 'Total Fee (Estimated)', '371,440', true, true, true),
                        feeRow('5', 'Credit Hours (1st Semester)', '12'),
                        feeRow('6', 'Total Credit Hours', '30'),
                    ],
                ],
                [
                    'label' => 'MSCP (Psychology)',
                    'rows' => [
                        feeRow('1', 'Admission Fee', '20,000'),
                        feeRow('', 'Total One Time Charges', '20,000', true, true, true),
                        feeRow('2', 'Tuition Fee (Per Credit Hour)', '11,278'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '3,600'),
                        feeRow('', 'Fee at the Time of Admission', '158,936', true, true, true),
                        feeRow('4', '2nd Semester Fee', '138,336'),
                        feeRow('', 'Total Fee (Estimated)', '540,610', true, true, true),
                        feeRow('5', 'Credit Hours (1st Semester)', '12'),
                        feeRow('6', 'Total Credit Hours', '45'),
                    ],
                ],
                [
                    'label' => 'M.Phil Islamic Studies',
                    'rows' => [
                        feeRow('1', 'Admission Fee', '20,000'),
                        feeRow('', 'Total One Time Charges', '20,000', true, true, true),
                        feeRow('2', 'Tuition Fee (Per Credit Hour)', '10,430'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '3,600'),
                        feeRow('', 'Fee at the Time of Admission', '148,760', true, true, true),
                        feeRow('4', '2nd Semester Fee', '128,760'),
                        feeRow('', 'Total Fee (Estimated)', '346,000', true, true, true),
                        feeRow('5', 'Credit Hours (1st Semester)', '12'),
                        feeRow('6', 'Total Credit Hours', '30'),
                    ],
                ],
            ],
        ],
        [
            'title' => 'M.Phil / MS Level Fee Structure of Faculty of Basic Sciences',
            'heading' => 'M.Phil / MS – Faculty of Basic Sciences',
            'subheading' => null,
            'tables' => [
                [
                    'label' => 'M.Phil Chemistry, M.Phil Physics',
                    'rows' => [
                        feeRow('1', 'Admission Fee', '20,000'),
                        feeRow('', 'Total One Time Charges', '20,000', true, true, true),
                        feeRow('2', 'Tuition Fee (Per Credit Hour)', '11,278'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '3,600'),
                        feeRow('', 'Fee at Time of Admission', '158,936', true, true, true),
                        feeRow('4', '2nd Semester Fee', '138,336'),
                        feeRow('', 'Total Fee (Estimated)', '371,440', true, true, true),
                        feeRow('6', 'Credit Hours (1st Semester)', '12'),
                        feeRow('7', 'Total Credit Hours', '30'),
                    ],
                ],
                [
                    'label' => 'M.Phil Zoology, M.Phil Math, M.Phil Micro Biology',
                    'rows' => [
                        feeRow('1', 'Admission Fee', '20,000'),
                        feeRow('', 'Total One Time Charges', '20,000', true, true, true),
                        feeRow('2', 'Tuition Fee (Per Credit Hour)', '10,430'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '3,600'),
                        feeRow('', 'Fee at Time of Admission', '148,760', true, true, true),
                        feeRow('4', '2nd Semester Fee', '128,160'),
                        feeRow('', 'Total Fee (Estimated)', '346,000', true, true, true),
                        feeRow('6', 'Credit Hours (1st Semester)', '12'),
                        feeRow('7', 'Total Credit Hours', '30'),
                    ],
                ],
            ],
        ],
        [
            'title' => 'M.Phil / MS Level Fee Structure of Faculty of Languages',
            'heading' => 'M.Phil / MS – Faculty of Languages',
            'subheading' => 'Programs: M.Phil Urdu, M.Phil English',
            'tables' => [
                [
                    'label' => null,
                    'rows' => [
                        feeRow('1', 'Admission Fee', '20,000'),
                        feeRow('', 'Total One Time Charges', '20,000', true, true, true),
                        feeRow('2', 'Tuition Fee (Per Credit Hour)', '10,430'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '3,600'),
                        feeRow('', 'Fee at Time of Admission', '148,760', true, true, true),
                        feeRow('4', '2nd Semester Fee', '128,760'),
                        feeRow('', 'Total Fee (Estimated)', '346,000', true, true, true),
                        feeRow('5', 'Credit Hours (1st Semester)', '12'),
                        feeRow('6', 'Total Credit Hours', '30'),
                    ],
                ],
            ],
        ],
        [
            'title' => 'Ph.D Level Fee Structure of Faculty of Computer Sciences',
            'heading' => 'PhD – Faculty of Computer Sciences',
            'subheading' => 'Program: PhD Computer Science',
            'tables' => [
                [
                    'label' => null,
                    'rows' => [
                        feeRow('1', 'Admission Fee', '50,000'),
                        feeRow('', 'Total One Time Charges', '50,000', true, true, true),
                        feeRow('2', 'Tuition Fee (Per Credit Hour)', '15,246'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '8,600'),
                        feeRow('', 'Fee at Time of Admission', '195,814', true, true, true),
                        feeRow('4', '2nd Semester Fee', '145,214'),
                        feeRow('', 'Total Fee (Estimated)', '816,408', true, true, true),
                        feeRow('5', 'External Viva', 'USD 300'),
                        feeRow('6', 'Credit Hours (1st Semester)', '9'),
                        feeRow('7', 'Total Credit Hours', '48'),
                    ],
                ],
            ],
        ],
        [
            'title' => 'Ph.D Level Fee Structure of Faculty of Social Sciences',
            'heading' => 'PhD – Faculty of Social Sciences',
            'subheading' => null,
            'tables' => [
                [
                    'label' => 'PhD Management Sciences',
                    'rows' => [
                        feeRow('1', 'Admission Fee', '50,000'),
                        feeRow('', 'Total One Time Charges', '50,000', true, true, true),
                        feeRow('2', 'Tuition Fee (Per Credit Hour)', '9,240'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '8,600'),
                        feeRow('', 'Fee at the Time of Admission', '141,760', true, true, true),
                        feeRow('4', '2nd Semester Fee', '91,160'),
                        feeRow('', 'Total Fee (Estimated)', '523,120', true, true, true),
                        feeRow('5', 'External Viva', 'USD 300'),
                        feeRow('6', 'Credit Hours (1st Semester)', '9'),
                        feeRow('7', 'Total Credit Hours', '48'),
                    ],
                ],
                [
                    'label' => 'PhD Islamic Studies',
                    'rows' => [
                        feeRow('1', 'Admission Fee', '50,000'),
                        feeRow('', 'Total One Time Charges', '50,000', true, true, true),
                        feeRow('2', 'Tuition Fee (Per Credit Hour)', '9,240'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '8,600'),
                        feeRow('', 'Fee at the Time of Admission', '141,760', true, true, true),
                        feeRow('4', '2nd Semester Fee', '91,160'),
                        feeRow('', 'Total Fee (Estimated)', '412,240', true, true, true),
                        feeRow('5', 'External Viva', 'USD 300'),
                        feeRow('6', 'Credit Hours (1st Semester)', '9'),
                        feeRow('7', 'Total Credit Hours', '36'),
                    ],
                ],
            ],
        ],
        [
            'title' => 'Ph.D Level Fee Structure of Faculty of Basic Sciences',
            'heading' => 'PhD – Faculty of Basic Sciences',
            'subheading' => null,
            'tables' => [
                [
                    'label' => 'PhD Chemistry',
                    'rows' => [
                        feeRow('1', 'Admission Fee', '50,000'),
                        feeRow('', 'Total One Time Charges', '50,000', true, true, true),
                        feeRow('2', 'Tuition Fee (Per Credit Hour)', '12,835'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '8,600'),
                        feeRow('', 'Fee at Time of Admission', '174,115', true, true, true),
                        feeRow('4', '2nd Semester Fee', '123,515'),
                        feeRow('', 'Total Fee (Estimated)', '772,690', true, true, true),
                        feeRow('5', 'External Viva', 'USD 300'),
                        feeRow('6', 'Credit Hours (1st Semester)', '9'),
                        feeRow('7', 'Total Credit Hours', '54'),
                    ],
                ],
                [
                    'label' => 'PhD Micro Biology, Zoology, Mathematics',
                    'rows' => [
                        feeRow('1', 'Admission Fee', '50,000'),
                        feeRow('', 'Total One Time Charges', '50,000', true, true, true),
                        feeRow('2', 'Tuition Fee (Per Credit Hour)', '13,860'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '8,600'),
                        feeRow('', 'Fee at Time of Admission', '183,340', true, true, true),
                        feeRow('4', '2nd Semester Fee', '132,740'),
                        feeRow('', 'Total Fee (Estimated)', '744,880', true, true, true),
                        feeRow('5', 'External Viva', 'USD 300'),
                        feeRow('6', 'Credit Hours (1st Semester)', '9'),
                        feeRow('7', 'Total Credit Hours', '48'),
                    ],
                ],
            ],
        ],
        [
            'title' => 'Ph.D Level Fee Structure of Faculty of Languages',
            'heading' => 'PhD – Faculty of Languages',
            'subheading' => 'Program: PhD Urdu',
            'tables' => [
                [
                    'label' => null,
                    'rows' => [
                        feeRow('1', 'Admission Fee', '50,000'),
                        feeRow('', 'Total One Time Charges', '50,000', true, true, true),
                        feeRow('2', 'Tuition Fee (Per Credit Hour)', '9,240'),
                        feeRow('3', 'Misc. Charges (Per Semester)', '8,600'),
                        feeRow('', 'Fee at Time of Admission', '141,760', true, true, true),
                        feeRow('4', '2nd Semester Fee', '91,160'),
                        feeRow('', 'Total Fee (Estimated)', '356,800', true, true, true),
                        feeRow('5', 'External Viva', 'USD 300'),
                        feeRow('6', 'Credit Hours (1st Semester)', '9'),
                        feeRow('7', 'Total Credit Hours', '30'),
                    ],
                ],
            ],
            'notes_after' => [
                'heading' => 'M.Phil/MS & PhD Programs',
                'items' => [
                    '100% concession for LGU Employees (with minimum of 3 years service).',
                    'Fee shall be charged according to Cr Hrs mentioned in Prospectus/Scheme of Studies.',
                    'Credit Hours scheme may be changed during the program.',
                    'Tuition Fee, Exam Fee, Society Fund, Magazine Charges, Misc. Charges and Enrollment Fee shall be charged on semester and annual basis respectively.',
                    'All fees are non-refundable and can be changed without prior notice.',
                ],
            ],
        ],
        [
            'title' => 'BTEC Higher National Diploma',
            'heading' => 'BTEC Higher National Diploma',
            'subheading' => 'Programs: HND Computing, HND Business, HND Creative Media',
            'tables' => [
                [
                    'label' => null,
                    'rows' => [
                        feeRow('1', 'Admission Fee', '50,000'),
                        feeRow('2', 'Registration Fee for HND', '107,750'),
                        feeRow('3', 'Pearson Registration Fee in Pound (One time)', '£300'),
                        feeRow('', 'Total One Time Charges', '157,750', true, true, true),
                        feeRow('4', 'Tuition Fee (1st Semester)', '168,000'),
                        feeRow('5', 'Misc. Charges (Per Semester)', '–'),
                        feeRow('', 'At the Time of Admission', '325,750', true, true, true),
                        feeRow('', '1st Semester Credit Hours (Estimated)', '18', false, true),
                        feeRow('', '2nd Semester Credit Hours (Estimated)', '18', false, true),
                        feeRow('', '2nd Semester Fee (Estimated)', '168,000', false, true),
                        feeRow('', 'Total Charges (Estimated)', '829,750', true, true, true),
                    ],
                ],
            ],
        ],
    ],
];
