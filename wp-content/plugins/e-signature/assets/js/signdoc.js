(function($){

	var popup_content_id = 'signer-signature'; //Id of the pop-up content
		
	var sender_input = $('input[name="sender_signature"]');
	sender_input = sender_input[0];
	var sender_sig = $(sender_input).val();
	
	// Sigpad Options
	var edit_opts = {
		drawOnly: true,
		validateFields : false,
		penColour: '#000000',
		lineWidth: '0',
		lineColour: 'rgba(255,255,255,0)',
		displayOnly:false, //useful for when re-signing
		bgColour : 'transparent'
	};
	
	var display_opts = {
		penColour: '#000000',
		displayOnly: true,
		bgColour : 'transparent',
	};
	
	// remove footer if visiting from mobile . 
	if(esigAjax.esig_mobile == '1'){
		$('#esig-footer').hide();
	}

	// If read-only form is present, the doc has been signed. Show signatures
	if(document.forms['readonly']){

		// Create sigpads and regeneerate
		$('.signature-wrapper-displayonly').each(function(i,e){
			var sigpad = $(e).signaturePad(display_opts);
			var input = $(e).find('input.output');
			if(input && $(input).val()){
				sig = $(input).val();
				sigpad.regenerate(sig);
			}
		});

	} else {
		$('.signature-wrapper-displayonly').each(function(i,e){
			var sigpad = $(e).signaturePad(display_opts);
			var input = $(e).find('input.output');
			if(input && $(input).val()){
				sig = $(input).val();
				sigpad.regenerate(sig);
			}
		});
		
		var recipient_input = $('input[name="recipient_signature"]');
		recipient_input = recipient_input[0];
		var sig = recipient_input.value;
		
		var signaturePadEdit = $('.signature-wrapper').signaturePad(edit_opts);
		
		var signatureDisplayRecipient = $('.signature-wrapper-displayonly.recipient').signaturePad(display_opts);
	
		if(sig != ""){
			if(signatureDisplayRecipient){
				signatureDisplayRecipient.regenerate(sig);
			}
			if(signaturePadEdit){
				signaturePadEdit.regenerate(sig);
			}
		}
	
		// Signature pop-up
		$('.signature-wrapper-displayonly').mousedown(function(){
			//if($('#sign-form').valid()){
			 validator.form();
		if(validator.numberOfInvalids() != 0){
			return ;
		}
				document.getElementById('page_loader').style.display='block';
				if(esigAjax.esig_mobile == '1'){
					tb_show("+ Add signature", '#TB_inline?width=320&height=220&inlineId=' + popup_content_id);
				}
				else {
				tb_show("+ Add signature", '#TB_inline?width=460&height=195&inlineId=' + popup_content_id);
				}
				
				document.getElementById('page_loader').style.display='none';				
			//}
		});
	
		// Signature inserted event
		var popup_input = $('.signature-wrapper input[name="output"]');
		$('.signature-wrapper .saveButton').click(function(){
			signatureDisplayRecipient.regenerate(popup_input.val());
			tb_remove();
		$('.signature-wrapper-displayonly .sign-here').removeClass('unsigned').addClass('signed');
		$('.signature-wrapper-displayonly .sign-here').addClass('sigvalid');
		if(esigAjax.esig_mobile == '1'){
			event.preventDefault(); // disable normal link function so that it doesn't refresh the page
			var docHeight = $(document).height(); //grab the height of the page
			var scrollTop = $(window).scrollTop(); //grab the px value from the top of the page to where you're scrolling
			// $('.mobile-overlay-bg').show();
			$('body').addClass('mobile-overlay-bg-black');
			$('.mobile-overlay-bg').show().css({'height' : docHeight}); //display your popup and set height to the page height
			//$('.overlay-content').css({'top': scrollTop+20+'px'}); //set the content 20px from the window top
			//$('#esig-agree-button').removeClass('disabled');
			}else {	
			// Send on change to validate form
			$('.signature-wrapper input[name="output"]').trigger('change');	
			}
		});
		
	}
	
	$('.closeButton').click(function(){
	    $('.mobile-overlay-bg').hide(); 
	    $('body').removeClass('mobile-overlay-bg-black');
	});
	
	var popup_invite = $('.signatures input[name="invite_hash"]');

	// Footer Ajax. Runs afer each page load for dynamic footer
	if(esigAjax.preview || (esigAjax.document_id && sig)){
		$.ajax({
			type: "get",
			dataType: 'html',
			url: esigAjax.ajaxurl,
			data: {
				action: "wp_e_signature_ajax",
				method: "get_footer_ajax",
				className: "WP_E_Shortcode",
				inviteCode: popup_invite.val(),
				url:esigAjax.ajaxurl ,
				preview: esigAjax.preview, 
				document_id: esigAjax.document_id,
			},
			success: function(data, status, jqXHR){
				$('#esig-footer').html(data);
			},
			error: function(jqXHR, status, error){
				console.log('signature ajax error:' + error)
			},
			beforeSend: function(){
				$("#esig-footer").addClass('loading');
			}
		});	
	}
	// mobile submit start here 
	$('#esign_click_mobile_submit').click(function(){
	
	$('#esign_click_submit').trigger('click');
	});
	// Agree button is disabled until document is signed
	$('#esign_click_submit').click(function(){
		
       
		   if(validator.numberOfInvalids() > 0)
           {
              return false ;
           }
       
		$('.mobile-overlay-bg').hide(); 
		document.getElementById('page_loader').style.display='block';
		var overlay = $('<div class="page_loader_overlay"></div>').appendTo('body');
		$(overlay).show();
		$('form[name="sign-form"]').submit();
		return false;
	});
	
	$('#esig-agree-button').addClass('disabled');


	var validator = $('#sign-form').validate({
		errorClass: 'esig-error',
		invalidHandler: function(event, validator){
			try{
				var first_error = validator.errorList[0].element;
				var tag = first_error.tagName;
				var field_name = first_error.getAttribute('name');
				
			    $('html, body').animate({
			        scrollTop: $(tag + '[name="'+field_name+'"]').offset().top - 20
			    }, 1500);
				
			} catch(err){
				console.log('invalidHandler Error' + err)
			}
		},
		errorPlacement: function(error, element) {
    if (element.attr('type')=="checkbox"){
				
				  error.insertAfter("#checkboxes");
				  
				}
		else if	(element.attr('type')=="radio"){
				  error.insertAfter("#radios");
				}	
				else {
      error.insertAfter(element);
    }	
				
				
  }
	});
	
	// Validate form when user has signed
	$('.signature-wrapper input[name="output"]', '#sign-form').change(function(){
		validator.form();
       
		if(validator.numberOfInvalids() == 0){
       
			$('#esig-print-button').remove();
			$('#esig-agree-button').removeClass('disabled').trigger('showtip');
           
			var fname = $("input[name='recipient_first_name']").val();
             var agreetext = $('.agree-text').html();
                 $('.agree-text').html('I am ' + fname + ' and ' + agreetext );
		}
	});
	
	// Eager validate after signed
	$('input[type="text"], select, checkbox', '#sign-form').change(function(){
		if($('.signature-wrapper-displayonly .sign-here').hasClass('sigvalid')){
			validator.form();
			if(validator.numberOfInvalids() == 0){
				$('#esig-print-button').remove();
				$('#esig-agree-button').removeClass('disabled').trigger('showtip');

                var fname = $("input[name='recipient_first_name']").val();
                 var agreetext = $('.agree-text').html();
                 $('.agree-text').html('I am ' + fname + ' and ' + agreetext );
				
			} else {
				$('#esig-agree-button').addClass('disabled').trigger('hidetip')
			}
		}
	});

	// Agree Button Tool Tip
	$.fn.tooltips = function(el) {

		var $tooltip,
			$body = $('body'),
			$el;

		return this.each(function(i, el) {

			$el = $(el).attr("data-tooltip", i);

			// Make DIV and append to page
			var content = $('#agree-button-tip').html();
			
			var $tooltip = $('<div class="tooltip" data-tooltip="' + i + '">' + 
				content + 
				'<div class="arrow"></div></div>'
			).appendTo(el);
			
			
			var overlay = $('<div class="esig-tooltip-overlay"></div>').appendTo('body');

			// Position right away, so first appearance is smooth
			var linkPosition = $el.offset();
			var topOffset = -2; // Offset the top position of the tip

			$tooltip.css({
				top: 0 - $tooltip.outerHeight() - topOffset ,
				left: linkPosition.left - ($el.width()/2)
			});

			$el.on('showtip', function() {

				$el = $(this);
			
				if($el.hasClass('disabled')){
					//return;
				}
			
				$tooltip = $('div[data-tooltip=' + $el.data('tooltip') + ']');

				// Reposition tooltip, in case of page movement e.g. screen resize
				var linkPosition = $el.offset();

				$tooltip.css({
					top: 0 - $tooltip.outerHeight() - topOffset ,
					left: linkPosition.left - ($el.width()/2)
				});

				// Adding class handles animation through CSS
				$tooltip.addClass("active");
				
				//$(overlay).show();

			});
			
			$el.on('hidetip', function() {
				$el = $(this);
				$tooltip = $('div[data-tooltip=' + $el.data('tooltip') + ']');
				$tooltip.removeClass('active').addClass('disabled');
			});
		});
		
	} // End Tool Tip

    // Click and show terms and condition 
    $('body').on('click', '.tooltip #esig-terms', function () {
            
       jQuery.ajax({  
        type:"POST",  
        url: esigAjax.ajaxurl+"?action=esig_terms_condition",    
        success:function(data, status, jqXHR){  
           
            $('.esig-terms-modal-lg .modal-body').html(data);
        },  
        error: function(xhr, status, error){  
           $('.esig-terms-modal-lg .modal-body').html('<h1>No internet connection</h1>');
        }
        });  

	});
    // click terms of service . 
  $('body').on('click', '#esig-terms', function () {
        
      jQuery.ajax({  
        type:"POST",  
        url: esigAjax.ajaxurl+"?action=esig_terms_condition",    
        success:function(data, status, jqXHR){  
           
            $('.esig-terms-modal-lg .modal-body').html(data);
        },  
        error: function(xhr, status, error){  
           $('.esig-terms-modal-lg .modal-body').html('<h1>No internet connection</h1>');
        }
        }); 

	});

    

})(jQuery);

jQuery(".esig-template-page .agree-button").tooltips();
