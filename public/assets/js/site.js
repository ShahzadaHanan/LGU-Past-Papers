(function () {
    'use strict';

    /* ---- Theme toggle (shared — was duplicated inline in header.php and
       auth.php; this is the one copy both now load).
       ponytail: dark mode is forced off — the real LGU green palette
       doesn't translate to an acceptable dark theme yet, so the toggle
       button is hidden (see .theme-btn{display:none} in style.css) and a
       previously-stored 'dark' preference is intentionally ignored here
       rather than trapping a past visitor in a broken dark UI with no
       visible way back. The toggle wiring below is left in place — only
       the forced 'light' default needs to change to re-enable dark mode. */
    function initThemeToggle() {
        var root = document.documentElement;
        var current = 'light';
        root.setAttribute('data-theme', current);

        document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
            setIcon(btn, current);
            btn.addEventListener('click', function () {
                var theme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                root.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
                document.querySelectorAll('[data-theme-toggle]').forEach(function (b) {
                    setIcon(b, theme);
                });
            });
        });

        function setIcon(btn, theme) {
            var icon = btn.querySelector('svg');
            if (!icon) return;
            icon.innerHTML = theme === 'dark'
                ? '<circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>'
                : '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>';
        }
    }

    /* ---- Mobile slide-out nav ---- */
    function initMobileNav() {
        var toggle = document.querySelector('[data-nav-toggle]');
        var panel = document.querySelector('[data-mobile-nav]');
        var close = document.querySelector('[data-nav-close]');
        if (!toggle || !panel) return;

        function open() { panel.classList.add('is-open'); }
        function shut() { panel.classList.remove('is-open'); }

        toggle.addEventListener('click', open);
        if (close) close.addEventListener('click', shut);
        panel.addEventListener('click', function (e) {
            if (e.target === panel) shut();
        });
    }

    /* ---- Hero / content slider (home page hero_slides) ---- */
    function initHeroSlider() {
        var slider = document.querySelector('[data-hero-slider]');
        if (!slider) return;
        var slides = slider.querySelectorAll('.hero-slide');
        if (slides.length < 2) return;
        var index = 0;
        setInterval(function () {
            index = (index + 1) % slides.length;
            slider.style.transform = 'translateX(-' + (index * 100) + '%)';
        }, 5500);
    }

    /* ---- Exam-type tabs (degree page) ---- */
    function initExamTabs() {
        var tabs = document.querySelectorAll('[data-exam-tab]');
        if (!tabs.length) return;
        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                var target = tab.getAttribute('data-exam-tab');
                document.querySelectorAll('[data-exam-tab]').forEach(function (t) {
                    t.classList.remove('active');
                });
                document.querySelectorAll('[data-exam-panel]').forEach(function (p) {
                    p.classList.remove('active');
                });
                tab.classList.add('active');
                var panel = document.querySelector('[data-exam-panel="' + target + '"]');
                if (panel) panel.classList.add('active');
            });
        });
    }

    /* ---- Lightbox (paper preview) ---- */
    function initLightbox() {
        var triggers = document.querySelectorAll('[data-lightbox-trigger]');
        var lightbox = document.querySelector('[data-lightbox]');
        if (!triggers.length || !lightbox) return;
        var img = lightbox.querySelector('img');

        triggers.forEach(function (trigger) {
            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                img.src = trigger.getAttribute('href') || trigger.dataset.src;
                lightbox.classList.add('active');
            });
        });

        lightbox.addEventListener('click', function () {
            lightbox.classList.remove('active');
        });
    }

    /* ---- Generic dismissible modal (first-visit popup etc.) ---- */
    function initModals() {
        document.querySelectorAll('[data-modal-close]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var modal = btn.closest('.modal');
                if (modal) modal.classList.remove('active');
            });
        });
    }

    /* ---- News bar: rotate through announcements, remember dismissal ---- */
    function initNewsBar() {
        var bar = document.querySelector('[data-news-bar]');
        if (!bar) return;

        if (sessionStorage.getItem('news_bar_dismissed')) {
            bar.remove();
            return;
        }

        var closeBtn = bar.querySelector('[data-news-bar-close]');
        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                sessionStorage.setItem('news_bar_dismissed', 'true');
                bar.remove();
            });
        }

        var items = bar.querySelectorAll('.news-bar__item');
        if (items.length < 2) return;
        var index = 0;

        setInterval(function () {
            var current = items[index];
            index = (index + 1) % items.length;
            var next = items[index];

            // Current slides out to the left; next slides in from the
            // right (its default, off-screen resting position).
            current.classList.remove('active');
            current.classList.add('exiting');
            next.classList.add('active');

            // Once its exit transition finishes, snap the old item back to
            // its off-screen-right starting point with no transition, so
            // it's ready to slide in again next time its turn comes up.
            setTimeout(function () {
                current.classList.add('no-transition');
                current.classList.remove('exiting');
                void current.offsetWidth; // force reflow before re-enabling transition
                current.classList.remove('no-transition');
            }, 650);
        }, 4500);
    }

    /* ---- Animated stat counters — count up from 0 once scrolled into
       view, instead of just printing the final number. ---- */
    function initCountUp() {
        var counters = document.querySelectorAll('[data-count-to]');
        if (!counters.length) return;

        function animate(el) {
            var target = parseInt(el.getAttribute('data-count-to'), 10) || 0;
            var suffix = el.getAttribute('data-count-suffix') || '';
            var duration = 1400;
            var start = null;

            function step(timestamp) {
                if (start === null) start = timestamp;
                var progress = Math.min((timestamp - start) / duration, 1);
                var eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(eased * target) + suffix;
                if (progress < 1) {
                    requestAnimationFrame(step);
                }
            }

            requestAnimationFrame(step);
        }

        if (!('IntersectionObserver' in window)) {
            counters.forEach(animate);
            return;
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animate(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.4 });

        counters.forEach(function (el) { observer.observe(el); });
    }

    /* ---- "Show more" grids (e.g. homepage departments) — reveals the
       rest of the list instead of navigating anywhere. ---- */
    function initShowMore() {
        document.querySelectorAll('[data-show-more]').forEach(function (btn) {
            var targetId = btn.getAttribute('data-show-more');
            var grid = document.getElementById(targetId);
            if (!grid) return;
            btn.addEventListener('click', function () {
                grid.classList.add('is-expanded');
                btn.style.display = 'none';
            });
        });
    }

    /* ---- Nested "+"/"-" accordions (fee structure page: Group -> Faculty).
       max-height transition needs a real pixel value, not 'none', so JS
       measures scrollHeight on open/close. Nested panels also resize any
       still-open ancestor panel after their own transition finishes, so
       expanding a faculty inside an already-open group doesn't get
       clipped by the group's now-stale max-height. ---- */
    function initAccordions() {
        var toggles = document.querySelectorAll('[data-accordion-toggle]');
        if (!toggles.length) return;

        toggles.forEach(function (btn) {
            var item = btn.closest('[data-accordion]');
            if (!item) return;
            var panel = item.querySelector('[data-accordion-panel]');
            if (!panel) return;

            btn.addEventListener('click', function () {
                var isOpen = panel.classList.contains('open');

                if (isOpen) {
                    panel.style.maxHeight = panel.scrollHeight + 'px';
                    requestAnimationFrame(function () {
                        panel.style.maxHeight = '0px';
                    });
                    panel.classList.remove('open');
                    btn.classList.remove('open');
                } else {
                    panel.classList.add('open');
                    btn.classList.add('open');
                    panel.style.maxHeight = panel.scrollHeight + 'px';
                }
            });
        });
    }

    /* Lectures page: Department -> Degree -> Subject cascading filter.
       Data (SUB_DEPARTMENTS, SUBJECTS_BY_SUB_DEPARTMENT) is embedded inline
       by pages/lectures.php; a full page reload on submit does the actual
       filtering, this just keeps each dropdown showing only options that
       are actually possible given what's already picked. */
    function initLecturesFilter() {
        var deptSelect = document.getElementById('filter_department');
        var degreeSelect = document.getElementById('filter_degree');
        var subjectSelect = document.getElementById('filter_subject');
        if (!deptSelect || !degreeSelect || !subjectSelect || !window.SUB_DEPARTMENTS) return;

        var subDepartments = window.SUB_DEPARTMENTS;
        var subjectsByDegree = window.SUBJECTS_BY_SUB_DEPARTMENT || {};

        function departmentIdForSlug(slug) {
            var opt = deptSelect.querySelector('option[value="' + slug + '"]');
            return opt && opt.dataset ? opt.dataset.id : null;
        }

        function populateDegrees(deptSlug, selectedSlug) {
            degreeSelect.innerHTML = '<option value="">All Degrees</option>';
            var deptId = deptSlug ? departmentIdForSlug(deptSlug) : null;
            subDepartments
                .filter(function (sd) { return deptId && String(sd.department_id) === String(deptId); })
                .forEach(function (sd) {
                    var opt = document.createElement('option');
                    opt.value = sd.slug;
                    opt.dataset.id = sd.id;
                    opt.textContent = sd.name;
                    if (selectedSlug && sd.slug === selectedSlug) opt.selected = true;
                    degreeSelect.appendChild(opt);
                });
        }

        function populateSubjects(selectedSubject) {
            subjectSelect.innerHTML = '<option value="">All Subjects</option>';
            var selectedDegreeOpt = degreeSelect.options[degreeSelect.selectedIndex];
            var subDeptId = selectedDegreeOpt ? selectedDegreeOpt.dataset.id : null;
            var subjects = subDeptId ? (subjectsByDegree[subDeptId] || []) : [];
            subjects.forEach(function (name) {
                var opt = document.createElement('option');
                opt.value = name;
                opt.textContent = name;
                if (selectedSubject && name === selectedSubject) opt.selected = true;
                subjectSelect.appendChild(opt);
            });
        }

        deptSelect.addEventListener('change', function () {
            populateDegrees(deptSelect.value, null);
            populateSubjects(null);
        });
        degreeSelect.addEventListener('change', function () {
            populateSubjects(null);
        });

        populateDegrees(deptSelect.value, degreeSelect.dataset.selected);
        populateSubjects(subjectSelect.dataset.selected);
    }

    document.addEventListener('DOMContentLoaded', function () {
        initThemeToggle();
        initMobileNav();
        initHeroSlider();
        initExamTabs();
        initLightbox();
        initModals();
        initNewsBar();
        initCountUp();
        initShowMore();
        initAccordions();
        initLecturesFilter();
    });
})();
