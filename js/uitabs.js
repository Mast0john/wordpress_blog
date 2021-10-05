/**
 * uitabs.js 1.0.0
 * Theme-options uses tabs. All the tabs and content of tabs are initailzed with this file.
 * @author Template
 * GPL Licensed
 */

jQuery(document).ready(function ($) {
    var sections = [];
	sections[pass_data.colors] = "colors";
	sections[pass_data.sidebar] = "sidebar";
	sections[pass_data.header] = "header";
	sections[pass_data.slideshow] = "slideshow";
    sections[pass_data.footer] = "footer";
    sections[pass_data.generaloptions] = "generaloptions";
    sections[pass_data.postcontent] = "postcontent";
    sections[pass_data.post] = "post";
    sections[pass_data.page] = "page";
    sections[pass_data.error] = "error";
    sections[pass_data.maintenance] = "maintenance";
    sections[pass_data.googlemap] = "googlemap";
	sections[pass_data.shortcodes] = "shortcodes";
	// sections[pass_data.contactus] = "contactus";
    /*sections[pass_data.seo] = "seo";
	sections[pass_data.seoenable] = "seoenable";
    sections[pass_data.seohome] = "seohome";
    sections[pass_data.seogeneral] = "seogeneral";
    sections[pass_data.seosocial] = "seosocial";
    sections[pass_data.seositemap] = "seositemap";
    sections[pass_data.seoadvanced] = "seoadvanced";*/
	sections[pass_data.backuppage] = "backuppage";
    sections[pass_data.backupsettings] = "backupsettings";
    sections[pass_data.backup] = "backup";
    sections[pass_data.restore] = "restore";
    sections[pass_data.export_import] = "export_import";
    sections[pass_data.import_content] = "import_content";
	
    
	/* SEO */
	/*var wrapped = $("#tabseo h4").wrap("<div class=\"ui-tabs-panel ui-widget-content ui-corner-bottom\">");
    wrapped.each(function () {
        $(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));
    });
    $("#tabseo .ui-tabs-panel").each(function (index) {
         var seo_tab = sections[$(this).children("h4").text()];
        $(this).attr("id", seo_tab);
		$(this).children("h4").css("display","none");	

    });*/
	
	/* Backup/Recovery */
	var wrapped = $("#tabbackup h4").wrap("<div class=\"ui-tabs-panel\">");
    wrapped.each(function () {
        $(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));
    });
    $("#tabbackup .ui-tabs-panel").each(function (index) {
         var backup_tab = sections[$(this).children("h4").text()];
        $(this).attr("id", backup_tab);
		$(this).children("h4").css("display","none");

    });
	
	var wrap_h2 = $(".wrap h2").length;	
	if(wrap_h2 > 0){
    var wrapped = $(".wrap h2").wrap("<div class=\"ui-tabs-panel\">");
	}
	else{
    var wrapped = $(".wrap h3").wrap("<div class=\"ui-tabs-panel\">");
    }
    wrapped.each(function () {
        $(this).parent().append($(this).parent().nextUntil("div.ui-tabs-panel"));
    });
    $(".ui-tabs-panel").each(function (index) {
       if(wrap_h2 > 0){
        var theme_tab = sections[$(this).children("h2").text()];
		}
		else{
        var theme_tab = sections[$(this).children("h3").text()];
        }
        $(this).attr("id", theme_tab);

    });
	
	
	/*$( "#tabseo" ).tabs({
        fx: { opacity: "toggle", duration: "fast" }
    });*/
	$( "#tabbackup" ).tabs({
        fx: { opacity: "toggle", duration: "fast" }
    });
    $(".ui-tabs").tabs({
        fx: { opacity: "toggle", duration: "fast" }
    });

    $("input[type=text], textarea").each(function () {
        if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
            $(this).css("color", "#999");
    });

    $("input[type=text], textarea").focus(function () {
        if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
            $(this).val("");
            $(this).css("color", "#000");
        }
    }).blur(function () {
        if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
            $(this).val($(this).attr("placeholder"));
            $(this).css("color", "#999");
        }
    });

	if(wrap_h2 > 0){
	$(".wrap h2, .wrap table").show();
	}
	else{
	$(".wrap h3, .wrap table").show();
	}


    // Browser compatibility
   /*if ($.browser.mozilla)
        $("form").attr("autocomplete", "off");*/


    // ColorPicker jquery
    var s = jQuery('.colorwell');
    $('.colorwell').wpColorPicker();
	
	/*
    var page_title = jQuery("#ttr_seo_page_title").parent().parent();
    var post_title = jQuery("#ttr_seo_post_title").parent().parent();
    var category_title = jQuery("#ttr_seo_category_title").parent().parent();
    var date_archive_title = jQuery("#ttr_seo_date_archive_title").parent().parent();
    var anchor_archive_title = jQuery("#ttr_seo_anchor_archive_title").parent().parent();
    var tag_title = jQuery("#ttr_seo_tag_title").parent().parent();
    var search_title = jQuery("#ttr_seo_search_title").parent().parent();
    var seo_404_title = jQuery("#ttr_seo_404_title").parent().parent();


    if (!jQuery("#ttr_seo_rewrite_titles").is(':checked')) {
        page_title.css("display", "none");
        post_title.css("display", "none");
        category_title.css("display", "none");
        date_archive_title.css("display", "none");
        anchor_archive_title.css("display", "none");
        tag_title.css("display", "none");
        search_title.css("display", "none");
        seo_404_title.css("display", "none");
    }
    jQuery("#ttr_seo_rewrite_titles").change(function () {
        if (!jQuery(this).is(':checked')) {
            page_title.css("display", "none");
            post_title.css("display", "none");
            category_title.css("display", "none");
            date_archive_title.css("display", "none");
            anchor_archive_title.css("display", "none");
            tag_title.css("display", "none");
            search_title.css("display", "none");
            seo_404_title.css("display", "none");

        }
        else {
            page_title.css("display", "");
            post_title.css("display", "");
            category_title.css("display", "");
            date_archive_title.css("display", "");
            anchor_archive_title.css("display", "");
            tag_title.css("display", "");
            search_title.css("display", "");
            seo_404_title.css("display", "");
        }
    });
	*/
    var automatic_interval = jQuery("#ttr_automatic_backup_interval").parents('tr');
    if (!jQuery("#ttr_automatic_backup_recovery_enable").is(':checked')) {
        automatic_interval.css("display", "none");
        jQuery("#ttrauto").css("display", "");
    }

    jQuery("#ttr_automatic_backup_recovery_enable").change(function () {
        if (!jQuery(this).is(':checked')) {

            automatic_interval.css("display", "none");
            jQuery("#ttrauto").css("display", "none");
        }
        else {

            automatic_interval.css("display", "");
            jQuery("#ttrauto").css("display", "");
        }
    });

    jQuery("#ttr_ftp_check_connection").click(function () {
        var serverAddress = jQuery("#ttr_ftp_server_address").val();
        var userName = jQuery("#ttr_ftp_user_name").val();
        var userPassword = jQuery("#ttr_ftp_user_password").val();
        jQuery.ajax({
            type: 'POST',
            url: myurl,
            data: {
                "action": "check_ftp_connection",
                "server_address": serverAddress, "user_name": userName, "user_password": userPassword
            },
            success: function (data) {
                alert(data);
            }
        });
    });

   /* jQuery("#ttr_update_contactus_form").click(function () {

        var data2 = jQuery('#myForm').serialize()
        jQuery.ajax({
            type: 'POST',
            url: myurl,
            data: data2 + '&action=update_contactus_form',
            success: function (data) {
                alert(data);
            }
        });
    });*/

      jQuery("input:checkbox[id='ttr_site_title_enable'],input:checkbox[id='ttr_site_slogan_enable']").on('change' ,function() {
subtogglebutton();
});

