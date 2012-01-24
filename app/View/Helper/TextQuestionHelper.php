<?php
App::uses('AppHelper', 'View/Helper');

class TextQuestionHelper extends AppHelper {	
    
	private static $attributes = array(0 => 'Question Text',
    								   1 => 'Answer Length');
	
    function attributes() {
    	return self::$attributes;
    }
    
    function getAttributeName($id)
    {
    	return self::$attributes[$id];
    }
    
}
?>