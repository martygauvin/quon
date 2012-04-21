<div class="surveys index">
	<h2>
	<?php echo __('Metadata');?></h2>
	
	    <script type="text/javascript">
        $(function(){
            widgets.contentLoaded(function(){      // initialise widgets
                // this callback is called when the widgets has finished initialising
                // restore data
				$.getJSON('<?php echo $this->Html->url(array('controller' => 'surveys',
            		'action' => 'retrieveMetadata', $survey['Survey']['id'])); ?>', function(data) {
            		widgets.forms[0].restore(data);
            	});
            });
        })
        
        $(document).ready(function() {
	        var tabs = $( "#tabs" ).tabs();
			$( ".button" ).button();
		
        	$(".go-prev-tab").click(function() {
        		var selected = tabs.tabs("option", "selected");
        		var last = tabs.tabs("length");
        		if (selected == 0) {
            		tabs.tabs("select", last - 1);
        		} else {
            		tabs.tabs("select", selected - 1);
        		}
    		});

    		$(".go-next-tab").click(function() {
        		var selected = tabs.tabs("option", "selected");
        		var last = tabs.tabs("length");
        		if (selected == last - 1) {
            		tabs.tabs("select", 0);
        		} else {
            		tabs.tabs("select", selected+1);
        		}
    		});
		});
    </script>
    
    <div class="widget-form" style="padding:1em;"
            data-pre-save-func=""
            data-save-func=""
            data-save-url="<?php echo $this->Html->url(array('controller' => 'surveys',
            	'action' => 'saveMetadata', $survey['Survey']['id'])); ?>"
            data-submit-url="">
         <input type="hidden" class="form-fields" value="
         	name,
         	dob
         "/>
         <input type="hidden" class="form-fields-readonly" value="
         	formID
         "/>
         
         <div id="tabs">
         <ul>
         	<li><a href="#general-tab">General</a></li>
         	<li><a href="#coverage-tab">Coverage</a></li>
        	<li><a href="#description-tab">Description</a></li>
        	<li><a href="#people-tab">People</a></li>
        	<li><a href="#subject-tab">Subject</a></li>
        	<li><a href="#rights-tab">Rights</a></li>
        	<li><a href="#management-tab">Management</a></li>
        	<li><a href="#attach-tab">Attachments</a></li>
        	<li><a href="#notes-tab">Notes</a></li>
         	<li><a href="#submit-tab">Submit</a></li>
         </ul>
         <div id="general-tab"><?php include 'metadata/general.ctp' ?></div>
         <div id="coverage-tab"><?php include 'metadata/coverage.ctp' ?></div>
         <div id="description-tab"><?php include 'metadata/description.ctp' ?></div>
         <div id="people-tab"><?php include 'metadata/people.ctp' ?></div>
         <div id="subject-tab"><?php include 'metadata/subject.ctp' ?></div>
         <div id="rights-tab"><?php include 'metadata/rights.ctp' ?></div>
         <div id="management-tab"><?php include 'metadata/management.ctp' ?></div>
         <div id="attach-tab"><?php include 'metadata/attach.ctp' ?></div>
         <div id="notes-tab"><?php include 'metadata/notes.ctp' ?></div>
         <div id="submit-tab"><?php include 'metadata/submit.ctp' ?></div>
         </div>
         
         <div class="navigation">
        	<span class="go-prev-tab"><?php
            	echo $this->Html->image('arrow_left.png',
            		array('alt' => "Go to the previous section of the form",
            			'width' => '50', 'height' => '50'));
        	?></span>
        	<span class="go-next-tab"><?php
            	echo $this->Html->image('arrow_right.png',
            		array('alt' => "Go to the previous section of the form",
            			'width' => '50', 'height' => '50'));
        	?></span>
        	<span class="button form-fields-save">Save</span>
        	<span class="saved-result"></span>
    	</div>
    </div>
	
</div>
<div class="actions">
	<h3>

	<?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Return to Survey'), array('controller' => 'surveys', 'action' => 'edit', $survey['Survey']['id'])); ?> </li>
	</ul>
</div>
