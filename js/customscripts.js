/**
* CustomScripts.js 1.0.0
* @author Template
**/
jQuery(document).ready(function () {
 
/* Button Style Script */
jQuery("#wp-submit").addClass("btn btn-default");
jQuery(".contact_file").addClass(" btn btn-md btn-default");
jQuery(".wpcf7-submit").addClass("pull-left float-left btn btn-md btn-default");
jQuery(".wpcf7-submit").attr("value", "Send Message");
if(jQuery('.wpcf7-file').length){
jQuery('.wpcf7-file').change(function(){
var parentdiv = jQuery(this).parent().closest(".form-group");
var value = this.value.replace( /C:\\fakepath\\/i, "" );
var divv = jQuery(parentdiv).find("span#upload-file");
jQuery(divv).text(value);
});
}
 
/* Account details page Button class Script */
jQuery('form.woocommerce-EditAccountForm .woocommerce-Button.button').removeClass("button").addClass('btn btn-default');
 
/* Slideshow Function Call */
if(jQuery('#ttr_slideshow_inner').length){
jQuery('#ttr_slideshow_inner').TTSlider({
stopTransition:false ,
slideShowSpeed:2000, begintime:1000,cssPrefix: 'ttr_'
});
}
/* Animation Function Call */
jQuery().TTAnimation({cssPrefix: 'ttr_'});
 
/* -----StaticFooterScript ----*/
var window_height =  Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
var body_height = jQuery(document.body).height();
var content = jQuery("div[id$='content_margin']");
if(body_height < window_height){
differ = (window_height - body_height);
content_height = content.height() + differ;
jQuery("div[id*='content_margin']").css({"min-height":content_height+"px"});
}
 
/* -----HamburgerScript ----*/
jQuery('#nav-expander').on('click',function(e){
e.preventDefault();
jQuery('body').toggleClass('nav-expanded');
});
 
/* -----MenuOpenClickScript ----*/
jQuery('ul.ttr_menu_items.nav li [data-toggle=dropdown]').on('click', function() {
var window_width =  Math.max(document.documentElement.clientWidth, window.innerWidth || 0)
if (('ontouchstart' in window) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0)) {
if(jQuery(this).parent().hasClass('show')){
location.assign(jQuery(this).attr('href'));
}
} else {
if(window_width > 991 && jQuery(this).attr('href')){
window.location.href = jQuery(this).attr('href'); 
}
else{
if(jQuery(this).parent().hasClass('show')){
location.assign(jQuery(this).attr('href'));
}
}
}
});
 
/* -----SidebarMenuOpenClickScript ----*/
jQuery('ul.ttr_vmenu_items.nav li [data-toggle=dropdown]').on('click', function() {
var window_width =  Math.max(document.documentElement.clientWidth, window.innerWidth || 0)
if(window_width > 991 && jQuery(this).attr('href')){
window.location.href = jQuery(this).attr('href'); 
}
else{
if(jQuery(this).parent().hasClass('show')){
location.assign(jQuery(this).attr('href'));
}
}
});
 
/* -----SidebarMenuOpenClickScript ForNoStyle----*/
jQuery('ul.menu li [data-toggle=dropdown]').on('click', function() {
	if(jQuery(this).parent().hasClass('show')){
	location.assign(jQuery(this).attr('href'));
	}
});
 
/*----- PageAlignment Script ------*/
var page_width = jQuery('#ttr_page').width();
var window_width =  Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
if(window_width > 991){
jQuery('.ttr_page_align_left').each(function() {
var left_div_width = jQuery(this).width(); 
var page_align_left_value = page_width - left_div_width;
left_div_width = left_div_width + 1;
jQuery(this).css({'left' : '-' + page_align_left_value + 'px', 'width': left_div_width + 'px'});
});
jQuery('.ttr_page_align_right').each(function() {
var right_div_width = jQuery(this).width(); 
var page_align_left_value = page_width - right_div_width;
right_div_width = right_div_width + 1;
jQuery(this).css({'right' : '-' + page_align_left_value + 'px', 'width': right_div_width + 'px'});
});
}
 
/* ---- TabClickScript ----*/
jQuery('.ttr_menu_items ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) { 
var window_width =  Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
if(window_width < 992){
event.preventDefault();
event.stopPropagation();
jQuery(this).parent().siblings().removeClass('show');
jQuery(this).parent().toggleClass(function() {
if (jQuery(this).is(".show") ) {
window.location.href = jQuery(this).children("[data-toggle=dropdown]").attr('href'); 
return "";
} else {
return "show";
}
});
}
});
 
/* ----- TabVMenuClickScript -----*/
jQuery('.ttr_vmenu_items ul [data-toggle=dropdown]').on('click', function(event) { 
var window_width =  Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
if(window_width < 992){
event.preventDefault();
event.stopPropagation();
jQuery(this).parent().siblings().removeClass('show');
jQuery(this).parent().toggleClass(function() {
if (jQuery(this).is(".show") ) {
window.location.href = jQuery(this).children("[data-toggle=dropdown]").attr('href'); 
return "";
} else {
return "show";
}
});
}
});
 
/* ----- TabVMenuClickScript ForNoStyle -----*/
jQuery('.menu ul [data-toggle=dropdown]').on('click', function(event) {
event.preventDefault();
event.stopPropagation();
jQuery(this).parent().siblings().removeClass('show');
jQuery(this).parent().toggleClass(function() {
if (jQuery(this).is(".show") ) {
window.location.href = jQuery(this).children("[data-toggle=dropdown]").attr('href'); 
return "";
} else {
return "show";
}
});
});
 
/* ----- GoogleWebFontScript -----*/
 
/*************** Html video script ***************/
var objects = ['iframe[src*="youtube.com"]','iframe[src*="youtu.be"]', 'object'];
for(var i = 0 ; i < objects.length ; i++){
if (jQuery(objects[i]).length > 0) {
jQuery(objects[i]).wrap( "<div class='embed-responsive embed-responsive-16by9'></div>" );
jQuery(objects[i]).addClass('embed-responsive-item');
}
}
});