<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Setup env
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

// Instantiate Database class
$db = new \App\Core\Database();

echo "Running Database Migrations...\n";

// 1. Create migrations tracking table if it doesn't exist
$db->execute("
    CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) NOT NULL UNIQUE,
        run_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;
");

// 2. Scan migrations directory
$migrationsDir = __DIR__ . '/migrations';
$files = scandir($migrationsDir);
$migrationFiles = [];

foreach ($files as $file) {
    if (in_array($file, ['.', '..'])) {
        continue;
    }
    // Match .sql files or .php files (which are actually SQL structure)
    if (str_ends_with($file, '.sql') || str_ends_with($file, '.php')) {
        $migrationFiles[] = $file;
    }
}

sort($migrationFiles);

// Get executed migrations
$runMigrations = [];
$rows = $db->fetchAll("SELECT migration FROM migrations");
foreach ($rows as $row) {
    $runMigrations[] = $row['migration'];
}

// 3. Execute outstanding migrations
foreach ($migrationFiles as $file) {
    if (in_array($file, $runMigrations)) {
        echo "Migration $file already run. Skipping.\n";
        continue;
    }

    echo "Running migration $file...\n";
    $content = file_get_contents($migrationsDir . '/' . $file);
    
    // Split SQL by semicolon, handling multi-query SQL files
    // But be careful not to split inside trigger/routines if any.
    // For simple migrations, splitting by semicolon is fine if we strip whitespace
    $queries = array_filter(array_map('trim', explode(';', $content)));
    
    try {
        foreach ($queries as $query) {
            if (empty($query)) {
                continue;
            }
            $db->pdo()->exec($query);
        }
        
        // Log migration
        $db->execute("INSERT INTO migrations (migration) VALUES (?)", [$file]);
        echo "Migration $file successfully run.\n";
    } catch (\PDOException $e) {
        if ($e->getCode() === '42S01') {
            echo "Table(s) from $file already exist. Marking migration as completed.\n";
    
            $db->execute(
                "INSERT IGNORE INTO migrations (migration) VALUES (?)",
                [$file]
            );
    
            continue;
        }
    
        throw $e;
    }
}

// 4. Seed default super admin user if none exists
$adminsCount = (int) $db->fetch("SELECT COUNT(*) as total FROM admins")['total'];
if ($adminsCount === 0) {
    echo "Seeding default superadmin user...\n";
    $passwordHash = password_hash('Admin123!', PASSWORD_BCRYPT);
    $db->execute("
        INSERT INTO admins (name, email, password_hash, role, is_active)
        VALUES ('LGU Admin', 'admin@lgu.edu.pk', ?, 'super_admin', 1)
    ", [$passwordHash]);
    echo "Default Admin seeded. Email: admin@lgu.edu.pk | Password: Admin123!\n";
}

echo "Database Migrations completed successfully.\n";
