'use strict';

document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('admin-theme-toggle');
    var icon = document.getElementById('admin-theme-icon');
    var root = document.documentElement;

    var current = localStorage.getItem('theme') || 'light';
    root.setAttribute('data-theme', current);
    updateIcon(current);

    if (toggle) {
        toggle.addEventListener('click', function () {
            var theme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            updateIcon(theme);
        });
    }

    function updateIcon(theme) {
        if (!icon) return;
        if (theme === 'dark') {
            icon.innerHTML = '<circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>';
        } else {
            icon.innerHTML = '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>';
        }
    }

    // Confirm before any destructive delete action
    document.querySelectorAll('form.form-delete').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!confirm('Are you sure you want to delete this item? This cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    // Department -> Degree cascading select (video create/edit forms).
    // window.SUB_DEPARTMENTS is embedded inline by the view before this file loads.
    var deptSelect = document.getElementById('department_select');
    var subDeptSelect = document.getElementById('sub_department_select');
    if (deptSelect && subDeptSelect && window.SUB_DEPARTMENTS) {
        var selectedSubDept = subDeptSelect.dataset.selected || '';
        var populateDegrees = function (deptId, selectedId) {
            subDeptSelect.innerHTML = '<option value="">Select degree</option>';
            window.SUB_DEPARTMENTS
                .filter(function (sd) { return String(sd.department_id) === String(deptId); })
                .forEach(function (sd) {
                    var opt = document.createElement('option');
                    opt.value = sd.id;
                    opt.textContent = sd.name;
                    if (selectedId && String(sd.id) === String(selectedId)) opt.selected = true;
                    subDeptSelect.appendChild(opt);
                });
        };
        deptSelect.addEventListener('change', function () { populateDegrees(deptSelect.value, null); });
        if (deptSelect.value) populateDegrees(deptSelect.value, selectedSubDept);
    }

    // Sidebar active-link highlight
    var path = window.location.pathname;
    document.querySelectorAll('.sidebar a').forEach(function (link) {
        if (link.getAttribute('href') === path || (path.indexOf(link.getAttribute('href')) === 0 && link.getAttribute('href') !== '/admin/dashboard')) {
            link.classList.add('active');
        }
    });
});
