// JavaScript Document

$.ajaxSetup({cache: false});


$(function() {
		
		
		
	$('input.hint, textarea.hint').hint();
	
	
	
	$('input.date').datepicker({
		dateFormat : 'yy-mm-dd',
		prevText : '&lt;'	,
		nextText : '&gt;'	,
		weekHeader : 'W',
		buttonImage: "/statics/img/icons/date.png",
		showOn: "both",
		changeYear: true,
		changeMonth: true,
		showOtherMonths: true,
		constrainInput: true
	});
	
});

/*
 *
 * Facebook Javascript SDK
 *
 */
window.fbAsyncInit = function() {
	FB.init({appId: FB_APPID, status: true, cookie: true, xfbml: true});
};