<?php

declare(strict_types=1);

/**
 * LGU grading rules, transcribed from LGU's own Undergraduate Student
 * Handbook Vol. II (Sep 2025) — "Grading System", "GPA & CGPA Calculation",
 * "Good Academic Standing and Deficiencies", "Repetition of Course(s)" and
 * "Academic Medals" sections. One source for both calculator pages: the
 * grade <select> options, the visible tables and the FAQPage JSON-LD all
 * read from here, so a policy change is a one-file edit.
 *
 * 'points' = null means the grade is excluded from GPA/CGPA (W, I).
 */
return [
    'source_url' => 'https://lgu.edu.pk/wp-content/uploads/2025/09/Student-Handbook-Volume-2.pdf',
    'source_name' => 'LGU Undergraduate Student Handbook, Volume II (2025)',

    'grades' => [
        ['grade' => 'A', 'points' => 4.00, 'note' => 'Excellent'],
        ['grade' => 'A-', 'points' => 3.70, 'note' => ''],
        ['grade' => 'B+', 'points' => 3.30, 'note' => ''],
        ['grade' => 'B', 'points' => 3.00, 'note' => 'Good'],
        ['grade' => 'B-', 'points' => 2.70, 'note' => ''],
        ['grade' => 'C+', 'points' => 2.30, 'note' => ''],
        ['grade' => 'C', 'points' => 2.00, 'note' => 'Satisfactory'],
        ['grade' => 'C-', 'points' => 1.70, 'note' => 'Can be improved'],
        ['grade' => 'D', 'points' => 1.00, 'note' => 'Minimum pass'],
        ['grade' => 'F', 'points' => 0.00, 'note' => 'Fail — credits still count'],
        ['grade' => 'SF', 'points' => 0.00, 'note' => 'Fail (short attendance)'],
        ['grade' => 'W', 'points' => null, 'note' => 'Withdrawal — not counted'],
        ['grade' => 'I', 'points' => null, 'note' => 'Incomplete — not counted'],
    ],

    // Absolute grading chart — applies only where the class has fewer
    // than 20 students; larger classes are graded relatively.
    'absolute' => [
        ['marks' => '85 and above', 'grade' => 'A', 'points' => '4.00'],
        ['marks' => '79 – 84', 'grade' => 'A-', 'points' => '3.70'],
        ['marks' => '74 – 78', 'grade' => 'B+', 'points' => '3.30'],
        ['marks' => '70 – 73', 'grade' => 'B', 'points' => '3.00'],
        ['marks' => '66 – 69', 'grade' => 'B-', 'points' => '2.70'],
        ['marks' => '62 – 65', 'grade' => 'C+', 'points' => '2.30'],
        ['marks' => '58 – 61', 'grade' => 'C', 'points' => '2.00'],
        ['marks' => '54 – 57', 'grade' => 'C-', 'points' => '1.70'],
        ['marks' => '50 – 53', 'grade' => 'D', 'points' => '1.00'],
        ['marks' => 'Below 50', 'grade' => 'F', 'points' => '0.00'],
    ],

    'faqs' => [
        'sgpa' => [
            [
                'q' => 'How is SGPA calculated at Lahore Garrison University?',
                'a' => 'Multiply each course\'s grade point by its credit hours, add these up, and divide by the total attempted credit hours of the semester. For example, B (3.00) in a 4-credit course, A- (3.70) in a 3-credit course, B+ (3.30) in a 3-credit course and F (0.00) in a 2-credit course give 33 grade points over 12 credit hours, an SGPA of 2.75.',
            ],
            [
                'q' => 'Does an F grade count in my LGU GPA?',
                'a' => 'Yes. F and SF (failure due to short attendance) carry 0.00 grade points, but their credit hours are still counted as attempted, so they pull your GPA down. Once you clear the course by repeating it, only one F is counted and the better attempt is shown on your transcript.',
            ],
            [
                'q' => 'Are W and I grades included in the GPA?',
                'a' => 'No. A W (withdrawal) is not used in GPA or CGPA, and its credit hours are not counted. An I (incomplete) is replaced by your actual grade once the Grade-I exam is marked, and that grade then enters the calculation.',
            ],
            [
                'q' => 'What is the minimum passing grade at LGU?',
                'a' => 'You must earn at least a D (1.00 grade point) to clear a course. Under absolute grading, used for classes of fewer than 20 students, a D starts at 50 marks.',
            ],
            [
                'q' => 'What happens if my semester GPA is below 2.00?',
                'a' => 'A semester GPA below 2.00 is an academic deficiency. The first time it happens you are placed on first probation and promoted to the next regular semester; a second time puts you on second probation. A maximum of two probations are allowed in a four-year bachelor\'s programme.',
            ],
            [
                'q' => 'How does LGU round the GPA?',
                'a' => 'The GPA is shown to two decimal places. If the third decimal is 5 or more, the second decimal is raised by one, so 2.666667 becomes 2.67 and 2.872223 becomes 2.87.',
            ],
        ],
        'cgpa' => [
            [
                'q' => 'How is CGPA calculated at LGU?',
                'a' => 'CGPA is the sum of all grade points earned across every semester divided by the sum of all attempted credit hours. For example, 33 grade points over 12 credit hours in the first semester and 45 over 15 in the second give (33 + 45) / 27 = 2.89.',
            ],
            [
                'q' => 'Can I calculate CGPA by averaging my semester GPAs?',
                'a' => 'Only if every semester has the same credit hours. Otherwise you must weight each semester GPA by its credit hours, which is exactly what this calculator does. A plain average gives the wrong result when credit loads differ.',
            ],
            [
                'q' => 'What CGPA do I need to get a BS degree from LGU?',
                'a' => 'A minimum CGPA of 2.00 is required for a BS degree. You also need the programme\'s minimum credit hours (120 for one Major under the new undergraduate policy, 124 under the old policy), no uncleared F, SF, I or W grades, and any required project, internship or thesis completed.',
            ],
            [
                'q' => 'How can I improve my CGPA at LGU?',
                'a' => 'You can repeat courses in which you got a D or C- to improve your CGPA, when the department offers them in a regular or summer semester. Both grades appear on the transcript but the better one counts. A maximum of 5 courses can be improved in the whole degree.',
            ],
            [
                'q' => 'What CGPA is needed for an LGU gold medal?',
                'a' => 'Gold, silver and bronze medals require a minimum CGPA of 3.50, the highest standing in the session, completion in the minimum time, and every course cleared in the first attempt with no repeats, F grades, withdrawals or semester freezes.',
            ],
            [
                'q' => 'What is good academic standing at LGU?',
                'a' => 'You are in good academic standing if you keep a GPA of at least 2.00 and a CGPA of at least 2.00 at the end of each regular semester. Falling below 2.00, or earning an F, is an academic deficiency.',
            ],
        ],
    ],
];
