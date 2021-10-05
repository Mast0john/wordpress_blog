/**
 * tt_editor.js 1.0.0
 * TT edidor uses this file to initailze all the setting value 
 * @author Template
 * GPL Licensed
 */

	var test;
		
			jQuery(document).ready(function ($) {
				
			$('#myGrid').gridEditor({
					 new_row_layouts: [[12], [6,6], [4,4,4], [3,3,3,3], [8,4], [4,8]],	
					
				});
			
    
           $('#tt_editor_tabs.nav-tabs li a[data-toggle="tab"]').on('click', function(e) {
           	
          var answer=confirm('Switching tabs will lose your content. Please either use default editor or custom editor.');

		    if (answer === true)
		     {
		     	var id, mode;
				var target = e.target.hash;
		     	$(this).tab('show');
		     	if(target == '#tt_editor_tab2' )
					{
						id = "content";
						mode = 'html';
						switchEditors.go(id,mode); 
					}
				else if(target == '#tt_editor_tab1'){
						id = "content"; 
						mode = 'tmce';
						switchEditors.go(id,mode); 	
					}
		     }
		     else 
		     {
		     return false;
		     }
		     
    	   window.localStorage.setItem('activeTab', $(e.target).attr('href'));
       
          });
           
           var activeTab = window.localStorage.getItem('activeTab');
    
    
    	    if (activeTab)
    	     {
        		$('#tt_editor_tabs.nav-tabs li a[href="' + activeTab + '"]').tab('show');
    		 }
    		 
    		jQuery("#contextual-help-wrap").removeClass("hidden").addClass("tt_hide");
    		jQuery("#screen-options-wrap").removeClass("hidden").addClass("tt_hide");
});