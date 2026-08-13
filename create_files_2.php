<?php

$base = 'c:/Github Projects/LGU-Past-Papers/app/';

$modules = [
    'ClassBooking' => [
        'fields' => "
        public ?int \$id = null,
        public string \$name,
        public string \$email,
        public string \$phone,
        public string \$class_name,
        public string \$booking_date,
        public string \$status = 'pending',
        public string \$created_at = '',
        public string \$updated_at = ''
        ",
        'fromArray' => "
            id: isset(\$data['id']) ? (int) \$data['id'] : null,
            name: \$data['name'] ?? '',
            email: \$data['email'] ?? '',
            phone: \$data['phone'] ?? '',
            class_name: \$data['class_name'] ?? '',
            booking_date: \$data['booking_date'] ?? '',
            status: \$data['status'] ?? 'pending',
            created_at: \$data['created_at'] ?? '',
            updated_at: \$data['updated_at'] ?? ''
        ",
        'table' => 'class_bookings',
        'insertCols' => "name, email, phone, class_name, booking_date, status",
        'insertParams' => ":name, :email, :phone, :class_name, :booking_date, :status",
        'updateSet' => "name = :name, email = :email, phone = :phone, class_name = :class_name, booking_date = :booking_date, status = :status",
        'dataMap' => "
                'name' => \$data['name'],
                'email' => \$data['email'],
                'phone' => \$data['phone'],
                'class_name' => \$data['class_name'],
                'booking_date' => \$data['booking_date'],
                'status' => \$data['status'] ?? 'pending'
        "
    ],
    'PaperSubmission' => [
        'fields' => "
        public ?int \$id = null,
        public string \$student_name,
        public string \$student_email,
        public string \$title,
        public int \$sub_department_id,
        public string \$exam_type,
        public string \$year,
        public string \$file_path,
        public string \$status = 'pending',
        public string \$created_at = '',
        public string \$updated_at = ''
        ",
        'fromArray' => "
            id: isset(\$data['id']) ? (int) \$data['id'] : null,
            student_name: \$data['student_name'] ?? '',
            student_email: \$data['student_email'] ?? '',
            title: \$data['title'] ?? '',
            sub_department_id: (int)(\$data['sub_department_id'] ?? 0),
            exam_type: \$data['exam_type'] ?? '',
            year: \$data['year'] ?? '',
            file_path: \$data['file_path'] ?? '',
            status: \$data['status'] ?? 'pending',
            created_at: \$data['created_at'] ?? '',
            updated_at: \$data['updated_at'] ?? ''
        ",
        'table' => 'paper_submissions',
        'insertCols' => "student_name, student_email, title, sub_department_id, exam_type, year, file_path, status",
        'insertParams' => ":student_name, :student_email, :title, :sub_department_id, :exam_type, :year, :file_path, :status",
        'updateSet' => "student_name = :student_name, student_email = :student_email, title = :title, sub_department_id = :sub_department_id, exam_type = :exam_type, year = :year, file_path = :file_path, status = :status",
        'dataMap' => "
                'student_name' => \$data['student_name'],
                'student_email' => \$data['student_email'],
                'title' => \$data['title'],
                'sub_department_id' => \$data['sub_department_id'],
                'exam_type' => \$data['exam_type'],
                'year' => \$data['year'],
                'file_path' => \$data['file_path'],
                'status' => \$data['status'] ?? 'pending'
        "
    ],
    'NewsletterSubscriber' => [
        'fields' => "
        public ?int \$id = null,
        public string \$email,
        public bool \$is_active = true,
        public string \$created_at = '',
        public string \$updated_at = ''
        ",
        'fromArray' => "
            id: isset(\$data['id']) ? (int) \$data['id'] : null,
            email: \$data['email'] ?? '',
            is_active: isset(\$data['is_active']) ? (bool) \$data['is_active'] : true,
            created_at: \$data['created_at'] ?? '',
            updated_at: \$data['updated_at'] ?? ''
        ",
        'table' => 'newsletter_subscribers',
        'insertCols' => "email, is_active",
        'insertParams' => ":email, :is_active",
        'updateSet' => "email = :email, is_active = :is_active",
        'dataMap' => "
                'email' => \$data['email'],
                'is_active' => \$data['is_active'] ?? 1
        "
    ],
    'AlumniTestimonial' => [
        'fields' => "
        public ?int \$id = null,
        public string \$name,
        public string \$graduation_year,
        public string \$content,
        public string \$image_path,
        public bool \$is_active = true,
        public string \$created_at = '',
        public string \$updated_at = ''
        ",
        'fromArray' => "
            id: isset(\$data['id']) ? (int) \$data['id'] : null,
            name: \$data['name'] ?? '',
            graduation_year: \$data['graduation_year'] ?? '',
            content: \$data['content'] ?? '',
            image_path: \$data['image_path'] ?? '',
            is_active: isset(\$data['is_active']) ? (bool) \$data['is_active'] : true,
            created_at: \$data['created_at'] ?? '',
            updated_at: \$data['updated_at'] ?? ''
        ",
        'table' => 'alumni_testimonials',
        'insertCols' => "name, graduation_year, content, image_path, is_active",
        'insertParams' => ":name, :graduation_year, :content, :image_path, :is_active",
        'updateSet' => "name = :name, graduation_year = :graduation_year, content = :content, image_path = :image_path, is_active = :is_active",
        'dataMap' => "
                'name' => \$data['name'],
                'graduation_year' => \$data['graduation_year'],
                'content' => \$data['content'],
                'image_path' => \$data['image_path'],
                'is_active' => \$data['is_active'] ?? 1
        "
    ]
];

