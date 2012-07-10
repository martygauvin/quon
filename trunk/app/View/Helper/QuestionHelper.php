<?php
App::uses('AppHelper', 'View/Helper');

/**
 * A helper that determines the type of question that is being displayed and then uses the appropriate
 * QuestionHelper to display/validate/store the question. The QuestionHelpers used are named {$type}QuestionHelper,
 * where {$type} is any value in this class's $typeList array.
 */
class QuestionHelper extends AppHelper {
	var $helpers = array('Html');
	
	// TODO: Add support for attributes having "default" values configured in the helper

	private static $typeList = array(0 => 'Text',
									 1  => 'RadioButton',
									 2  => 'Checkbox',
									 3  => 'Dropdown',
									 4  => 'RankOrder',
									 5  => 'LikertScale',
									 6  => 'Informational',
									 7  => 'Calendar',
									 8  => 'Branch',
									 9  => 'ButtonOption',
									 10 => 'Calculation'
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
    
    function flatten_attributes($attributes)
    {
    	$flat_attributes = array();
    	 
    	foreach ($attributes as $attribute)
    	{
    		$name = $attribute['SurveyObjectAttribute']['name'];
    		$value = $attribute['SurveyObjectAttribute']['value'];
    		$flat_attributes[$name] = $value;
    	}
    	
    	return $flat_attributes;
    }
    
    function render($form, $attributes, $previousAnswer, &$show_next = false)
    {
    	$flat_attributes = $this->flatten_attributes($attributes);
    	
    	return $this->renderQuestion($form, $flat_attributes, $previousAnswer, &$show_next);
    }
    
    function renderQuestion($form, $attributes, &$show_next)
    {
    	return $this->renderQuestion($form, $attributes, true);
    }
    
    function validate($data, $attributes, &$error)
    {
    	$flat_attributes = $this->flatten_attributes($attributes);
    	
    	return $this->validateAnswer($data, $flat_attributes, $error);
    }
    
    function serialise($data, $attributes)
    {
    	$flat_attributes = $this->flatten_attributes($attributes);
    	 
    	return $this->serialiseAnswer($data, $flat_attributes);
    }
    
    function validateAnswer($data, $attributes, &$error)
    {
    	return true;
    }
    
    /**
     * Escapes a string so it doesn't contain any pipes (|).
     * @param string $string The string to escape
     * @return The escaped string
     */
    public static function escapeString($string) {
    	$tildeEscaped = str_replace('~', '~t', $string);
    	return str_replace('|', '~p', $tildeEscaped);
    }

    /**
     * Unescapes a string so it may contain pipes (|).
     * @param string $string The string to unescape
     * @return The unescaped string
     */
    public static function unescapeString($string) {
    	$pipeRestored = str_replace('~p', '|', $string);
    	return str_replace('~t', '~', $pipeRestored);
    }
}
?>