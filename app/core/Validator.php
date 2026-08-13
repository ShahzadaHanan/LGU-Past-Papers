<?php

declare(strict_types=1);

namespace App\Core;

class Validator
{
    private array $errors=[];

    public function required(
        string $field,
        mixed $value,
        string $message='This field is required.'
    ): self{

        if(
            $value===null ||
            trim((string)$value)===''
        ){
            $this->errors[$field][]=$message;
        }

        return $this;
    }

    public function email(
        string $field,
        mixed $value,
        string $message='Invalid email.'
    ): self{

        if(
            !filter_var(
                $value,
                FILTER_VALIDATE_EMAIL
            )
        ){
            $this->errors[$field][]=$message;
        }

        return $this;
    }

    public function min(
        string $field,
        string $value,
        int $length
    ): self{

        if(
            strlen($value)<$length
        ){
            $this->errors[$field][]=
            "Minimum {$length} characters.";
        }

        return $this;
    }

    public function max(
        string $field,
        string $value,
        int $length
    ): self{

        if(
            strlen($value)>$length
        ){
            $this->errors[$field][]=
            "Maximum {$length} characters.";
        }

        return $this;
    }

    public function integer(
        string $field,
        mixed $value
    ): self{

        if(
            filter_var(
                $value,
                FILTER_VALIDATE_INT
            )===false
        ){
            $this->errors[$field][]=
            'Must be integer.';
        }

        return $this;
    }

    public function boolean(
        string $field,
        mixed $value
    ): self{

        if(
            !in_array(
                $value,
                [0,1,true,false,'0','1'],
                true
            )
        ){
            $this->errors[$field][]=
            'Invalid value.';
        }

        return $this;
    }

    public function image(
        string $field,
        ?array $file
    ): self{

        if(!$file){
            return $this;
        }

        if(
            strpos(
                $file['type'],
                'image/'
            )!==0
        ){
            $this->errors[$field][]=
            'Invalid image.';
        }

        return $this;
    }

    public function fails():bool
    {
        return !empty($this->errors);
    }

    public function errors():array
    {
        return $this->errors;
    }
}