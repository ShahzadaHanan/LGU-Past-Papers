<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOStatement;

class Database
{
    private PDO $pdo;

    public function __construct()
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            Config::get('DB_HOST'),
            Config::get('DB_PORT'),
            Config::get('DB_DATABASE')
        );

        $this->pdo = new PDO(
            $dsn,
            Config::get('DB_USERNAME'),
            Config::get('DB_PASSWORD'),
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    public function query(string $sql, array $params = []): PDOStatement
    {
        $statement = $this->pdo->prepare($sql);

        $statement->execute($params);

        return $statement;
    }

    public function fetch(string $sql, array $params = []): array|false
    {
        return $this->query($sql, $params)->fetch();
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    /**
     * Success means the statement executed without error, not that it
     * changed rows — an UPDATE that matches 0 rows (submitted with
     * unchanged values) is still a successful no-op, not a failure.
     * PDO::ERRMODE_EXCEPTION means a real failure already threw before
     * reaching this return, so this reports the statement's own result.
     */
    public function execute(string $sql, array $params = []): bool
    {
        $statement = $this->pdo->prepare($sql);

        return $statement->execute($params);
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }
}