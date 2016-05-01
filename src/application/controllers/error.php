<?php

/**
 * This class acts as a controller for error module.
 */
class Error {

	/**
	 * Display an error message when a page is not found.
	 */
	public function index() {
		require APP . 'views/_templates/header.php';
		require APP . 'views/error/index.php';
		require APP . 'views/_templates/footer.php';
	}

}