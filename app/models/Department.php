<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Department extends Model
{
    protected string $table = 'departments';

    protected array $fillable = [

        'name',

        'slug',

        'hero_image',

        'description',

        'meta_title',

        'meta_description',

        'display_order',

        'is_active'

    ];
}
