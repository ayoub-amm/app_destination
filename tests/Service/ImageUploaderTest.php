<?php

namespace App\Tests\Service;

use App\Service\ImageUploader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class ImageUploaderTest extends TestCase
{
    private $imageUploader;
    private $parameterBagMock;
    private $filesystem;
    private $uploadDir;

    protected function setUp(): void
    {
        $this->filesystem = new Filesystem();

        $this->uploadDir = sys_get_temp_dir() . '/uploads/images';
        $this->filesystem->mkdir($this->uploadDir);

        $this->parameterBagMock = $this->createMock(ParameterBagInterface::class);
        $this->parameterBagMock->method('get')->willReturn($this->uploadDir);

        $this->imageUploader = new ImageUploader($this->uploadDir);
    }

    protected function tearDown(): void
    {
        $this->filesystem->remove($this->uploadDir);
    }

    public function testUploadFailure(): void
    {
        $file = $this->createMock(UploadedFile::class);
        $file->method('move')->willThrowException(new \Exception('Upload failed'));

        $this->expectException(\Exception::class);

        $this->imageUploader->upload($file);
    }
}
