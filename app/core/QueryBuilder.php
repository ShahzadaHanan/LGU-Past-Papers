<?php

declare(strict_types=1);

namespace App\Core;

class QueryBuilder
{
    private array $where=[];

    private array $bindings=[];

    public function where(
        string $column,
        mixed $value
    ):self{

        $this->where[]="$column=?";

        $this->bindings[]=$value;

        return $this;

    }

    public function build():array
    {
        return[
            implode(
                ' AND ',
                $this->where
            ),
            $this->bindings
        ];
    }
}