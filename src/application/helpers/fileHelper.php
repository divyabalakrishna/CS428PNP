<?php

/**
 * This class provides utility functions for files.
 */
class FileHelper {

	/**
	 * Upload a file into a specific directory in the server.
	 * This function will overwrite the file if the same file name already exists in the upload directory.
	 * @param string $fieldName Name for the HTML file upload tag.
	 * @param string $directory Directory name inside the upload folder where the file should be uploaded to.
	 * @param string $acceptedExtensions Comma separated list of accepted extensions (without the dot).
	 * @param string $errorMessageSubject Words to be appended into the error message "Unable to upload <...>" if the upload is unsuccessful.
	 * @param integer $maxSize Maximum file size limit in bytes. Use 0 if there is no limit.
	 * @param string $replaceFileName New file name to replace the original file name. Use empty string to keep the original file name.
	 * @param integer $index File upload index for multiple file upload. Use 0 for single file upload.
	 * @return object An object which contains the uploaded file name, error message, and success flag.
	 */
	public function uploadFile($fieldName, $directory, $acceptedExtensions, $errorMessageSubject, $maxSize = 0, $replaceFileName = "", $index = 0) {
		$fileName = "";
		$errorMessage = "Unable to upload " . $errorMessageSubject . " " . $_FILES[$fieldName]["name"][$index];
		$fileUploaded = false;

		if ($_FILES[$fieldName]["error"][$index] == 0 && $_FILES[$fieldName]["tmp_name"][$index] != "" && ($maxSize == 0 || $_FILES[$fieldName]["size"][$index] <= $maxSize)) {
			$fileExtension = strtolower(pathinfo($_FILES[$fieldName]["name"][$index], PATHINFO_EXTENSION));
			$acceptedExtensionArray = explode(",", strtolower($acceptedExtensions));

			if (in_array($fileExtension, $acceptedExtensionArray)) {
				$uploadDirectory = UPLOAD_ROOT . $directory . DIRECTORY_SEPARATOR;
				if (!file_exists($uploadDirectory)) {
					mkdir($uploadDirectory, 0755, true);
				}

				$fileName = $_FILES[$fieldName]["name"][$index];
				if (trim($replaceFileName) != "") {
					$fileName = $replaceFileName . "." . $fileExtension;
				}

				if (file_exists($uploadDirectory . $fileName)) {
					unlink($uploadDirectory . $fileName);
				}
				move_uploaded_file($_FILES[$fieldName]["tmp_name"][$index], $uploadDirectory . $fileName);

				$fileUploaded = true;
			}
			else {
				$errorMessage .= ": The uploaded file is not in the accepted file types." . $fileExtension;
			}
		}
		else if ($_FILES[$fieldName]["name"][$index] == "") {
			$errorMessage = "";
		}
		else if ($_FILES[$fieldName]["error"][$index] == UPLOAD_ERR_INI_SIZE || $_FILES[$fieldName]["error"][$index] == UPLOAD_ERR_FORM_SIZE || ($maxSize > 0 && $_FILES[$fieldName]["size"] > $maxSize)) {
			$errorMessage .= ": The uploaded file exceeds the max size limit.";
		}
		else if ($_FILES[$fieldName]["error"][$index] == UPLOAD_ERR_NO_FILE || $_FILES[$fieldName]["error"][$index] == 0) {
			$errorMessage = "";
		}
		else if ($_FILES[$fieldName]["error"][$index] != 0) {
			$errorMessage .= ".";
		}

		$result = new stdClass();
		$result->fileName = $fileName;
		$result->errorMessage = $errorMessage;
		$result->fileUploaded = $fileUploaded;

		return $result;
	}

	/**
	 * Delete a previously uploaded file from the server
	 * @param string $directory Directory name inside the upload folder where the file was uploaded to.
	 * @param string $fileName File name.
	 */
	public function deleteUploadedFile($directory, $fileName) {
		$uploadDirectory = UPLOAD_ROOT . $directory . DIRECTORY_SEPARATOR;

		if (file_exists($uploadDirectory . $fileName)) {
			unlink($uploadDirectory . $fileName);
		}
	}

	/**
	 * Get URL for an uploaded file.
	 * @param string $directory Directory name inside the upload folder where the file was uploaded to.
	 * @param string $fileName File name.
	 * @return string Uploaded file URL.
	 */
	public function getUploadedFileURL($directory, $fileName) {
		return UPLOAD_URL . $directory . "/" . $fileName;
	}

	/**
	 * Copy a previously uploaded file.
	 * @param string $directory Directory name inside the upload folder where the file was uploaded to.
	 * @param string $fileName File name.
	 * @param string $replaceFileName New file name to replace the original file name. Use empty string to follow the original file name.
	 * @return string New file name.
	 */
	public function copyUploadedFile($directory, $originalFileName, $replaceFileName = "") {
		$uploadDirectory = UPLOAD_ROOT . $directory . DIRECTORY_SEPARATOR;
		$extensionIndex = strpos($originalFileName, ".");
		$extension = $GLOBALS["beans"]->stringHelper->right($originalFileName, strlen($originalFileName) - $extensionIndex - 1);

		$newFileName = $replaceFileName;
		if ($newFileName == "") {
			$newFileName = $GLOBALS["beans"]->stringHelper->left($originalFileName, strlen($originalFileName) - $extensionIndex - 1);

			// Find a number to ensure unique file name
			$appendNumber = $GLOBALS["beans"]->stringHelper->right($newFileName, 1);
			if (strlen($newFileName) > 1 && is_numeric($appendNumber)) {
				$newFileName = $GLOBALS["beans"]->stringHelper->left($originalFileName, strlen($originalFileName) - 1);
			}
			else {
				$appendNumber = 0;
			}

			// Loop until the append number makes a unique file name
			while (file_exists($uploadDirectory . $newFileName . "." . $extension)) {
				$appendNumber = $appendNumber + 1;
				$newFileName = $newFileName . $appendNumber;
			}
		}
		$newFileName = $newFileName . "." . $extension;

		copy($uploadDirectory . $originalFileName, $uploadDirectory . $newFileName);

		return $newFileName;
	}

}
