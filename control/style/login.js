$(function() {
	$(".js_text").css("color","silver").focus(function() {
		if(!$(this).hasClass("origed")) {
			$(this).attr("title",$(this).attr("value"));
			$(this).attr("value","").css("color","black");
			$(this).addClass("origed");
		}
	});
	$(".js_text").blur(function() {
		if($(this).attr("value") == '') {
			$(this).attr("value",$(this).attr("title")).css("color","silver").removeClass("origed");
		}
	});
});