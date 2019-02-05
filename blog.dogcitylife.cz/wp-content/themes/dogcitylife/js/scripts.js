jQuery(document).ready(function(){

	function copyToClipboard(element) {
	    var $temp = jQuery("<input>");
	    jQuery("body").append($temp);
	    $temp.val(jQuery(element).text()).select();
	    document.execCommand("copy");
	    $temp.remove();
	}

	jQuery(".footer .up").click(function(e){
		e.preventDefault();
		jQuery("html, body").animate({ scrollTop: 0 }, "slow");
  		return false;
	});

	jQuery(".ajax_load_more").click(function(e){
		e.preventDefault();

		if (jQuery(window).data('ajax_in_progress') === true)
	    	return;

	    var ajaxUrl = jQuery("#ajax_load_container").attr("data-next");
	    if(ajaxUrl){
	    	jQuery(window).data('ajax_in_progress', true); 
				jQuery.ajax({
				   type: "GET",
				   url: ajaxUrl,
				   success: function(response){
				   	var data =  jQuery(response).find("#ajax_load_container"); 
				   	jQuery("#ajax_load_container").attr("data-next", data.attr("data-next"));
				   	jQuery("#ajax_load_container").append(data.html());
				   	//jQuery(".ajax_loader").removeClass("show");
				   	jQuery(window).data('ajax_in_progress', false); 
				   	if(jQuery("#ajax_load_container").attr("data-next") == ""){
				   		jQuery(".ajax_load_more").hide();
				   	}
				   }
				 }); 
	    }
		
	});

	jQuery(".top_header .mobile_menu .hamburger").click(function(e){
		e.preventDefault();
		jQuery(this).next().stop(true,false).fadeToggle(300);
		jQuery(".top_header").toggleClass("borderless");
	});


	jQuery(".copy_link").click(function(e){
		e.preventDefault();
		copyToClipboard(jQuery(this));
	});

	jQuery(".top_header .user_header").hover(function(e){
		jQuery(this).children(".dropdown_menu").stop(true,false).fadeToggle(300);
	});

});