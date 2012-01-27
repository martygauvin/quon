<?php
App::uses('AppHelper', 'View/Helper');

class RadioButtonQuestionHelper extends AppHelper {	
    
	private static $attributes = array(0 => 'Question Text',
    								   1 => 'Options',
									   2 => 'Include "None of the above" as an option',
									   3 => 'Include "Other" option'
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