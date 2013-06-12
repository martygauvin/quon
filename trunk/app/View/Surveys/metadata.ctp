<style type="text/css">
.lookup_button {
	margin: -20px 0 0 10px;
}
.float_textarea {
	float: left;
	width: 80%;
}
.hidden {
	display: none;
}
</style>
<script type="text/javascript">
//<![CDATA[
	$(document).ready(function() {
		$("#SurveyMetadataGrantDescriptionText").val($("#SurveyMetadataGrantDescription").val());
		$('#for-lookup').click(function() {
  			$( "#for-search" ).dialog({
  				buttons: {
  					"Cancel": function() { $(this).dialog("close"); }
  				},
  				modal: true,
  				title: "<?php echo __('Mint Lookup'); ?>",
  				width: 600
  			});
		});
		
		$('#for-search-button').click(function() {
			$('#for-search-results').html("Searching...");
			$.getJSON('<?php echo $this->Html->url(array('controller' => 'surveys', 'action' => 'searchFOR')); ?>', { query: $('#for-search-text').val()}, function(data) {
				$('#for-search-results').html('Search complete.<ul id="for-search-results-list"></ul>');
				$('#for-search-results-list').empty();
				var resultId = 0;
				$.each(data.results, function(key, val) {
					resultId++;
					var identifier = val["skos:prefLabel"];
					var code = identifier.substring(0, identifier.indexOf(' '));
					var item = '<li><a id="for' + resultId + '" href="#">' + identifier + '</a></li>';
					$('#for-search-results-list').append(item);
					
					$("#for" + resultId).click(function() {
						$("#SurveyMetadataFieldsOfResearch").val(code);
						$("#for-search").dialog("close");
						return false;
					});
				});
			});
		});
		
		$('#seo-lookup').click(function() {
  			$("#seo-search").dialog({
  				buttons: {
  					"Cancel": function() { $(this).dialog("close"); }
  				},
  				modal: true,
  				title: "<?php echo __('Mint Lookup'); ?>",
  				width: 600
  			});
		});
		
		$('#seo-search-button').click(function() {
			$('#seo-search-results').html("Searching...");
			$.getJSON('<?php echo $this->Html->url(array('controller' => 'surveys', 'action' => 'searchSEO')); ?>', { query: $('#seo-search-text').val()}, function(data) {
				$('#seo-search-results').html('Search complete.<ul id="seo-search-results-list"></ul>');
				$('#seo-search-results-list').empty();
				var resultId = 0;
				$.each(data.results, function(key, val) {
					resultId++;
					var identifier = val["skos:prefLabel"];
					var code = identifier.substring(0, identifier.indexOf(' '));
					var item = '<li><a id="seo' + resultId + '" href="#">' + identifier + '</a></li>';
					$('#seo-search-results-list').append(item);
					
					$("#seo" + resultId).click(function() {
						$("#SurveyMetadataSocio-economicObjective").val(code);
						$("#seo-search").dialog("close");
						return false;
					});
				});
			});
		});
		
		$('#grant-lookup').click(function() {
  			$( "#grant-search" ).dialog({
  				buttons: {
  					"Cancel": function() { $(this).dialog("close"); }
  				},
  				modal: true,
  				title: "<?php echo __('Mint Lookup'); ?>",
  				width: 600
  			});
		});
		
		$('#grant-search-button').click(function() {
			$('#grant-search-results').html("Searching...");
			$.getJSON('<?php echo $this->Html->url(array('controller' => 'surveys', 'action' => 'searchGrant')); ?>', { query: $('#grant-search-text').val()}, function(data) {
				$('#grant-search-results').html('Search complete.<ul id="grant-search-results-list"></ul>');
				$('#grant-search-results-list').empty();
				var resultId = 0;
				$.each(data.results, function(key, val) {
					resultId++;
					var grantIdentifier = val["dc:identifier"];
					var grantNumber = val["grant_number"];
					var repository = val["result-metadata"]["all"]["repository_name"];
					var title = val["dc:title"];
					var item = '<li><a id="grant' + resultId + '" href="#">' + grantNumber + ': (' + repository + ') ' + title + '</a></li>';
					$('#grant-search-results-list').append(item);
					
					$("#grant" + resultId).click(function() {
						$("#SurveyMetadataGrantIdentifier").val(grantIdentifier);
						$("#SurveyMetadataGrantDescription").val('(' + repository + ') ' + title);
						$("#SurveyMetadataGrantDescriptionText").val('(' + repository + ') ' + title);
						$("#grant-search").dialog("close");
						return false;
					});
				});
			});
		});
	});