foreach ($modules as $m => $cfg) {
    // Model
    $modelCode = "<?php\n\ndeclare(strict_types=1);\n\nnamespace App\Models;\n\nclass {$m}\n{\n    public function __construct({$cfg['fields']}    ) {\n    }\n\n    public static function fromArray(array \$data): self\n    {\n        return new self({$cfg['fromArray']}        );\n    }\n}\n";
    file_put_contents($base . 'models/' . $m . '.php', $modelCode);

    // Repository
    $repoCode = "<?php\n\ndeclare(strict_types=1);\n\nnamespace App\Repositories;\n\nuse App\Core\Database;\nuse App\Models\\{$m};\n\nclass {$m}Repository\n{\n    public function __construct(private Database \$db) {}\n\n    public function all(): array\n    {\n        \$stmt = \$this->db->query(\"SELECT * FROM {$cfg['table']} ORDER BY id DESC\");\n        return array_map(fn(\$row) => {$m}::fromArray(\$row), \$stmt->fetchAll());\n    }\n\n    public function find(int \$id): ?{$m}\n    {\n        \$stmt = \$this->db->query(\"SELECT * FROM {$cfg['table']} WHERE id = :id\", ['id' => \$id]);\n        \$row = \$stmt->fetch();\n        return \$row ? {$m}::fromArray(\$row) : null;\n    }\n\n    public function create(array \$data): bool\n    {\n        return \$this->db->query(\n            \"INSERT INTO {$cfg['table']} ({$cfg['insertCols']}) VALUES ({$cfg['insertParams']})\",\n            [{$cfg['dataMap']}            ]\n        )->rowCount() > 0;\n    }\n\n    public function update(int \$id, array \$data): bool\n    {\n        \$params = [{$cfg['dataMap']}        ];\n        \$params['id'] = \$id;\n        return \$this->db->query(\n            \"UPDATE {$cfg['table']} SET {$cfg['updateSet']} WHERE id = :id\",\n            \$params\n        )->rowCount() > 0;\n    }\n\n    public function delete(int \$id): bool\n    {\n        return \$this->db->query(\"DELETE FROM {$cfg['table']} WHERE id = :id\", ['id' => \$id])->rowCount() > 0;\n    }\n}\n";
    file_put_contents($base . 'Repositories/' . $m . 'Repository.php', $repoCode);

    // Service
    $serviceCode = "<?php\n\ndeclare(strict_types=1);\n\nnamespace App\Services;\n\nuse App\Repositories\\{$m}Repository;\n\nclass {$m}Service\n{\n    public function __construct(private {$m}Repository \$repository) {}\n\n    public function getAll(): array\n    {\n        return \$this->repository->all();\n    }\n\n    public function getById(int \$id)\n    {\n        return \$this->repository->find(\$id);\n    }\n\n    public function create(array \$data): bool\n    {\n        return \$this->repository->create(\$data);\n    }\n\n    public function update(int \$id, array \$data): bool\n    {\n        return \$this->repository->update(\$id, \$data);\n    }\n\n    public function delete(int \$id): bool\n    {\n        return \$this->repository->delete(\$id);\n    }\n}\n";
    file_put_contents($base . 'services/' . $m . 'Service.php', $serviceCode);

    // Controller
    $controllerCode = "<?php\n\ndeclare(strict_types=1);\n\nnamespace App\Controllers\Admin;\n\nuse App\Services\\{$m}Service;\nuse App\Core\View;\n\nclass {$m}Controller\n{\n    public function __construct(private {$m}Service \$service, private View \$view) {}\n\n    public function index()\n    {\n        \$items = \$this->service->getAll();\n        return \$this->view->render('admin/'.strtolower('{$m}').'/index', ['items' => \$items]);\n    }\n\n    public function create()\n    {\n        return \$this->view->render('admin/'.strtolower('{$m}').'/create');\n    }\n\n    public function store()\n    {\n        \$this->service->create(\$_POST);\n        header('Location: /admin/'.strtolower('{$m}'));\n        exit;\n    }\n\n    public function edit(int \$id)\n    {\n        \$item = \$this->service->getById(\$id);\n        return \$this->view->render('admin/'.strtolower('{$m}').'/edit', ['item' => \$item]);\n    }\n\n    public function update(int \$id)\n    {\n        \$this->service->update(\$id, \$_POST);\n        header('Location: /admin/'.strtolower('{$m}'));\n        exit;\n    }\n\n    public function delete(int \$id)\n    {\n        \$this->service->delete(\$id);\n        header('Location: /admin/'.strtolower('{$m}'));\n        exit;\n    }\n}\n";
    file_put_contents($base . 'controllers/Admin/' . $m . 'Controller.php', $controllerCode);
}
echo "Generated basic files.\n";
