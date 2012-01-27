<?php
App::uses('AppHelper', 'View/Helper');

class LikertScaleQuestionHelper extends AppHelper {	
    
	private static $attributes = array(0 => 'Question Text',
    								   1 => 'Left label',
    								   2 => 'Right label',
									   3 => 'Number of graduations'
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