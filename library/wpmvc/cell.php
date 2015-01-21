<?php
class wpmvc_cell
{
	public function __construct($name,$type,$size = null,$null = true,$defaultValue = "")
	{
		$this->name = $name;
		$this->type = $type;
		$this->size = $size;	
		$this->null = $null;
		$this->defaultValue = $defaultValue;
	}
	public $name;
	public $type;
	public $size;
	public $null;
	public $defaultValue;
}