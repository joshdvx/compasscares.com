(function($){

	$("#esig_active_campaign").click(function() {
		
    jQuery.ajax({  
        type:"GET",  
        url: esig_active_campaign_ajax_script.ajaxurl,   
        data:{
				esigdocid: esig_active_campaign_ajax_script.esigdocid,
			},  
        success:function(data, status, jqXHR){    
            jQuery("#esig_active_campaign").html(data);  
        },  
        error: function(xhr, status, error){  
            alert(xhr.responseText); 
        }  
    });  

  });

})(jQuery);