//]]>
</script>


<div id="for-search" style="display: none">
<form action="">
	<fieldset>
		<input type="text" id="for-search-text" class="text ui-widget-content ui-corner-all" />
		<input type="button" id="for-search-button" value="Search"/>
	</fieldset>
	<h3>Results</h3>
	<div id="for-search-results">
		No results found.
	</div>
</form>
</div>

<div id="seo-search" style="display: none">
<form action="">
	<fieldset>
		<input type="text" id="seo-search-text" class="text ui-widget-content ui-corner-all" />
		<input type="button" id="seo-search-button" value="Search"/>
	</fieldset>
	<h3>Results</h3>
	<div id="seo-search-results">
		No results found.
	</div>
</form>
</div>

<div id="grant-search" style="display: none">
<form action="">
	<fieldset>
		<input type="text" id="grant-search-text" class="text ui-widget-content ui-corner-all" />
		<input type="button" id="grant-search-button" value="Search"/>
	</fieldset>
	<h3>Results</h3>
	<div id="grant-search-results">
		No results found.
	</div>
</form>
</div>

<div class="surveys form">
	<?php echo $this->Form->create('SurveyMetadata');?>
	<fieldset>
		<legend><?php echo __('Edit Survey Metadata'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('description');
		echo $this->Form->input('keywords');
		echo $this->Form->input('date_from', array('empty' => '-', 'minYear' => date('Y') - 100, 'maxYear' => date('Y') + 100));
		echo $this->Form->input('date_to', array('empty' => '-', 'minYear' => date('Y') - 100, 'maxYear' => date('Y') + 100));
		echo $this->Form->input('Location', array('label' => 'Locations'));
		echo $this->Form->input('fields_of_research', array('class' => 'float_textarea'));
		if ($lookupSupported) {
			echo $this->Form->button(__('Lookup'), array('type' => 'button', 'id' => 'for-lookup', 'class' => 'lookup_button'));
		}
		echo $this->Form->input('socio-economic_objective', array('class' => 'float_textarea'));
		if ($lookupSupported) {
			echo $this->Form->button(__('Lookup'), array('type' => 'button', 'id' => 'seo-lookup', 'class' => 'lookup_button'));
		}
		// hide the following two so they are still passed/stored
		echo $this->Form->input('grant_identifier', array('type' => 'hidden'));
		echo $this->Form->input('grant_description', array('type' => 'hidden'));
		if ($lookupSupported) {
			echo $this->Form->input('grant_description_text', array('label' => 'Grant', 'type' => 'text', 'class' => 'float_textarea', 'disabled' => true));
			echo $this->Form->button(__('Lookup'), array('type' => 'button', 'id' => 'grant-lookup', 'class' => 'lookup_button'));
		}
		echo $this->Form->input('retention_period');
		echo $this->Form->input('access_rights');
		echo $this->Form->input('User', array('label' => 'Users'));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>

</div>
<div class="actions">
	<h3>

	<?php echo __('Actions'); ?></h3>
	<?php if ($publishSupported) { ?>
		<ul>
		<li><?php echo $this->Form->postLink(__('Publish to ReDBox'), array('controller' => 'surveys', 'action' => 'publish', $survey['Survey']['id']), null, __('Are you sure you want to publish %s? Once published you cannot publish again. Please ensure all metadata is saved before continuing.', $survey['Survey']['name'])); ?></li>
		</ul>
	<br /><br />
	<?php } ?>
	<ul>
		<li><?php echo $this->Html->link(__('Return to Survey'), array('controller' => 'surveys', 'action' => 'edit', $survey['Survey']['id'])); ?> </li>
	</ul>
</div>
