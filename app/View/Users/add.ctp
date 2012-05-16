<div class="users form">
<script type="text/javascript">
//<![CDATA[
	$(document).ready(function() {
		$('#mint-lookup').click(function() {
  			$( "#mint-search" ).dialog({
  				buttons: {
  					"Cancel": function() { $(this).dialog("close"); }
  				},
  				modal: true,
  				title: "<?php echo __('Mint Lookup'); ?>",
  				width: 600
  			});
		});
		
		$('#mint-search-button').click(function() {
			$('#mint-search-results').html("Searching...");
			$.getJSON('http://localhost/quon/users/search', { query: $('#mint-search-text').val()}, function(data) {
				$('#mint-search-results').html('Search complete.<ul id="mint-search-results-list"></ul>');
				$.each(data.results, function(key, val) {
					var identifier = val["dc:identifier"];
					var description = val["dc:description"];
					var firstName = val["result-metadata"]["all"]["Given_Name"];
					var surName = val["result-metadata"]["all"]["Family_Name"];
					var email = val["result-metadata"]["all"]["Email"];
					var resultId = "mint-search-result" + key;
					var detailId = resultId + "-detail";
					var item = '<li><a id="' + resultId + '" href="#">' + firstName + ' ' + surName + '</a> <a id="' + detailId + '" href="#">Details</a></li>';
					$('#mint-search-results-list').append(item);
					
					$("#" + resultId).click(function() {
						$("#UserExternalIdentifier").val(identifier);
						$("#UserGivenName").val(firstName);
						$("#UserSurname").val(surName);
						$("#UserEmail").val(email);
						$( "#mint-search" ).dialog("close");
					});
					
					$("#" + detailId).click(function() {
						$("#mint-search-details-identifier").val(identifier);
						$("#mint-search-details-first-name").val(firstName);
						$("#mint-search-details-surname").val(surName);
						$("#mint-search-details-email").val(email);
						$("#mint-search-details-description").val(description);
						$("#mint-search-details").dialog({
							buttons: {
  								"Cancel": function() { $(this).dialog("close"); }
  							},
  							title: "<?php echo __('Description'); ?>",
  							width: 600
						});
					});
				});
			});
		});
	});
//]]>
</script>

<div id="mint-search" style="display: none">
<form action="">
	<fieldset>
		<input type="text" id="mint-search-text" class="text ui-widget-content ui-corner-all" />
		<input type="button" id="mint-search-button" value="Search"/>
	</fieldset>
	<h3>Results</h3>
	<div id="mint-search-results">
		No results found.
	</div>
</form>
</div>

<div id="mint-search-details" style="display: none">
	<form action="">
		<fieldset>
			<label for="mint-search-details-identifier">Identifier</label>
			<input type="text" readonly="readonly" id="mint-search-details-identifier"/>
			<label for="mint-search-details-first-name">First name</label>
			<input type="text" readonly="readonly" id="mint-search-details-first-name"/>
			<label for="mint-search-details-surname">Surname</label>
			<input type="text" readonly="readonly" id="mint-search-details-surname"/>
			<label for="mint-search-details-email">Email</label>
			<input type="text" readonly="readonly" id="mint-search-details-email"/>
			<label for="mint-search-details-description">Description</label>
			<textarea id="mint-search-details-description" readonly="readonly" rows="10" cols="50"></textarea>
		</fieldset>
	</form>
</div>

<?php echo $this->Form->create('User');?>
	<fieldset>
		<legend><?php echo __('Add User'); ?></legend>
	<?php
		if ($lookupSupported) {
			echo $this->Form->button(__('Lookup'), array('type' => 'button', 'id' => 'mint-lookup'));
		}
		echo $this->Form->input('type', array(
		    'options' => array(User::type_researcher => 'Researcher', User::type_admin => 'Administrator')
		));
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->input('given_name');
		echo $this->Form->input('surname');
		echo $this->Form->input('email');
		if ($lookupSupported) {
			echo $this->Form->input('external_identifier', array('readonly' => true));
		}
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('controller' => 'users', 'action' => 'index')); ?> </li>
	</ul>
</div>
