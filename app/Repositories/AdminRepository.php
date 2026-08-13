<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Core\Repository;

class AdminRepository extends Repository
{
    protected string $table='admins';
}