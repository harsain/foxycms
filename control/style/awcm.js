$(function() {
	$("#content tr.table_item input").click(function() {
		return false;
	});
	
	// FaceBox
	$('a[rel*=facebox]').facebox();
	// /FaceBox
	
	$("#content tr.table_item").click(function() {
		var input = $(this).find("input[type=checkbox]#tobeselected");
		if(input.is(':checked')) {
			input.removeAttr("checked");
		} else {
			input.attr("checked","checked");
		}
		$(this).toggleClass("selected");
		
		if($("#content tr.table_item.selected").length > 0) {
			$("#multi_action").stop().slideDown(150).animate({backgroundColor: '#FFABAB'},200).animate({backgroundColor: 'white'},200);
		} else {
			$("#multi_action").slideUp(150);
		}
	});

	$("tr.table_head td:first-child").html("<input type=\"checkbox\" id=\"select_all\" />");
	$("input#select_all").click(function() {
		if($(this).is(":checked")) {
			$("#content tr.table_item input[type=checkbox]").attr("checked","checked");
			$("#content tr.table_item").addClass("selected");
		} else {
			$("#content tr.table_item input[type=checkbox]").removeAttr("checked");
			$("#content tr.table_item").removeClass("selected");
		}
		if($("#content tr.table_item.selected").length > 0) {
			$("#multi_action").stop().slideDown(150).animate({backgroundColor: '#FFABAB'},200).animate({backgroundColor: 'white'},200);
		} else {
			$("#multi_action").slideUp(150);
		}
	});
	
	
	$("form.validate").validate();
	//Side Menu
	$("#menu ul li a.main").click(function() {
		$(this).parent().find("ul").slideToggle("fast");
		var id = $(this).parent().attr("id");
		$.get("menu_session.php?REGISTER&id="+id);
		return false;
	});
	
	$("input[type=checkbox]").change(function() {
	});
	
	//Delete Confirmation
	$("a.button.delete").click(function() {
		var delete_link = $(this).attr("href");
		var dialog_buttons = {};
		var this_ele = $(this);
		dialog_buttons[delete_phrase] = function(){
			if($.get(delete_link+'&ajax')) {
				this_ele.parent().parent().addClass("deleted").fadeOut("fast");
				$(this).dialog( "close" );
			}
		}
		dialog_buttons[cancel_phrase] = function(){ $(this).dialog( "close" ); }
		$("#delete-confirm" ).dialog({ buttons: dialog_buttons, show: "drop",hide: "drop",modal: true });
		return false;
	});
	
	// multi delete
	$("a.multi_delete").click(function() {
		var dialog_buttons = {};
		var this_ele = $(this);
		dialog_buttons[delete_phrase] = function() {
			window.final_values = '';
			$("input:checkbox[id=tobeselected]:checked").each(function() {
				window.final_values = window.final_values+'|'+$(this).attr("value");
				$(this).parent().parent().removeClass("selected").addClass("deleted").fadeOut();
			});
			$.get(this_ele.attr("href")+"&ids="+window.final_values);
			$("#multi_action").hide("fast");
			$(this).dialog( "close" );
		}
		dialog_buttons[cancel_phrase] = function(){ $(this).dialog( "close" ); }
		$("#delete-confirm" ).dialog({ buttons: dialog_buttons, show: "drop",hide: "drop",modal: true  });
		return false;
	});
	// multi edit
	window.final_values = '';
	$("a.multi_edit").click(function() {
		$("input:checkbox[id=tobeselected]:checked").each(function() {
			window.final_values = window.final_values+'|'+$(this).attr("value");
		});
		$(this).attr("href",$(this).attr("href")+"&ids="+window.final_values);
	});
	
	/*
	$("a").click(function() {
		if(!$(this).hasClass("delete") && !$(this).hasClass("main")) {
			$("body").css("cursor","wait");
			$("body").load($(this).attr("href"));
			if($(this).attr("target") == '_blank') {
				window.open($(this).attr("href"));
				return false;
			}
			window.location="#"+$(this).attr("href");
			$("body").ready(function() {
				$("body").css("cursor","default");
			});
			return false;
		}
	});
	*/
	
	// repeat form
	$("a.repeat_form").click(function() {
		$("#tr_hr").show();
		$("form.to_clone tbody:first-child").clone().appendTo("form.to_clone table").hide().fadeIn();
		$("form.to_clone tbody:last-child input[type=text]").attr("value","");
	});
	
	// apply widgets
	$(".ui_datepicker").datepicker({"dateFormat":"dd/mm/yy","showAnim":"blind"});	
	
	// text fields title inside
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
    
    // toggle hrefs (enable , disable , ban, unban)
    $(".toggle_val").click(function() {
    	$.get($(this).attr("href")+"&ajax");
    	$(this).find("span").toggle();
    	$(this).fadeOut("fast").fadeIn("fast");
    	return false;
    });
    
    // Media Library
    $("input.images_library").val(images_library_phrase).attr("readonly","readonly");
    
    $("input.images_library").click(function() {
    	jQuery.facebox({ ajax: '?show=media_library&type=images&ajax' }, 'my-groovy-style'); 
    });
    


});

