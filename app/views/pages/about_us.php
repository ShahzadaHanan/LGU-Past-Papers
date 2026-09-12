<section class="page-hero">
    <div class="container">
        <?php $crumbs = [['label' => 'About Us', 'url' => null]]; require basePath('app/views/components/breadcrumb.php'); ?>
        <h1>About LGU Hub</h1>
        <p>An independent, student-built resource — not an official Lahore Garrison University platform.</p>
    </div>
</section>

<main class="container section">
    <div style="max-width:800px; margin:0 auto; display:flex; flex-direction:column; gap:36px;">
        <?php if (empty($blocks)): ?>
            <div class="card-text" style="font-size:1.05rem; line-height:1.8;">
                <p>
                    LGU Hub was built to solve a simple problem: past papers, lecture notes and exam
                    prep material for LGU students were scattered across WhatsApp groups and Facebook
                    posts, hard to search and easy to lose. This platform organises everything the way
                    students actually look for it — Department → Degree → Exam Type → Course — so a paper
                    from three semesters ago is as easy to find as one from last week.
                </p>
                <p style="margin-top:16px;">
                    Every past paper is student-submitted and reviewed before publishing. We also run a
                    class-booking system connecting students with tutors familiar with LGU's own course
                    structure and exam patterns.
                </p>
            </div>
        <?php else: ?>
            <?php foreach ($blocks as $block): ?>
                <div>
                    <h2 style="font-size:1.5rem; margin-bottom:14px; color:var(--primary-color); border-left:4px solid var(--accent-color); padding-left:16px;">
                        <?= htmlspecialchars($block['heading'], ENT_QUOTES, 'UTF-8') ?>
                    </h2>
                    <div style="font-size:1.02rem; line-height:1.8;">
                        <?= $block['body'] /* trusted admin-authored rich text */ ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div style="text-align:center; margin-top:8px; display:flex; gap:14px; justify-content:center; flex-wrap:wrap;">
            <a href="/contact-us" class="btn">Submit a Past Paper</a>
            <a href="/departments" class="btn btn-ghost">Browse Departments</a>
        </div>
    </div>
</main>
