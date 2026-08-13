<?php
$base = 'c:/Github Projects/LGU-Past-Papers/app/';
$models = ['ClassBooking', 'PaperSubmission', 'NewsletterSubscriber', 'AlumniTestimonial'];

// Create empty files for models, repositories, services, controllers
foreach ($models as $m) {
    file_put_contents($base . 'models/' . $m . '.php', '');
    file_put_contents($base . 'Repositories/' . $m . 'Repository.php', '');
    file_put_contents($base . 'services/' . $m . 'Service.php', '');
    file_put_contents($base . 'controllers/Admin/' . $m . 'Controller.php', '');
}
// For Admin User
file_put_contents($base . 'services/AdminUserService.php', '');
file_put_contents($base . 'controllers/Admin/AdminUserController.php', '');

echo "Done";
