<?php

declare(strict_types=1);

namespace App\Services;

class CacheService
{
    private string $path;

    public function __construct()
    {
        $this->path=ROOT_PATH.'/storage/cache/';
    }

    public function put(
        string $key,
        mixed $value
    ):void{

        file_put_contents(
            $this->path.md5($key),
            serialize($value)
        );

    }

    public function get(
        string $key
    ):mixed{

        $file=$this->path.md5($key);

        if(
            !file_exists($file)
        ){
            return null;
        }

        return unserialize(
            file_get_contents($file)
        );

    }

    public function forget(
        string $key
    ):void{

        $file=$this->path.md5($key);

        if(file_exists($file)){
            unlink($file);
        }

    }

    public function flush():void
    {
        foreach(
            glob($this->path.'*')
            as $file
        ){
            unlink($file);
        }
    }
}