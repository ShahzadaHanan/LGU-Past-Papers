<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

class ActivityLogger
{
    public function __construct(
        private Database $database
    ){
    }

    public function log(
        ?int $adminId,
        string $module,
        string $action,
        ?int $recordId=null,
        ?string $description=null
    ):void{

        $this->database->execute(

            "INSERT INTO activity_logs
            (
                admin_id,
                module,
                action,
                record_id,
                description,
                ip_address,
                user_agent
            )
            VALUES
            (?,?,?,?,?,?,?)",

            [

                $adminId,

                $module,

                $action,

                $recordId,

                $description,

                $_SERVER['REMOTE_ADDR'] ?? null,

                $_SERVER['HTTP_USER_AGENT'] ?? null

            ]

        );

    }
}