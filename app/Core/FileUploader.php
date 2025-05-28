<?php

namespace App\Core;

class FileUploader
{
    protected array $file;
    protected string $uploadDir;
    protected int $maxFileSize;
    protected array $allowedExtensions;
    protected array $allowedMimeTypes;
    protected ?string $filename = null;

    public function __construct()
    {
        // Configurações padrão
        $this->uploadDir = __DIR__ . '/../../storage/files/';
        $this->maxFileSize = 5 * 1024 * 1024; // 5 MB
        $this->allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt'];
        $this->allowedMimeTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain'
        ];

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
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

    public function setFilename(string $name): self
    {
        $this->filename = $name;
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

        if (!move_uploaded_file($this->file['tmp_name'], $destination)) {
            return ['success' => false, 'message' => 'Falha ao mover o arquivo para o diretório de destino.'];
        }

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