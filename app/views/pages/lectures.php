<section class="page-hero">
    <div class="container">
        <?php $crumbs = [['label' => 'Lectures', 'url' => null]]; require basePath('app/views/components/breadcrumb.php'); ?>
        <h1>Online Lectures &amp; Live Classes</h1>
        <p>Recorded lectures mapped to your course, plus live one-on-one coaching with LGU-experienced tutors.</p>
    </div>
</section>

<main class="container section">
    <p class="lectures-intro">
        Browse recorded video lectures the same way you browse past papers on this platform — by department, then
        degree program, then subject — so you only ever see lectures that actually match what you're studying.
        Pick your department below to narrow the degree list, pick your degree to narrow the subject list, then
        filter to see every lecture uploaded for that exact subject. Can't find a topic covered here yet? You can
        always <a href="/lectures/book">book a live one-on-one class</a> with a tutor instead.
    </p>

    <form method="GET" action="/lectures" class="lectures-filter">
        <div class="lectures-filter__field">
            <label for="filter_department">Department</label>
            <select name="department" id="filter_department">
                <option value="">All Departments</option>
                <?php foreach ($departments as $d): ?>
                    <option value="<?= htmlspecialchars($d['slug'], ENT_QUOTES, 'UTF-8') ?>" data-id="<?= (int) $d['id'] ?>" <?= $selectedDept === $d['slug'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($d['name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="lectures-filter__field">
            <label for="filter_degree">Degree Program</label>
            <select name="degree" id="filter_degree" data-selected="<?= htmlspecialchars($selectedDegree, ENT_QUOTES, 'UTF-8') ?>">
                <option value="">All Degrees</option>
            </select>
        </div>
        <div class="lectures-filter__field">
            <label for="filter_subject">Subject</label>
            <select name="subject" id="filter_subject" data-selected="<?= htmlspecialchars($selectedSubject, ENT_QUOTES, 'UTF-8') ?>">
                <option value="">All Subjects</option>
            </select>
        </div>
        <button type="submit" class="btn btn-accent lectures-filter__submit">Filter</button>
    </form>

    <div class="grid" style="margin-top:28px;">
        <?php if (empty($videos)): ?>
            <div class="empty-state" style="grid-column:1/-1;">
                No video lectures match this filter yet — check back soon, or
                <a href="/lectures/book">book a live class</a> instead.
            </div>
        <?php else: ?>
            <?php foreach ($videos as $video): ?>
                <div class="card">
                    <div class="card-img" style="background-image:url('https://img.youtube.com/vi/<?= htmlspecialchars($video['youtube_video_id'], ENT_QUOTES, 'UTF-8') ?>/hqdefault.jpg'); position:relative;">
                        <a href="<?= htmlspecialchars($video['youtube_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; background-color:rgba(8,20,5,0.25); color:#fff; text-decoration:none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="46" height="46" viewBox="0 0 24 24" fill="currentColor" style="filter:drop-shadow(0 4px 6px rgba(0,0,0,0.35));">
                                <path d="M8 5v14l11-7z"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="card-content">
                        <span class="card-meta">
                            <?= htmlspecialchars($video['department_name'] ?? $video['category_name'] ?? 'LGU Hub', ENT_QUOTES, 'UTF-8') ?>
                            <?= !empty($video['sub_department_name']) ? ' · ' . htmlspecialchars($video['sub_department_name'], ENT_QUOTES, 'UTF-8') : '' ?>
                        </span>
                        <h3 class="card-title" style="font-size:1.05rem;"><?= htmlspecialchars($video['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <?php if (!empty($video['subject_name'])): ?>
                            <span class="badge badge-success" style="margin-bottom:8px; display:inline-block;"><?= htmlspecialchars($video['subject_name'], ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                        <p class="card-text"><?= htmlspecialchars(mb_strimwidth($video['description'] ?? '', 0, 90, '…'), ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if ($page < $totalPages): ?>
        <div style="text-align:center; margin-top:28px;">
            <?php $nextQuery = array_filter(['department' => $selectedDept, 'degree' => $selectedDegree, 'subject' => $selectedSubject, 'page' => $page + 1]); ?>
            <a href="/lectures?<?= htmlspecialchars(http_build_query($nextQuery), ENT_QUOTES, 'UTF-8') ?>" class="btn btn-secondary" rel="next">
                See More Lectures
            </a>
        </div>
    <?php endif; ?>
</main>

<script>
window.SUB_DEPARTMENTS = <?= json_encode(array_map(fn($sd) => ['id' => (int) $sd['id'], 'department_id' => (int) $sd['department_id'], 'slug' => $sd['slug'], 'name' => $sd['name']], $subDepartments), JSON_UNESCAPED_UNICODE) ?>;
window.SUBJECTS_BY_SUB_DEPARTMENT = <?= json_encode($subjectsBySubDepartment, JSON_UNESCAPED_UNICODE) ?>;
window.SELECTED_DEPARTMENT_SLUG = <?= json_encode($selectedDept) ?>;
</script>
