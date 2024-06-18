<?php
namespace App\Service;
use Symfony\Component\HttpFoundation\File\UploadedFile;
interface ImageServiceInterface
{
    public function moveImageToUploads(array $fileInfo): ?string;

    public function updateImage(?string $pathImage, ?array $fileInfo): ?string;

    public function deleteImage(string $pathImage): void;

}
