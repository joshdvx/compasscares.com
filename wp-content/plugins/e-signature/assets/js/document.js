

(function($){
	
	var popup_standard_view_id = 'standard_view_popup'; //Id of the pop-up content
	var popup_edit ='standard_view_popup_edit';
	$('.invitations-container a').click(function(){
		$(".af-inner_edit input").removeAttr( "readonly");
		tb_show("", '#TB_inline?width=450&height=300&inlineId='+ popup_edit );
	});
	
	$('#basic_view').click(function(){
		$("#signer_logo").show();
		$("#signer_add").css("display","block");
		$("#signer_save").css("display","block");
		$(".af-inner input").removeAttr( "readonly");
		tb_show("", '#TB_inline?width=450&height=300&inlineId=' + popup_standard_view_id);
	});
	
	
	$('#submit_signer_save').click(function(){ 
	var esig_signer_fname='';
	var esig_signer_email='';
     esig_signer_fname = $("input[name='recipient_fnames\\[\\]']").map(function(){return $(this).val();}).get();		
	 esig_signer_email =$("input[name='recipient_emails\\[\\]']").map(function(){return $(this).val();}).get();
	var esig_document_id = $('input[name="document_id"]');
	
	//alert($("input[name='recipient_fnames_ajax[]']").val());
    jQuery.ajax({  
        type:"POST",  
        url: documentAjax.ajaxurl,   
        data: {
			recipient_fnames:esig_signer_fname,
			recipient_emails:esig_signer_email,
			document_id:esig_document_id.val(),
		},  
        success:function(data, status, jqXHR){    
            jQuery("#recipient_emails_ajax").html(data);
			//jQuery("#recipient_emails_ajax").html(data);
					tb_remove();
        },  
        error: function(xhr, status, error){  
            alert(xhr.responseText); 
        }  
    });  
    return false;  
	});
	
	$("#TB_closeWindowButton").on("click", function(e){
				alert('test sdfdf');
	});
	
	$("#addRecipient").on("click", function(e){
		e.preventDefault();
		
		$("#recipient_emails").append( '<div id="signer_main">' +
					    '<input type="text" name="recipient_fnames[]" placeholder="Signers Name"  />' +
					    '<input type="text" name="recipient_emails[]" placeholder="email@address.com" style="width:230px;"  value="" /><span id="esig-del-signer" class="deleteIcon"></span></div>').trigger("contentchange");
	});

		
				
	$("#addRecipient_view").on("click", function(e){
		e.preventDefault();
		
		$("#recipient_emails").append( '<div id="signer_main">' +
					    '<input type="text" name="recipient_fnames[]" placeholder="Signers Name"  />' +
					    '<input type="text" name="recipient_emails[]" placeholder="email@address.com" style="width:230px;"  value="" /><span id="esig-del-signer" class="deleteIcon"></span></div>').trigger("contentchange");
		
	});

     $('body').on('click', '#recipient_emails .deleteIcon', function () {

        // checking if signer only one then hide signer order checkbox 

        $(this).parent().remove();
  
		e.preventDefault();
		$(this).remove();
	});
	
	$("#esignadvanced").on("click", function(e){
		e.preventDefault();
		$("#esignadvanced").hide();
		$("#esignadvanced-hide").show();
		$("#advanced-settings").show();		
	});
	
	$("#esignadvanced-hide").on("click", function(e){
		e.preventDefault();
		$("#esignadvanced-hide").hide();
		$("#esignadvanced").show();
		$("#advanced-settings").hide();		
	});


	/* outside append field deled event */
	$('.minus-recipient').on('click', function(e){
		e.preventDefault();
		$(this).parent().remove();
	});
	// Bindings

	/*
	-- Validation --
	 Required fields:
	  - Title
	  - Document Content

	  if ( SEND ) :
	  	- At least one email invite
	*/


	// Bind the Submit type before submission
	var submit_type;
	$("#submit_send").on("click", function(){
		submit_type = 'send';
	});

	$("#submit_save").on("click", function(){
		submit_type = 'save';
	});


	// Bind the Submission
	$("#document_form").on("submit", function(e){

        //showing loader 
       
        document.getElementById('page-loader-admin').style.display='block';
		var overlay = $('<div class="page-loader-overlay"></div>').appendTo('body');
		$(overlay).show();
        
		//e.preventDefault();	
		var valid = true;

		if(this['document_title'].value == ""){
			valid = false;	
		}
		/** 
		Going to skip document content validation for now.. 
		theres a delay with ck editor in adding the content to the dom
		
		if($("#document_content").val() == ""){
			alert("content appears to be empty");
			var doc_content = document.getElementById("document_content").value;
			alert(doc_content);
		}
		*/

		// If sending validate that at least one recipient is present
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		var recips = [];
		$("#document_form input").each(function(index){
			
			if(/recipient/.test(this.name)){
				//alert("recipeints found with value of: " + this.value);
				if(this.value != ""){
					if(emailReg.test(this.value)){
						recips.push(this.value);
					}
				}
			}
		}); 

		if(recips.length < 1 && submit_type == 'send'){
			valid = false;
		}

		if(!valid){
			window.scrollTo(0,0);

			if($(".error").html() == undefined){
				var alertmsg = '<div class="error"><p><strong>Document Error</strong> : All setting fields are required</p></div>';
				$(this).prepend(alertmsg);
			}
			return false;
		}else{

			$("#document_action").val(submit_type);

			$(".submit").attr("disabled", true);
			return true;
		}
	});

	$(".cls_tr").on("hover", function(){
		$(this).find(".manage-options").toggle();
	});
	
	$("#advanced-settings").hide();	
	
	
	$(".esigactive").click(function() {
			$('.esigactiveinside').toggle(400);
			return false;
		});
		
	$(".urlredirect").click(function() {
			$('.urlredirectbody').toggle(400);
			return false;
		});	
    
	// error dialog popup 
	$( "#esig_show_alert" ).dialog({
	'dialogClass'   : 'wp-dialog esig-error-dialog',
	'title'         : 'Whoah there',
      modal: true,
      buttons: {
        Close: function() {
          $( this ).dialog( "close" );
        }
      }
    });


    // adding E-signature menu active when add document page . 
    if($('.toplevel_page_esign-docs').hasClass( "wp-not-current-submenu" )){
        $('.toplevel_page_esign-docs')
            .removeClass('wp-not-current-submenu')
            .addClass('wp-has-current-submenu')
            .find('li').has('a[href*="admin.php?page=esign-view-document"]')
            .addClass('current');
    }
		
})(jQuery);
