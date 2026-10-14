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

        // mime_content_type() only sniffs the file's magic bytes — a
        // polyglot file (valid JPEG header with, say, PHP or HTML appended
        // after the real image data) still passes that check. getimagesize()
        // parses the image header structure, which is a stronger check, but
        // it too only reads as much of the file as it needs — bytes tacked
        // on after a complete, valid image stream still slip through it.
        if (@getimagesize($file['tmp_name']) === false) {
            throw new \RuntimeException(
                'The uploaded file is not a valid image.'
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

        $destination = $path . '/' . $filename;

        // The only check that actually defeats an image+payload polyglot is
        // decoding the image and writing a brand new file from the decoded
        // pixels — GD never serializes anything beyond the image it parsed,
        // so bytes appended after the real image data are discarded here.
        // ponytail: this GD build has no WebP encode/decode support (no
        // imagecreatefromwebp/imagewebp), so WebP uploads only get the
        // getimagesize() check above, not full re-encoding — upgrade path
        // is a GD/Imagick build with WebP support if that gap needs closing.
        if ($mime === 'image/jpeg' || $mime === 'image/png') {
            $decoded = $mime === 'image/jpeg'
                ? @imagecreatefromjpeg($file['tmp_name'])
                : @imagecreatefrompng($file['tmp_name']);

            if ($decoded === false) {
                throw new \RuntimeException('The uploaded file is not a valid image.');
            }

            $saved = $mime === 'image/jpeg'
                ? @imagejpeg($decoded, $destination, 90)
                : @imagepng($decoded, $destination, 6);

            imagedestroy($decoded);

            if (!$saved) {
                throw new \RuntimeException('Failed to process the uploaded image.');
            }
        } else {
            move_uploaded_file(
                $file['tmp_name'],
                $destination
            );
        }

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

    /**
     * Moves an already-uploaded file (identified by its public /uploads/...
     * path, e.g. a pending paper_submissions.image_path) into a different
     * uploads subdirectory, generating a fresh filename. Used when an admin
     * approves a public submission and it becomes a real Paper — the file
     * itself doesn't need to be re-uploaded/re-validated, just relocated.
     */
    public function adopt(string $existingPublicPath, string $directory): string
    {
        $normalized = ltrim($existingPublicPath, '/');

        if (!str_starts_with($normalized, 'uploads/')) {
            throw new \RuntimeException('Invalid source file path.');
        }

        $uploadsRoot = realpath(ROOT_PATH . '/public/uploads');
        $realSource = realpath(ROOT_PATH . '/public/' . $normalized);

        if ($uploadsRoot === false || $realSource === false || !str_starts_with($realSource, $uploadsRoot)) {
            throw new \RuntimeException('Source file not found.');
        }

        $extension = pathinfo($realSource, PATHINFO_EXTENSION);
        $filename = uniqid('', true) . ($extension ? ".{$extension}" : '');

        $destDir = ROOT_PATH . '/public/uploads/' . trim($directory, '/');
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        $destPath = $destDir . '/' . $filename;

        if (!@rename($realSource, $destPath)) {
            if (!copy($realSource, $destPath)) {
                throw new \RuntimeException('Failed to move the submitted file.');
            }
            unlink($realSource);
        }

        return '/uploads/' . trim($directory, '/') . '/' . $filename;
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