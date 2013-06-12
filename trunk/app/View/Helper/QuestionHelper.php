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

	/** The types supported by the system.*/
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
									 10 => 'Calculation',
									 11 => 'DistributionOfPoints',
									 12 => 'Meta'
	);
	
	/** The attributes for the type.*/
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

    	$flat_attributes['id'] = $attributes[0]['SurveyObjectAttribute']['survey_object_id'];
    	
    	return $flat_attributes;
    }
    
    /**
     * Displays the question.
     * @param unknown_type $form The form to use
     * @param unknown_type $attributes The attributes to use
     * @param unknown_type $previousAnswer A stringified version of the previous answer
     * @param unknown_type $show_next Whether to show next
     */
    function render($form, $attributes, $previousAnswer = '', &$show_next = false)
    {
    	$flat_attributes = $this->flatten_attributes($attributes);
    	
    	return $this->renderQuestion($form, $flat_attributes, $previousAnswer, &$show_next);
    }
    
    /**
     * Converts form data to an array.
     * @param unknown_type $formData The form data to convert
     * @param unknown_type $attributes The attributes to use
     * @return An array containing the answer represented by the form
     */
    function convert($formData, $attributes)
    {
    	$flat_attributes = $this->flatten_attributes($attributes);
    	
    	$answer = $this->convertAnswer($formData, $flat_attributes);
    	if (!is_array($answer)) {
    		$answerArray = array();
    		$answerArray['value'] = $answer;
    		$answer = $answerArray;
    	}
    	
    	return $answer;
    }
    
    /**
     * Converts an answer array into a string.
     * @param unknown_type $data The answer to convert
     * @param unknown_type $attributes The attributes to use
     * @return string A string represeting the given answer
     */
    function serialise($data, $attributes)
    {
    	$flat_attributes = $this->flatten_attributes($attributes);
    
    	return $this->serialiseAnswer($data, $flat_attributes);
    }
    
    /**
     * Converts a string into an answer array.
     * @param unknown_type $data The string to convert
     * @param unknown_type $attributes The attributes to use
     * @return An array containing the answer represented in the string
     */
    function deserialise($data, $attributes)
    {
    	$flat_attributes = $this->flatten_attributes($attributes);
    	 
    	return $this->deserialiseAnswer($data, $flat_attributes);
    
    }
    
    /**
     * Validates the given answer.
     * @param unknown_type $data The answer to validate
     * @param unknown_type $attributes The attributes to use
     * @param unknown_type $error The error message to display
     * @return boolean Whether the answer is valid or not
     */
    function validate($data, $attributes, &$error)
    {
    	$flat_attributes = $this->flatten_attributes($attributes);
    	
    	return $this->validateAnswer($data, $flat_attributes, $error);
    }
    
    function convertAnswer($data, $attributes) {
    	// by default return empty string
    	return '';
    }
    
    function serialiseAnswer($data, $attibutes) {
    	// by default use PHP serialization
    	return serialize($data);
    }
    
    function deserialiseAnswer($data, $attributes)
    {
    	// by default use PHP deserialization
    	return unserialize($data);
    }
    
    function validateAnswer($data, $attributes, &$error)
    {
    	// by default assume valid
    	return true;
    }
    
    function validateConfig($object)
    {
    	$validate = array();
    	$validate['object'] = $object;
    	$validate['errors'] = array();
    	$validate['objects'] = array();
    	
    	return $validate;
    }
    
    public static function getKey($string) {
    	$key = $string;
    	$eqPos = strpos($key, '=');
    	if ($eqPos) {
    		$key = substr($key, 0, $eqPos);
    	}
    	$key = trim($key);
    	return $key;
    }
    
    public static function getValue($string) {
    	$value = $string;
    	$eqPos = strpos($value, '=');
    	if ($eqPos) {
    		$value = substr($value, $eqPos + 1);
    	}
    	$value = trim($value);
    	return $value;
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