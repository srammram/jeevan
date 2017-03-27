$(function() {
	$(".navbar-expand-toggle").click(function() {
		$(".app-container").toggleClass("expanded");
		return $(".navbar-expand-toggle").toggleClass("fa-rotate-90");
	});
	return $(".navbar-right-expand-toggle").click(function() {
		$(".navbar-right").toggleClass("expanded");
		return $(".navbar-right-expand-toggle").toggleClass("fa-rotate-90");
	});
});

$(function() {
	return $('select').select2({
		minimumResultsForSearch: Infinity
	});

});

$(function() {
	return $('.toggle-checkbox').bootstrapSwitch({
		size : "small"
	});
});

$(function() {
	return $('.match-height').matchHeight();
});

/*******************************************************************************
 * * removed tadatable - devteam $(function() { return
 * $('.datatable').DataTable({ "dom": '<"top"fl<"clear">>rt<"bottom"ip<"clear">>'
 * }); });
 ******************************************************************************/

$(function() {
	return $(".side-menu .nav .dropdown").on('show.bs.collapse', function() {
		return $(".side-menu .nav .dropdown .collapse").collapse('hide');
	});
});

/* Common form validation and for submit - start */
var loading_icon = get_loading_icon('form_submit col-sm-offset-2 col-sm-4 ');
$("#settings_form").validate({
	// errorElement: "span",
	// errorClass:"field_err",
	ignore : "",
	
});
$("#common_form").validate({
	// errorElement: "span",
	// errorClass:"field_err",
	ignore : "",

	submitHandler : function() {
		$(".alert_msg").hide();
		$(".btn_submit_div").hide();
		$(".btn_submit_div").before(loading_icon);

		$("#common_form").ajaxSubmit({
			type : "POST",
			dataType : "json",
			url :  admin_url + module + "/" + module_action,
			data : $("#common_form").serialize(),
			cache : false,
			success : function(data) {
				response = data;
				$(".btn_submit_div").show();
				$(".form_submit").remove();

				if (response.status == "success") {

					window.location.href = admin_url + module;
				} else if (response.status == "error") {
					$(".alert_msg").show().html(data.message);
					$('.side-body').scrollView();
					
				}

			}
		});

	}
});

/* Common form validation and for submi - end */

/* hide error on chnage the select box... */
$(document).ready(function() {
	$('select').change(function() {
		$(this).parents('.col-sm-4').find('.error').hide();
	});
});

/* get loading icon */
function get_loading_icon(class_name) {
	return loding_img = "<div class=\""
			+ class_name
			+ " loading\"><img src=\""
			+ lod_lib
			+ "theme/images/loading_icon_default.gif\" class=\"\" alt=\"Loading...\" /> </div>";

}

/*show  alert message*/
function showerror(errorclas,errormessage)
{ 
    $(".common_shower").remove();
    $('.side-body').prepend('<div class="all_notice '+errorclas+'">'+errormessage+'</div>').slideDown(500);
	$('.common_shower').delay(7000).slideUp(function(){$(this).remove();});
	 
}



/*scrool to top */
$.fn.scrollView = function () { 
	  return this.each(function () {
	    $('html, body').animate({
	      scrollTop: $(this).offset().top
	    }, 1000);
	  });
	}

function get_menu_types($this){
	var menu_type = $this.value;
	if(menu_type == "page"){
		$('#cmspage_list #cms_page').addClass('required');	
		$("#custom_link #custom_url").removeClass('required');	
		$("#menu_name input[name=name]").attr('value',' ');	
		$("#menu_name .required_star").hide();
		$("#menu_name input[name=name]").removeClass('required');	
		$("#cmspage_list").show();	
		$("#custom_link").hide();
		$("#parent_menu_sec").show();
	}
	else if(menu_type == "custom_link"){		
		$('#cmspage_list #cms_page').removeClass('required');
		$("#custom_link #custom_url").addClass('required');		
		$("#menu_name .required_star").show();
		$("#menu_name input[name=name]").addClass('required');	
		$("#custom_link").show();
		$("#cmspage_list").hide();
		$("#parent_menu_sec").show();
	}else{
		$('#cmspage_list #cms_page').removeClass('required');
		$("#cmspage_list").hide();
		$("#custom_link").hide();
		$("#parent_menu_sec").hide();
	}
	
} 