
	jQuery(document).ready(function ($) {

$("#publish").click(function (event) {
			  	 	
			  	var currentTab = jQuery("ul#tt_editor_tabs").find('li.active a').attr("href");
				if(currentTab == '#tt_editor_tab1')
		    	{
		    		var canvasContent = $('textarea#content').content();
				}
				else
				{
			         if($('#myGrid').is(':visible'))
					{
						var canvasContent = $('#myGrid').gridEditor('getHtml');
					}
			     	else{
						var canvasContent = $('textarea.ge-html-output').val();
					}
				}
			     $('<textarea>').attr({
						    type: 'hidden',
						    id: 'content',
						    name: 'content',
						    class: 'wp-editor-area',
						    value: canvasContent,
						}).appendTo('#post');
						
					
			        return;
			    });
			    
			    $('#post-preview').on( 'click.post-preview', function( event ) {
			    	
				var currentTab = window.localStorage.getItem('activeTab');
				if(currentTab == '#tt_editor_tab2')
				{
			        if($('#myGrid').is(':visible'))
					{
						var canvasContent = $('#myGrid').gridEditor('getHtml');
					}
			     	else{
						var canvasContent = $('textarea.ge-html-output').val();
					}
			         
				    if($('#wp-content-wrap').hasClass('html-active')){ // We are in text mode
				        $('textarea#content').val(canvasContent); // Update the textarea's content
				    } else { // We are in tinyMCE mode
				        var activeEditor = tinyMCE.get('content');
				        if(activeEditor!==null){ // Make sure we're not calling setContent on null
				            activeEditor.setContent(canvasContent); // Update tinyMCE's content
				        }
				    } 
				   	     		
			   		       
			        return;    
				}
				});

});			
