<?php
/**
 * A component to replace expressions which may involve previous answers.
 * @package Controller.Component
 */
class PreviousAnswerComponent extends Component {
	function augment($attributes, $surveyObjectInstance, $surveyInstance, $surveyResult, $controller, $positions = array(0)) {
		foreach ($attributes as &$attribute) {
			if (in_array($attribute['SurveyObjectAttribute']['name'], $positions)) {
				$attribute['SurveyObjectAttribute']['value'] =
					$this->replacePreviousValues($attribute['SurveyObjectAttribute']['value'], $surveyObjectInstance, $surveyInstance, $surveyResult, $controller);
			}
		}
		return $attributes;
	}
	
	protected function replacePreviousValues($attribute, $surveyObjectInstance, $surveyInstance, $surveyResult, $controller) {
		$expressionParser = $controller->Components->load('ExpressionParser');
		$regex = '/(\[!(?!!\]).*!\])/'; // match [!<expr>!]
		$splitText = preg_split($regex, $attribute, null, PREG_SPLIT_DELIM_CAPTURE);
		$text = '';
		foreach ($splitText as $split) {
			if (preg_match($regex, $split)) {
				$expression = substr($split, 2, -2); // remove [! and !]
				$value = $expressionParser->parse($expression, $controller, $surveyResult['SurveyResult']['id'], $surveyObjectInstance['SurveyInstanceObject']['id']);
				if ($value === FALSE) {
					$value = "error";
				}
				$text .= $value;
			} else {
				$text .= $split;
			}
		}
		return $text;
	}
}
?>