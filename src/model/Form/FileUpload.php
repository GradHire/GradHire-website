<?php

namespace app\src\model\Form;

class FileUpload
{
    private array $file;

    public function __construct(array $file)
    {
        $this->file = $file;
    }

    public function save(string $path, string $filename, bool $override = true): bool
    {
        if ($this->file['error'] === UPLOAD_ERR_OK) {
            $fileName = $this->file['name'];
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if ($extension === 'jpeg' || $extension === 'png')
                $extension = 'jpg';
            $path = trim($path, "/");
            $targetDirectory = "./$path/";
            if (!file_exists($targetDirectory))
                mkdir($targetDirectory, 0777, true);

            $targetPath = $targetDirectory . "$filename.$extension";

            if (!$override && file_exists($targetPath))
                return false;

            return move_uploaded_file($this->file['tmp_name'], $targetPath);
        }
        return false;
    }
}