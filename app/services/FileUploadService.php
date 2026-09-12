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

    /** Maps a verified MIME type to its extension — never trust the
     * client-supplied filename's extension for what gets saved to disk. */
    private array $imageExtensions=[
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    private int $maxSize;

    public function __construct()
    {
        $config = require ROOT_PATH . '/app/config/upload.php';

        $this->maxSize = $config['max_size'] ?? (5 * 1024 * 1024);
    }

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

        if($file['size'] > $this->maxSize){
            throw new \RuntimeException(
                sprintf('Image exceeds the %dMB upload limit.', (int) ($this->maxSize / 1024 / 1024))
            );
        }

        $mime = mime_content_type($file['tmp_name']);

        if(
            !in_array(
                $mime,
                $this->allowedImages,
                true
            )
        ){
            throw new \RuntimeException(
                'Invalid image.'
            );
        }

        $extension = $this->imageExtensions[$mime];

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

    /**
     * Alias used by public-facing "attach an image" flows (e.g. the paper
     * submission form) that don't need to distinguish image vs document.
     */
    public function upload(
        array $file,
        string $directory
    ):string{

        return $this->image($file, $directory);

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

        if($file['size'] > $this->maxSize){
            throw new \RuntimeException(
                sprintf('Document exceeds the %dMB upload limit.', (int) ($this->maxSize / 1024 / 1024))
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