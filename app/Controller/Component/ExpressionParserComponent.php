<?php
/**
 * A component to assist with parsing expressions, including replacing previous answers.
 * @package Controller.Component
 */
class ExpressionParserComponent extends Component {

	/**
	 * Parses the given expression.
	 * @param string $expression The expression to parse
	 * @param Controller $controller The controller to use
	 * @param int $survey_result_id The id of the SurveyResult to use
	 * @param in $survey_object_instance_id The id of the SurveyObjectInstance to use
	 */
	public function parse($expression, $controller,$survey_result_id, $survey_object_instance_id) {
		// allowed functions - all others will be stripped
		$allowed_functions = array ('abs', 'acos', 'acosh', 'asin', 'asinh', 'atan2', 'atan', 'atanh',
				'base_convert', 'bindec', 'ceil', 'cos', 'cosh', 'decbin', 'dechex', 'decoct', 'deg2rad', 'exp', 'expm1',
				'floor', 'fmod', 'getrandmax', 'hexdec', 'hypot', 'is_finite', 'is_infinite', 'is_nan', 'lcg_value',
				'log10', 'log1p', 'log', 'max', 'min', 'mt_getrandmax', 'mt_rand', 'mt_srand', 'octdec', 'pi', 'pow',
				'rad2deg', 'rand', 'round', 'sin', 'sinh', 'sqrt', 'srand', 'tan', 'tanh');
		$function_replacements = array();
		for ($count = 0; $count < count($allowed_functions); $count++)
		{
			$function_replacements[$count] = '!!'.$count.'!!';
		}
		
		$expr = $this->replace_question_values($expression, $controller, $survey_result_id, $survey_object_instance_id);
		$stringReplacements = array();
		$expr = $this->strip_strings($expr, $stringReplacements);
		$expr = $this->strip_functions($expr, $allowed_functions, $function_replacements);
		$expr = $this->strip_invalid($expr);
		$expr = $this->restore_functions($expr, $allowed_functions, $function_replacements);
		$expr = $this->restore_strings($expr, $stringReplacements);
		return eval('return '.$expr.';');
	}

	/**
	 * Replaces []-wrapped question names with the valud of the question's answer.
	 * @param string $expression The expression to replace question values in
	 * @param Controller controller The controller to use
	 * @param int $survey_result_id The id of the SurveyResult to use
	 * @param in $survey_object_instance_id The id of the SurveyObjectInstance to use
	 * @return An expression with correct question values
	 */
	private function replace_question_values($expression, $controller, $survey_result_id, $survey_object_instance_id) {
		$regex = "/(\[[^\]]*\])/"; // match [questionname{key}*]
		
		$expr = '';
		$splitText = preg_split($regex, $expression, null, PREG_SPLIT_DELIM_CAPTURE);
		foreach ($splitText as $split) {
			if (preg_match($regex, $split)) {
				$questionValue = $this->get_question_value($split, $controller, $survey_result_id, $survey_object_instance_id);
				if (!is_numeric($questionValue)) {
					$questionValue = '"'.$questionValue.'"'; // wrap strings in quotes
				}
				$expr = $expr.$questionValue;
			} else {
				$expr = $expr.$split;
			}
		}
		return $expr;
	}
	
	/**
	 * Gets the value for the given question.
	 */
	private function get_question_value($string, $controller, $survey_result_id, $survey_object_instance_id) {
		$surveyObjectInstance = $controller->SurveyInstanceObject->read(null, $survey_object_instance_id);
		$surveyObject = $controller->SurveyObject->read(null, $surveyObjectInstance['SurveyInstanceObject']['survey_object_id']);
		
		$questionName = substr($string, 1, -1); // remove surrounding []s
		$keyPos = strpos($questionName, '{');
		$keys = '';
		if ($keyPos) {
			$keys = substr($questionName, $keyPos); // get keys
			$questionName = substr($questionName, 0, $keyPos); // remove keys
		}
		// find answer
		$questionObject = $controller->SurveyObject->find('first',
				array('conditions' => array('SurveyObject.name' => $questionName,
						'SurveyObject.survey_id' => $surveyObject['Survey']['id'])));
		$questionObjectInstance = $controller->SurveyInstanceObject->find('first', array('conditions' => array('survey_object_id' => $questionObject['SurveyObject']['id'],
				'survey_instance_id' => $surveyObjectInstance['SurveyInstanceObject']['survey_instance_id'])));
		$result = $controller->SurveyResultAnswer->find('first', array('conditions' =>
				array('survey_instance_object_id' => $questionObjectInstance['SurveyInstanceObject']['id'],
						'survey_result_id' => $survey_result_id)));
		$questionObjectAttributes = $controller->SurveyObjectAttribute->find('all',
				array('order' => 'SurveyObjectAttribute.id', 'conditions' => array('survey_object_id' => $questionObject['SurveyObject']['id'])));
		
		if (!$result) {
			return '"""'; // something to guarantee an error
		}
		
		$answerValue = $result['SurveyResultAnswer']['answer'];
		
		// TODO: Broken MVC - find a better way to access a helper from a component
		$view = new View($controller);
		$questionFactory = $view->loadHelper('Question');
		$questionHelper = $questionFactory->getHelper($questionObject['SurveyObject']['type']);
		
		$answerValue = $questionHelper->deserialise($answerValue, $questionObjectAttributes);
		// TODO: The below if clause is horrible
		if (12 == $questionObject['SurveyObject']['type']) {
			$metaAnswer = array();
		
			foreach ($answerValue as $key=>$value)
			{
				$metaSurveyObject = $controller->SurveyObject->find('first', array('conditions' => array('SurveyObject.name' => $key, 'SurveyObject.survey_id' => $surveyObject['Survey']['id'])));
		
				$metaSurveyObjectAttributes = $controller->SurveyObjectAttribute->find('all',
						array('order' => array('SurveyObjectAttribute.id'), 'conditions' => array('survey_object_id' => $metaSurveyObject['SurveyObject']['id'])));
		
				$metaQuestionHelper = $questionFactory->getHelper($metaSurveyObject['SurveyObject']['type']);
		
				$metaQuestionAnswer = $metaQuestionHelper->deserialise($value, $metaSurveyObjectAttributes);
				$metaAnswer[$key] = $metaQuestionAnswer;
			}
		
			$answerValue = $metaAnswer;
		}
		while (is_array($answerValue)) {
			$key = 'value';
			$endKeyPos = strpos($keys, '}');
			if ($endKeyPos) {
				$key = substr($keys, 1, $endKeyPos - 1);
				$keys = substr($keys, $endKeyPos + 1);
			}
			if (array_key_exists($key, $answerValue)) {
				$answerValue = $answerValue[$key];
			} else {
				$answerValue = '"""'; // something to guarantee an error
			}
		}
		return $answerValue;
	}

