<?php
App::uses('AppHelper', 'View/Helper');

class QuestionHelper extends AppHelper {
	private static $typeList = array(0 => 'Text',
									 1 => 'RadioButton',
									 2 => 'Checkbox',
									 3 => 'Dropdown',
									 4 => 'RankOrder',
									 5 => 'LikertScale'
	);
	
	function types() {
		return self::$typeList;
	}
	
	function idToName($id)
	{
		return self::$typeList[$id];
	}
    
    function getAttributes($type)
    {
    	$helperName = self::$typeList[$type]."Question";
    	
    	$view = new View($this);
    	$helper = $view->loadHelper($helperName);

    	return $helper->attributes();
    }
    
    function getAttributeName($type, $attribute)	
    {
    	$helperName = self::$typeList[$type]."Question";
    	 
    	$view = new View($this);
    	$helper = $view->loadHelper($helperName);
    	
    	return $helper->getAttributeName($attribute);    	
    }
}
?>