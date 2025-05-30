<?php

namespace App\Core;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploader
{
    protected array $file;
    protected $image;
    protected string $uploadDir;
    protected int $maxFileSize;
    protected array $allowedExtensions;
    protected array $allowedMimeTypes;
    protected ?int $width = null;
    protected ?int $height = null;
    protected bool $keepAspectRatio = true;
    protected ?string $filename = null;
    protected ImageManager $manager;

    public function __construct()
    {
        // Configurações padrão
        $this->uploadDir = __DIR__ . '/../../storage/images/';
        $this->maxFileSize = 5 * 1024 * 1024; // 5 MB
        $this->allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $this->allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        $this->manager = new ImageManager(new Driver());
    }

    public function setFile(array $file): self
    {
        $this->file = $file;
        return $this;
    }

    public function setUploadDir(string $path): self
    {
        $this->uploadDir = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        return $this;
    }

    public function setMaxFileSize(int $bytes): self
    {
        $this->maxFileSize = $bytes;
        return $this;
    }

    public function setAllowedExtensions(array $extensions): self
    {
        $this->allowedExtensions = $extensions;
        return $this;
    }

    public function setAllowedMimeTypes(array $mimeTypes): self
    {
        $this->allowedMimeTypes = $mimeTypes;
        return $this;
    }

    public function setDimensions(int $width, int $height): self
    {
        $this->width = $width;
        $this->height = $height;
        return $this;
    }

    public function keepAspectRatio(bool $keep): self
    {
        $this->keepAspectRatio = $keep;
        return $this;
    }

    public function setFilename(string $name): self
    {
        $this->filename = $name;
        return $this;
    }

    public function unlink(string $cover): self
    {
        $image = public_path("images/{$cover}");

        if(file_exists($image))
        {
            unlink($image);
        }

        return $this;
    }

    public function upload(): array
    {
        if (!$this->file || $this->file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Erro no upload do arquivo.'];
        }

        if ($this->file['size'] > $this->maxFileSize) {
            return ['success' => false, 'message' => 'Arquivo excede o tamanho máximo permitido.'];
        }

        $extension = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowedExtensions)) {
            return ['success' => false, 'message' => 'Extensão de arquivo não permitida.'];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $this->file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            return ['success' => false, 'message' => 'Tipo de arquivo não permitido.'];
        }

        $hash = sha1_file($this->file['tmp_name']);
        $newFileName = $this->filename ?? ($hash . '.' . $extension);
        $destination = $this->uploadDir . $newFileName;

        $this->image = $this->manager->read($this->file['tmp_name']);

        if ($this->width && $this->height) {
            if ($this->keepAspectRatio) {
                // Redimensiona e corta para manter a proporção
                $this->image->cover($this->width, $this->height);
            } else {
                // Redimensiona sem manter a proporção
                $this->image->resize($this->width, $this->height);
            }
        }

        $this->image->save($destination);
        $size = filesize($destination);

        return [
            'success' => true,
            'message' => 'Arquivo enviado com sucesso.',
            'filename' => $newFileName,
            'path' => $destination,
            'extension' => $extension,
            'size' => $size,
        ];
    }
}