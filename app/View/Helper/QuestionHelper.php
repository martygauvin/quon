<?php
/**
 * QuestionHelper
 * @package View.Helper
 */
App::uses('AppHelper', 'View/Helper');

/**
 * A helper that determines the type of question that is being displayed and then uses the appropriate
 * QuestionHelper to display/validate/store the question. The QuestionHelpers used are named {$type}QuestionHelper,
 * where {$type} is any value in this class's $typeList array.
 */
class QuestionHelper extends AppHelper {
	/** The helpers used.*/
	var $helpers = array('Html');
	
	// TODO: Add support for attributes having "default" values configured in the helper

	/**
	 * The possible question types.
	 * Add new values to support new question types (and create appropriate helper)
	 */
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
	
	/** Attributes to be used.*/
	protected $attributes = array();
		
	// Factory-level methods
	/**
	 * Gets the types.
	 * @return array A list of possible types
	 */
	function types() {
		return self::$typeList;
	}
	
	/**
	 * Converts an id to a name
	 * @param int $id The id to convert
	 * @return multitype:string The name associated with the given id
	 */
	function idToName($id)
	{
		return self::$typeList[$id];
	}
	
	/**
	 * Get the helper for the given type
	 * @param int $type The id of the type to get the helper for
	 * @return Helper The helper associated with the given type
	 */
	function getHelper($type)
	{
		$helperName = self::$typeList[$type]."Question";
		
		$view = new View($this);
		$helper = $view->loadHelper($helperName);
		
		return $helper;
	}
    
	// Helper-level methods
	/**
	 * Gets the attributes
	 * @return multitype:string The attributes
	 */
    function getAttributes()
    {
    	return $this->attributes;
    }
    
    /**
     * Gets a particular attribute
     * @param int $attribute The id of the attribute to get
     * @return multitype:string The value of the requested attribute
     */
    function getAttribute($attribute)	
    {    	     	
    	return $this->attributes[$attribute];    	
    }
    
    /**
     * Flattens attributes.
     * @param unknown_type $attributes The attributes to flatten
     * @return multitype:unknown An array with the given attribtues
     */
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
    
    /**
     * Renders the question.
     * @param unknown_type $form The form to get values from
     * @param unknown_type $attributes The attributes to use
     * @param unknown_type $previousAnswer The previous answer, if available
     * @param unknown_type $show_next Whether to show the next question
     */
    function render($form, $attributes, $previousAnswer, &$show_next = false)
    {
    	$flat_attributes = $this->flatten_attributes($attributes);
    	
    	return $this->renderQuestion($form, $flat_attributes, $previousAnswer, &$show_next);
    }
    
    /**
     * Renders the question.
     * @param unknown_type $form The form to get values from
     * @param unknown_type $attributes The attributes to use
     * @param unknown_type $show_next Whether to show the next question
     */
    function renderQuestion($form, $attributes, &$show_next)
    {
    	return $this->renderQuestion($form, $attributes, null, true);
    }
    
    /**
     * Validates the given answer.
     * @param unknown_type $data The data to read the answer from
     * @param unknown_type $attributes The attributes of the question
     * @param unknown_type $error Any error caused
     * @return boolean true if answer is valid, false otherwise
     */
    function validate($data, $attributes, &$error)
    {
    	$flat_attributes = $this->flatten_attributes($attributes);
    	
    	return $this->validateAnswer($data, $flat_attributes, $error);
    }
    
    /**
     * Converts the given answer to a string.
     * @param unknown_type $data The given answer
     * @param unknown_type $attributes A string representing the given answer
     */
    function serialise($data, $attributes)
    {
    	$flat_attributes = $this->flatten_attributes($attributes);
    	 
    	return $this->serialiseAnswer($data, $flat_attributes);
    }
    
    /**
     * Validates the given answer.
     * @param unknown_type $data The answer to validate
     * @param unknown_type $attributes The attributes of the question
     * @param unknown_type $error Any error that is found
     * @return boolean true if answer is valid, false otherwise
     */
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