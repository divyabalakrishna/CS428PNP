<?php

class FileHelper
{

	/* This function will OVERWRITE the file if the same file name exists in the $directory.
	 * 
	 * $directory: which sub-directory the file should be uploaded to inside the uploads folder.
	 * $replaceFileName: file name will be replaced with this string. To keep original file name, use empty string.
	 * $maxSize: maximum file size limit in bytes. If there is no limit, use 0.
	 * $acceptedExtensions: comma separated list of accepted extensions (without the dot).
	 * $errorMessageSubject: to be appended into the error message "Unable to upload <subject>" if upload is unsuccessful.
	 */
	public function uploadFile($fieldName, $directory, $acceptedExtensions, $errorMessageSubject, $maxSize = 0, $replaceFileName = "")
	{
		$fileName = "";
		$errorMessage = "Unable to upload " . $errorMessageSubject;
		$fileUploaded = false;

		if ($_FILES[$fieldName]["error"] == 0 && $_FILES[$fieldName]["tmp_name"] != "" && ($maxSize == 0 || $_FILES[$fieldName]["size"] <= $maxSize)) {
			$fileExtension = strtolower(pathinfo($_FILES[$fieldName]["name"], PATHINFO_EXTENSION));
			$acceptedExtensionArray = explode(",", strtolower($acceptedExtensions));

			if (in_array($fileExtension, $acceptedExtensionArray)) {
				$uploadDirectory = UPLOAD_ROOT . $directory . DIRECTORY_SEPARATOR;
				if (!file_exists($uploadDirectory)) {
					mkdir($uploadDirectory, 0755, true);
				}

				$fileName = $_FILES[$fieldName]["name"];
				if (trim($replaceFileName) != "") {
					$fileName = $replaceFileName . "." . $fileExtension;
				}

				if (file_exists($uploadDirectory . $fileName)) {
					unlink($uploadDirectory . $fileName);
				}
				move_uploaded_file($_FILES[$fieldName]["tmp_name"], $uploadDirectory . $fileName);

				$fileUploaded = true;
			}
			else {
				$errorMessage .= ": The uploaded file is not in the accepted file types.";
			}
		}
		else if ($_FILES[$fieldName]["error"] == UPLOAD_ERR_INI_SIZE || $_FILES[$fieldName]["error"] == UPLOAD_ERR_FORM_SIZE || ($maxSize > 0 && $_FILES[$fieldName]["size"] > $maxSize)) {
			$errorMessage .= ": The uploaded file exceeds the max size limit.";
		}
		else if ($_FILES[$fieldName]["error"] == UPLOAD_ERR_NO_FILE || $_FILES[$fieldName]["error"] == 0) {
			$errorMessage = "";
		}
		else if ($_FILES[$fieldName]["error"] != 0) {
			$errorMessage .= ".";
		}

		$result = new stdClass();
		$result->fileName = $fileName;
		$result->errorMessage = $errorMessage;
		$result->fileUploaded = $fileUploaded;

		return $result;
	}

	public function deleteUploadedFile($directory, $fileName) {
		$uploadDirectory = UPLOAD_ROOT . $directory . DIRECTORY_SEPARATOR;

		if (file_exists($uploadDirectory . $fileName)) {
			unlink($uploadDirectory . $fileName);
		}
	}

	public function getUploadedFileURL($directory, $fileName) {
		return UPLOAD_URL . $directory . "/" . $fileName;
	}

}