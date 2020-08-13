<?php
class controller {

	protected $db;
	protected $lang;

	public function __construct() {
		global $config;

		$this->lang = new Language(); // criando uma classe Language, que vai carregar a linguagem do site

	}

	public function loadView($viewName, $viewData = array()) {
		extract($viewData);
		include 'views/'.$viewName.'.php';
	}

	public function loadTemplate($viewName, $viewData = array()) { //No TEmplate o viewData não sofre extract
		include 'views/template.php';
	}

	public function loadViewInTemplate($viewName, $viewData) {
		extract($viewData);
		include 'views/'.$viewName.'.php';
	}

}