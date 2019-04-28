<?php

namespace core\view;

/**
 * This is the main rendering agent for template/view files. It handles
 * the integration of the parser with the template file, and should mainly
 * be used for its $view->render() function, which is pretty straightforward.
 */

class View {

	protected $folder;

	/**
	 * Let's keep a record of where the view files are kept
	 * @param string $folder
	 */
	public function __construct($folder) {
		$this->folder = $folder;
	}

	/**
	 * The main rendering function. Needs a template name relative to
	 * /app/views and without the file extenion (e.g. common/header
	 * will load /app/views/common/header.tpl.php)
	 *
	 * @param  string $template  e.g. checkout/payment
	 * @param  mixed $params   		array
	 * @return string - HTML content
	 */
	public function render($template, $params = array()) {

		if (!pathinfo($template, PATHINFO_EXTENSION)) {
			$template .= '.tpl.php';
		}

		$path = $this->folder . '/' . $template;

		$output = $this->includeView($path, $params);

		return $output;
	}

	/**
	 * Wrap the view in output bufferring and render it.
	 *
	 * @param  string $path
	 * @param  array $params
	 * @return string - HTML content
	 */
	private function includeView($path, $params) {

		ob_start();
		extract($params);
		include $path;

		return ob_get_clean();
	}
}
