<?php
App::uses('AppHelper', 'View/Helper');

class DropdownQuestionHelper extends AppHelper {	
    
	private static $attributes = array(0 => 'Question Text',
    								   1 => 'Options',
									   2 => 'Include "Other" option'
	);
	
    function attributes() {
    	return self::$attributes;
    }
    
    function getAttributeName($id)
    {
    	return self::$attributes[$id];
    }
    
}
?>