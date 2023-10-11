<?php

namespace app\src\model\Form;

class FormFile
{
	private string $name;
	private string $accept;

	public function __construct(string $name)
	{
		$this->name = $name;
		$this->accept = '';
	}

	public static function New(string $name): FormFile
	{
		return new FormFile($name);
	}

	public static function save(array $file, string $path, string $filename, bool $override = true): bool
	{
		if ($file['error'] === UPLOAD_ERR_OK) {
			$fileName = $file['name'];
			$extension = pathinfo($fileName, PATHINFO_EXTENSION);

			if ($extension === 'jpeg' || $extension === 'png') {
				$extension = 'jpg';
			}

			$path = trim($path, "/");
			$targetDirectory = "./$path/";

			// Check if the target directory exists, create it if it doesn't
			if (!file_exists($targetDirectory)) {
				mkdir($targetDirectory, 0777, true);
			}

			// Build the full path to the target file
			$targetPath = $targetDirectory . "$filename.$extension";

			// Check if the file already exists and overwrite is disabled
			if (!$override && file_exists($targetPath)) {
				echo "The file already exists, and overwrite is disabled.";
				return false;
			}

			// Move the temporary file to the target directory
			if (move_uploaded_file($file['tmp_name'], $targetPath)) {
				echo "The file was successfully uploaded to the $path directory.";
				return true;
			} else {
				echo "Error uploading the file.";
				return false;
			}
		} else {
			echo "No file was sent or an error occurred during upload.";
			return false;
		}
	}


	public function accept(string $accept): FormFile
	{
		$this->accept = $accept;
		return $this;
	}

	public function render(): void
	{
		echo '<input type="file" accept="' . $this->accept . '" name="' . $this->name . '" class="shadow-sm bg-zinc-50 border border-zinc-300 text-zinc-900 text-sm rounded-lg focus:ring-zinc-500 focus:border-zinc-500 block w-full p-2.5 dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-zinc-500 dark:focus:border-zinc-500 dark:shadow-sm-light %s">';
	}
}