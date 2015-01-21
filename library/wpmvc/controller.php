<?php

class wpmvc_controller
{
	protected $view = null;
	
	public function __construct()
	{
		$this->view = ImportYml_View::getInstance();
	}
}