	/**
	 * Strips the strings in the expression, allowing them to pass through the strip_invalid function
	 * and be restored later.
	 * @param string $expression The expression to strip strings from
	 * @return string The expression with strings stripped
	 */
	private function strip_strings($expression, &$replacements) {
		// if no strings, just return
		if (preg_match('/^[^"]*$/', $expression))
		{
			return $expression;
		}
		// replace strings with !num!, where num is the string reference number
		// store the string value in replacements so it can be restored later
		$expr = '';
		$count = 0;
		$splitText = preg_split('/("[^"]*")/', $expression, null, PREG_SPLIT_DELIM_CAPTURE);
		foreach ($splitText as $split)
		{
			if (preg_match('/^".*"$/', $split))
			{
				$replacements[$count] = substr($split, 1, -1); // remove quotes
				$expr .= '"!'.$count.'!"';
				$count++;
			} else
			{
				$expr .= str_replace('"', '', $split);
			}
		}
		return $expr;
	}

	/**
	 * Strips the functions in the expression, allowing them to pass through the strip_invalid function
	 * and be restored later.
	 * @param string $expression The expression to strip functions from
	 * @param array $allowed_functions The functions that are allowed
	 * @param array $function_replacements The replacements to make to keep the allowed functions
	 * @return string The expression with functions stripped
	 */
	private function strip_functions($expression, $allowed_functions, $function_replacements) {
		$expr = str_replace($allowed_functions, $function_replacements, $expression);
		return $expr;
	}

	/**
	 * Returns an expression equivalent to the given one with all invalid characters replaced with nothing.
	 * @param string $expression The expression with no invalid characters
	 */
	private function strip_invalid($expression) {
		$expr = preg_replace('/[^\d\s\+\-\*\/\.,\?\"()!:&|=><]/', '', $expression);
		// make equality better
		$expr = preg_replace('/\s*=+\s*/', ' == ', $expr);
		$expr = preg_replace('/< ==/', ' <=', $expr);
		$expr = preg_replace('/> ==/', ' >=', $expr);
		$expr = preg_replace('/! ==/', ' !=', $expr);
		$expr = preg_replace('/" == /', '" === ', $expr);
		$expr = preg_replace('/ == "/', ' === "', $expr);
		// replace string addition with concatenation
		$expr = preg_replace('/"\s*\+/', '" . ', $expr);
		$expr = preg_replace('/\+\s*"/', ' . "', $expr);
		return $expr;
	}

	/**
	 * Restores the functions in the expression.
	 * @param string $expression The expression to restore functions in
	 * @return string The expression with functions restored
	 * @param array $allowed_functions The functions that are allowed
	 * @param array $function_replacements The replacements that were made to keep the allowed functions
	 */
	private function restore_functions($expression, $allowed_functions, $function_replacements) {
		$expr = str_replace($function_replacements, $allowed_functions, $expression);
		return $expr;
	}

	/**
	 * Restores the strings in the expression.
	 * @param string $expression The expression to restore strings in
	 * @return string The expression with strings restored
	 */
	private function restore_strings($expression, &$replacements) {
		// replace !num! with the string from replacements[num]
		$expr = $expression;
		for ($count = 0; $count < count($replacements); $count++)
		{
			$expr = str_replace('!'.$count.'!', $replacements[$count], $expr);
		}
		return $expr;
	}
}
?>