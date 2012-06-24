<div class="surveyObjectAttributes form">
<?php echo $this->Form->create('SurveyObjectAttribute');?>
	<fieldset>
		<legend><?php echo __('Edit Survey Object Attribute'); ?></legend>
		Survey Object: <?php echo $surveyObject['SurveyObject']['name'];?><br/><br/>
		<?php 
			$questionHelper = $this->Question->getHelper($surveyObject['SurveyObject']['type']);
			$attribute = $questionHelper->getAttribute($this->request->data['SurveyObjectAttribute']['name']);
		?>
		Attribute Name: <?php echo $attribute['name'];?><br/>
		Description: <?php echo $attribute['help'];?>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('survey_object_id');
		
		if (array_key_exists('type', $attribute) && $attribute['type'] == "html")
		{
			echo $this->Html->script('tiny_mce/tiny_mce.js');
			
			if ($imageManager && $imageManager['Configuration']['value'] == 'true')
			{
				echo "<script type='text/javascript'>
								tinyMCE.init({
			        				mode : 'textareas',
			        				theme : 'advanced',
			        				theme_advanced_disable: 'styleselect',
			        				theme_advanced_buttons1 : 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect,|,spellchecker',
							        theme_advanced_buttons2 : 'cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,insertimage,cleanup,help,code,|,preview,|,forecolor,backcolor',
							        theme_advanced_buttons3 : 'tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,fullscreen',
							        relative_urls : false,
			        				convert_urls : true,
        							plugins : 'imagemanager,autolink,lists,spellchecker,pagebreak,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template',
								});
							  </script>";
			}
			else
			{
				echo "<script type='text/javascript'>
								tinyMCE.init({
			        				mode : 'textareas',
			        				theme : 'advanced',
			        				theme_advanced_disable: 'styleselect',
			        				theme_advanced_buttons1 : 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect,|,spellchecker',
							        theme_advanced_buttons2 : 'cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,insertimage,cleanup,help,code,|,preview,|,forecolor,backcolor',
							        theme_advanced_buttons3 : 'tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,fullscreen',
							        plugins : 'autolink,lists,spellchecker,pagebreak,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template',
								});
							  </script>";				
			}
				
			echo $this->Form->input('value', array('rows' => 17));	
		}
		else
		{
			echo $this->Form->input('value');
		}
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Cancel'), array('controller' => 'survey_object_attributes', 'action' => 'index', $surveyObject['SurveyObject']['id'])); ?> </li>
	</ul>
</div>
