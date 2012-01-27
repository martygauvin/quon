<?php
App::uses('AppHelper', 'View/Helper');

class RankOrderQuestionHelper extends AppHelper {	
    
	private static $attributes = array(0 => 'Question Text',
    								   1 => 'Options',
    								   2 => 'Maximum number of options to be ranked',
									   3 => 'Include "None of the above" as an option',
									   4 => 'Include "Other" option'
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