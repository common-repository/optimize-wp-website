/* WP Optimize Website */
jQuery(document).ready(function(){
	    jQuery(".owpw-tab").hide();
		jQuery("#div-owpw-general").show();
	    jQuery(".owpw-tab-links").click(function(){
		var divid=jQuery(this).attr("id");
		jQuery(".owpw-tab-links").removeClass("active");
		jQuery(".owpw-tab").hide();
		jQuery("#"+divid).addClass("active");
		jQuery("#div-"+divid).fadeIn();
		});
});
