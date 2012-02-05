<?php
App::uses('AppHelper', 'View/Helper');

class QuestionHelper extends AppHelper {
	
	// TODO: Add support for attributes having "default" values configured in the helper
	
	private static $typeList = array(0 => 'Text',
									 1 => 'RadioButton',
									 2 => 'Checkbox',
									 3 => 'Dropdown',
									 4 => 'RankOrder',
									 5 => 'LikertScale',
									 6 => 'Informational'
	);
	
	protected $attributes = array();
		
	// Factory-level methods
	function types() {
		return self::$typeList;
	}
	
	function idToName($id)
	{
		return self::$typeList[$id];
	}
	
	function getHelper($type)
	{
		$helperName = self::$typeList[$type]."Question";
		
		$view = new View($this);
		$helper = $view->loadHelper($helperName);
		
		return $helper;
	}
    
	// Helper-level methods
    function getAttributes()
    {
    	return $this->attributes;
    }
    
    function getAttribute($attribute)	
    {    	     	
    	return $this->attributes[$attribute];    	
    }
}
?>