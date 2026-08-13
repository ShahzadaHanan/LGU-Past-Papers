<?php

declare(strict_types=1);

namespace App\Services;

class FileUploadService
{
    private array $allowedImages=[
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    private array $allowedDocuments=[
        'application/pdf'
    ];

    public function image(
        array $file,
        string $directory
    ):string{

        if(
            $file['error']!==UPLOAD_ERR_OK
        ){
            throw new \RuntimeException(
                'Upload failed.'
            );
        }

        if(
            !in_array(
                mime_content_type(
                    $file['tmp_name']
                ),
                $this->allowedImages,
                true
            )
        ){
            throw new \RuntimeException(
                'Invalid image.'
            );
        }

        $extension=pathinfo(
            $file['name'],
            PATHINFO_EXTENSION
        );

        $filename=uniqid(
            '',
            true
        ).'.'.$extension;

        $path=ROOT_PATH.
        '/public/uploads/'.
        trim($directory,'/');

        if(
            !is_dir($path)
        ){
            mkdir(
                $path,
                0755,
                true
            );
        }

        move_uploaded_file(
            $file['tmp_name'],
            $path.'/'.$filename
        );

        return '/uploads/'.
        trim($directory,'/').
        '/'.$filename;
    }

    public function document(
        array $file,
        string $directory
    ):string{

        if(
            $file['error']!==UPLOAD_ERR_OK
        ){
            throw new \RuntimeException(
                'Upload failed.'
            );
        }

        if(
            !in_array(
                mime_content_type(
                    $file['tmp_name']
                ),
                $this->allowedDocuments,
                true
            )
        ){
            throw new \RuntimeException(
                'Invalid document.'
            );
        }

        $filename=uniqid(
            '',
            true
        ).'.pdf';

        $path=ROOT_PATH.
        '/public/uploads/'.
        trim($directory,'/');

        if(
            !is_dir($path)
        ){
            mkdir(
                $path,
                0755,
                true
            );
        }

        move_uploaded_file(
            $file['tmp_name'],
            $path.'/'.$filename
        );

        return '/uploads/'.
        trim($directory,'/').
        '/'.$filename;
    }

    public function delete(
        ?string $path
    ):void{

        if(!$path){
            return;
        }

        $file=ROOT_PATH.
        '/public'.$path;

        if(
            file_exists($file)
        ){
            unlink($file);
        }

    }
}