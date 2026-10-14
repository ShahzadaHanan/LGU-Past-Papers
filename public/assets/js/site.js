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

    /* ---- Nested "+"/"-" accordions (fee structure page: Group -> Faculty;
       degree page: Subject -> Exam Type). max-height transition needs a
       real pixel value, not 'none', so JS measures scrollHeight on
       open/close.

       Nesting bug this guards against: opening an inner panel (e.g. Mid-
       Term) grows its own height fine, but a still-open OUTER panel (e.g.
       the Subject) had its max-height fixed to whatever it measured
       *before* the inner panel expanded — so the outer clips the now-
       taller content, and overflow:hidden means there's no scrollbar to
       fall back on either. Fix: whenever a panel opens, every still-open
       ancestor panel gets its max-height lifted to a value tall enough
       that it can never be the limiting factor — the box still only
       renders as tall as its actual content (max-height is a ceiling, not
       a forced size), so this doesn't change anything for ancestors whose
       content didn't grow, and un-clips the ones that did, in sync with
       the inner panel's own opening animation rather than one transition
       staggered after another. ---- */
    var ACCORDION_UNCAPPED_HEIGHT = 10000;

    function liftOpenAncestorPanels(panel) {
        var item = panel.closest('[data-accordion]');
        var ancestorPanel = item && item.parentElement ? item.parentElement.closest('[data-accordion-panel]') : null;

        while (ancestorPanel) {
            if (ancestorPanel.classList.contains('open')) {
                ancestorPanel.style.maxHeight = ACCORDION_UNCAPPED_HEIGHT + 'px';
            }
            var ancestorItem = ancestorPanel.closest('[data-accordion]');
            ancestorPanel = ancestorItem && ancestorItem.parentElement ? ancestorItem.parentElement.closest('[data-accordion-panel]') : null;
        }
    }

    function initAccordions() {
        var toggles = document.querySelectorAll('[data-accordion-toggle]');
        if (!toggles.length) return;

        toggles.forEach(function (btn) {
            var item = btn.closest('[data-accordion]');
            if (!item) return;
            var panel = item.querySelector('[data-accordion-panel]');
            if (!panel) return;

            btn.setAttribute('aria-expanded', 'false');
            btn.addEventListener('click', function () {
                var isOpen = panel.classList.contains('open');
                btn.setAttribute('aria-expanded', isOpen ? 'false' : 'true');

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
                    liftOpenAncestorPanels(panel);
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

    /* ---- Header login/signup dialog — Login/Sign Up tab switching ---- */
    function initHeaderAuth() {
        var tabs = document.querySelectorAll('[data-auth-tab]');
        if (!tabs.length) return;

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                var target = tab.getAttribute('data-auth-tab');
                var group = tab.closest('.header-auth');
                if (!group) return;

                group.querySelectorAll('[data-auth-tab]').forEach(function (t) {
                    t.classList.remove('active');
                });
                group.querySelectorAll('[data-auth-panel]').forEach(function (p) {
                    p.hidden = true;
                });

                tab.classList.add('active');
                var panel = group.querySelector('[data-auth-panel="' + target + '"]');
                if (panel) panel.hidden = false;
            });
        });
    }

    /* ---- Google Sign-In (Identity Services) for the header dialog ----
       Only runs at all when the server rendered a [data-google-signin]
       container with a real client id (GOOGLE_CLIENT_ID configured) — see
       header.php. Renders Google's own button, then relays the returned
       ID token to our backend as a normal form POST (so the flow works
       the same as the rest of this login dialog, no fetch/JSON needed). */
    function initGoogleSignIn() {
        var containers = document.querySelectorAll('[data-google-signin]');
        if (!containers.length) return;

        var clientId = containers[0].getAttribute('data-client-id');
        if (!clientId) return;

        function relay(response) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/account/google';

            var fields = {
                _token: containers[0].getAttribute('data-csrf'),
                redirect_to: containers[0].getAttribute('data-redirect'),
                credential: response.credential
            };

            Object.keys(fields).forEach(function (name) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                input.value = fields[name] || '';
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }

        function renderButtons() {
            window.google.accounts.id.initialize({
                client_id: clientId,
                callback: relay
            });
            containers.forEach(function (el) {
                window.google.accounts.id.renderButton(el, { theme: 'outline', size: 'large', width: 240 });
            });
        }

        if (window.google && window.google.accounts) {
            renderButtons();
            return;
        }

        var script = document.createElement('script');
        script.src = 'https://accounts.google.com/gsi/client';
        script.async = true;
        script.defer = true;
        script.onload = renderButtons;
        document.head.appendChild(script);
    }

    /* ---- LGU SGPA / CGPA calculators (/lgu-sgpa-calculator,
       /lgu-cgpa-calculator). Grade points and credits are kept as integers
       in hundredths so LGU's rounding rule — 2 decimals, third decimal >= 5
       rounds up — is exact: round(P/C) half-up == floor((2P + C) / 2C).
       Float math (Math.round(x * 100) / 100) misrounds cases like 2.675. */
    function gpaHundredths(points100, credits) {
        return credits > 0 ? Math.floor((2 * points100 + credits) / (2 * credits)) : null;
    }
    window.lguGpa = { gpaHundredths: gpaHundredths };

    function initGpaCalculators() {
        document.querySelectorAll('[data-gpa-calc]').forEach(function (calc) {
            var type = calc.getAttribute('data-gpa-calc');
            var rows = calc.querySelector('[data-gpa-rows]');
            var template = calc.querySelector('[data-gpa-row-template]');
            var initial = parseInt(calc.getAttribute('data-gpa-initial'), 10) || 1;
            var addBtn = calc.querySelector('[data-gpa-add]');
            var result = calc.parentNode.querySelector('.gpa-result');
            if (!rows || !template || !result) return;

            function addRow() {
                rows.appendChild(template.content.cloneNode(true));
                return rows.lastElementChild;
            }

            function reset() {
                rows.innerHTML = '';
                for (var i = 0; i < initial; i++) addRow();
                compute();
            }

            function note(text, tone) {
                var li = document.createElement('li');
                li.className = 'gpa-note gpa-note--' + tone;
                li.textContent = text;
                return li;
            }

            function compute() {
                var points100 = 0, credits = 0, hasFail = false, hasExcluded = false, hasInvalid = false;

                rows.querySelectorAll('[data-gpa-row]').forEach(function (row) {
                    if (type === 'sgpa') {
                        var grade = row.querySelector('[data-gpa-grade]').value;
                        var ch = parseInt(row.querySelector('[data-gpa-credits]').value, 10);
                        if (grade === '') return;
                        if (grade === 'x') { hasExcluded = true; return; }
                        var gp = Math.round(parseFloat(grade) * 100);
                        if (gp === 0) hasFail = true;
                        points100 += gp * ch;
                        credits += ch;
                    } else {
                        var gpaInput = row.querySelector('[data-gpa-semester-gpa]');
                        var chInput = row.querySelector('[data-gpa-semester-credits]');
                        var g = parseFloat(gpaInput.value);
                        var c = parseInt(chInput.value, 10);
                        var gpaBad = gpaInput.value !== '' && !(g >= 0 && g <= 4);
                        var chBad = chInput.value !== '' && !(c >= 1 && String(c) === chInput.value.trim());
                        gpaInput.setAttribute('aria-invalid', gpaBad ? 'true' : 'false');
                        chInput.setAttribute('aria-invalid', chBad ? 'true' : 'false');
                        if (gpaBad || chBad) hasInvalid = true;
                        if (gpaBad || chBad || isNaN(g) || isNaN(c)) return;
                        points100 += Math.round(g * 100) * c;
                        credits += c;
                    }
                });

                var gpa = gpaHundredths(points100, credits);
                result.querySelector('[data-gpa-value]').textContent = gpa === null ? '—' : (gpa / 100).toFixed(2);
                result.querySelector('[data-gpa-credits-total]').textContent = credits;
                result.querySelector('[data-gpa-points-total]').textContent = (points100 / 100).toFixed(2);

                var notes = result.querySelector('[data-gpa-notes]');
                notes.innerHTML = '';
                if (hasInvalid) {
                    notes.appendChild(note('A row marked in red was skipped: GPA must be 0–4 and credit hours a whole number of 1 or more.', 'warn'));
                }
                if (gpa === null) {
                    notes.appendChild(note(type === 'sgpa'
                        ? 'Pick a grade for at least one course to see your SGPA.'
                        : 'Enter a GPA (0–4) and credit hours for at least one semester.', 'info'));
                    return;
                }

                if (type === 'sgpa') {
                    notes.appendChild(gpa >= 200
                        ? note('At or above 2.00: good academic standing for this semester.', 'good')
                        : note('Below 2.00: an academic deficiency. The first time puts you on probation (max two in a BS).', 'warn'));
                    if (hasFail) notes.appendChild(note('F/SF counts 0.00 points but its credit hours still count. Repeat the course to clear it.', 'warn'));
                    if (hasExcluded) notes.appendChild(note('W and I grades are left out of the GPA, as per LGU policy.', 'info'));
                } else {
                    if (gpa >= 350) notes.appendChild(note('3.50 or above: meets the minimum CGPA for LGU academic medals.', 'good'));
                    notes.appendChild(gpa >= 200
                        ? note('At or above 2.00: good standing, and meets the minimum CGPA for a BS degree.', 'good')
                        : note('Below 2.00: academic deficiency. Improve D/C- courses (up to 5) to raise your CGPA.', 'warn'));
                }
            }

            rows.addEventListener('input', compute);
            rows.addEventListener('change', compute);

            rows.addEventListener('click', function (e) {
                var btn = e.target.closest('[data-gpa-remove]');
                if (!btn) return;
                if (rows.children.length > 1) {
                    btn.closest('[data-gpa-row]').remove();
                    if (addBtn) addBtn.focus();
                } else {
                    reset();
                }
                compute();
            });

            if (addBtn) {
                addBtn.addEventListener('click', function () {
                    var row = addRow();
                    var first = row.querySelector('input, select');
                    if (first) first.focus();
                    compute();
                });
            }

            var resetBtn = calc.querySelector('[data-gpa-reset]');
            if (resetBtn) resetBtn.addEventListener('click', reset);

            reset();
        });
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
        initGpaCalculators();
        initHeaderAuth();
        initGoogleSignIn();
    });
})();
