<?php

declare(strict_types=1);

namespace App\Core;

abstract class CrudService
{
    public function __construct(
        protected Repository $repository
    ){
    }

    public function all():array
    {
        return $this->repository->all();
    }

    public function find(
        int $id
    ):array|false{

        return $this->repository->find($id);

    }

    public function create(
        array $data
    ):bool{

        return $this->repository->create($data);

    }

    public function update(
        int $id,
        array $data
    ):bool{

        return $this->repository->update(
            $id,
            $data
        );

    }

    public function delete(
        int $id
    ):bool{

        return $this->repository->delete($id);

    }
}