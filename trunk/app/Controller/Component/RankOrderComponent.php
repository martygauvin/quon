<?php
/**
 * A component to assist with rendering the RankOrder object type.
 * @package Controller.Component
 */
class RankOrderComponent extends PreviousAnswerComponent {
		function augment($attributes, $surveyObjectInstance, $surveyInstance, $surveyResult, $controller) {
			$positions = array(0, 1);
			return parent::augment($attributes, $surveyObjectInstance, $surveyInstance, $surveyResult, $controller, $positions);
		}
}
?>