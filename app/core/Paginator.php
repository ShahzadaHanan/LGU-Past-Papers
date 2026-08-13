<?php

declare(strict_types=1);

namespace App\Core;

class Paginator
{
    public function __construct(
        private int $total,
        private int $page=1,
        private int $perPage=15
    ){
    }

    public function offset():int
    {
        return ($this->page-1)*$this->perPage;
    }

    public function limit():int
    {
        return $this->perPage;
    }

    public function totalPages():int
    {
        return (int)ceil(
            $this->total/$this->perPage
        );
    }

    public function page():int
    {
        return $this->page;
    }

    public function hasNext():bool
    {
        return $this->page<$this->totalPages();
    }

    public function hasPrevious():bool
    {
        return $this->page>1;
    }
}