jQuery("input:checkbox[id='ttr_site_title_slogan_enable']").on('change' ,function() {
togglebutton();
});

$(window).on('load', function(){
if ($("input:checkbox[id='ttr_site_title_slogan_enable']").is(':not(:checked)')) {
	jQuery("input:checkbox[id='ttr_site_title_enable'],input:checkbox[id='ttr_site_slogan_enable']").attr({
		checked:false, 
		disabled:true
		}).parent().stop().animate({'left': '-50%'});
		$("input:checkbox[id='ttr_site_title_enable'],input:checkbox[id='ttr_site_slogan_enable']").closest('div.normal-toggle-button.toggle-button').addClass("disabledbutton");
	}
});

function togglebutton(){
	if ($("input:checkbox[id='ttr_site_title_slogan_enable']").is(':checked')) {
		jQuery("input:checkbox[id='ttr_site_title_enable'],input:checkbox[id='ttr_site_slogan_enable']").removeAttr('disabled');
		$("input:checkbox[id='ttr_site_title_enable'],input:checkbox[id='ttr_site_slogan_enable']").closest('div.normal-toggle-button.toggle-button').removeClass("disabledbutton");
		
		jQuery("input:checkbox[id='ttr_site_title_enable'],input:checkbox[id='ttr_site_slogan_enable']").attr({
		checked:true
		}).parent().stop().animate({'left': '0%'});
	} else {
		jQuery("input:checkbox[id='ttr_site_title_enable'],input:checkbox[id='ttr_site_slogan_enable']").attr({
		checked:false, 
		disabled:true
		}).parent().stop().animate({'left': '-50%'});
		$("input:checkbox[id='ttr_site_title_enable'],input:checkbox[id='ttr_site_slogan_enable']").closest('div.normal-toggle-button.toggle-button').addClass("disabledbutton");
	} 
}

function subtogglebutton(){
	
	if (($("input:checkbox[id='ttr_site_title_enable']").is(':not(:checked)')) && ($("input:checkbox[id='ttr_site_slogan_enable']").is(':not(:checked)'))) {
		jQuery("input:checkbox[id='ttr_site_title_slogan_enable']").attr({
		checked:false, 
		}).parent().stop().animate({'left': '-50%'});
		$("input:checkbox[id='ttr_site_title_slogan_enable']").closest('div.normal-toggle-button.toggle-button');
		
		jQuery("input:checkbox[id='ttr_site_title_enable'],input:checkbox[id='ttr_site_slogan_enable']").attr({ 
		disabled:true
		});
		$("input:checkbox[id='ttr_site_title_enable'],input:checkbox[id='ttr_site_slogan_enable']").closest('div.normal-toggle-button.toggle-button').addClass("disabledbutton");	
	}
}

});
	