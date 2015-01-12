<?php echo $data['message']; ?>

    <?php
    
     $logo_alignment= apply_filters('esig-logo-alignment','');
    
    ?>

 <div <?php echo $logo_alignment; ?>  class="esig_header_top"> <?php echo $data['document_logo']; ?></div>
 
<div class="document-sign-page">

	<p class="doc_title"><?php echo $data['document_title']; ?></p>
    <br />

	<form name="sign-form" id="sign-form" class="sign-form" method="post" action="<?php echo $data['action_url']; ?>">
		
		<?php echo $data['document_content']; ?>
		
		
		<div class="signatures row">
		
			<input type="hidden" id="invite_hash" name="invite_hash" value="<?php echo $data['invite_hash']; ?>" />
			<input type="hidden" name="checksum" value="<?php echo $data['checksum']; ?>" />

			<div class="col-sm-6">
				
					<p>
						<!--<label class="signers_label" for="first_name">Your legal name-->
						<input  type="text" required class="form-control"  name="recipient_first_name" value="<?php echo $data['recipient_first_name']; ?>"  <?php echo $data['extra_attr']; ?>   placeholder="Your legal name"/></label>
					</p>
				
					<?php echo $data['signer_sign_pad_before']; ?>
					
					<div class="signature-wrapper-displayonly recipient">
						<img src="<?php echo $data['ESIGN_ASSETS_URL']; ?>/images/sign-arrow.svg" class="sign-arrow" width="80px" height="70px"/>
						<canvas class="sign-here pad <?php echo $data['signature_classes']; ?>" width="425" height="100"></canvas>
						<input type="hidden" name="recipient_signature" class="output" value=''>
					</div>

					<div id="signer-signature" style="display:none">
						<div class="signature-wrapper">
							<span class="instructions"><?php _e('Draw signature with <strong>your mouse, tablet or smartphone</strong>', 'esig'); ?></span>
							<a href="#clear" class="clearButton" style="margin-bottom:25px;"><?php _e('Clear', 'esig'); ?></a>
							<canvas class="sign-here pad <?php echo $data['signature_classes']; ?>" width="425" height="100" ></canvas>
							<input type="hidden" name="output" class="output" value='<?php echo $data['output']; ?>'>
							
							<button class="wp-e-saveButton button saveButton" data-nonce="<?php echo $data['nonce']; ?>"><?php _e('Insert Signature', 'esig'); ?><span class="loader"></span></button>
						</div>
					</div>

			</div>
			
			<?php echo $data['signer_sign_pad_after']; ?>
		
			<?php echo $data['recipient_signatures']; ?>

			<?php echo $data['owner_signature']; ?>
			
			<span style="display:none;">
				<input type="submit" name="submit-signature" value="Submit signature" />
			</span>
		
		</div>
		
	</form>

	<div class="audit-wrapper">
		
		<div class="row page-break-before">
			<div class="esig-logo col-sm-8">
				<a href="http://www.approveme.me/wp-digital-e-signature/?ref=3" target="_blank"><img src="<?php echo $data['assets_dir']; ?>/images/legally-signed.svg" alt="WP E-Signature"/></a>
			</div>
			<div class="col-sm-4">
				<span><?php echo $data['blog_name']; ?></span>
				<a href="<?php echo $data['blog_url']; ?>" class="esig-sitename" target="_blank"><?php echo $data['blog_url']; ?></a>
			</div>
		</div>
		
		<?php echo $data['audit_report']; ?>
	
	</div>
</div>

<div id="agree-button-tip" style="display:none;">
	<div class="header">
		<span class="header-title"><?php _e('Agree &amp; Sign Below', 'esig'); ?></span>
	</div>
	<p>
		<?php _e('Click on "Agree &amp; Sign" to legally sign this document and agree to the WP E-Signature', 'esig'); ?> <a href="#" data-toggle="modal" data-target=".esig-terms-modal-lg" id="esig-terms" class="doc-terms"><?php _e('Terms of Use', 'esig'); ?></a>. 
		<?php _e('If you have questions about the contents of this document, you can email the', 'esig');  ?> <a href="mailto:<?php echo $data['owner_email']; ?>"><?php _e('document owner.', 'esig'); ?></a>
	</p>
</div>
<style type="text/css">
	.mobile-overlay-bg{position: fixed;top: 0;}
	.mobile-overlay-bg-black{background: black!important; margin: 0 !important; padding:0 !important;}
</style>
<div class="mobile-overlay-bg" style="display:none;">
	
<div class="overlay-content">
		<p class="overlay_logo"><img src="<?php echo $data['ESIGN_ASSETS_URL']; ?>/images/approveme-whitelogo.svg" width="120px" height="80px"/></p>
		<a class="closeButton"></a>
		
		<div class="overlay-content">
		<p align="center" class="doc_title"> <?php _e('Document Name:', 'esig'); ?> <?php echo $data['document_title']; ?></p>
		<p>
		<?php _e('Click on "Agree &amp; Sign" to legally sign this document and agree to the WP E-Signature', 'esig'); ?> <a href="http://beta.approveme.me/terms-of-use/" target="_blank" class="doc-terms"><?php _e('Terms of Use','esig'); ?></a>. 
		</p>
		<p>&nbsp;</p>
		<p align="center" id="esign_click_mobile_submit">
		<a href="#" class="agree-button" title="Agree and submit your signature."><?php _e('Agree & Sign', 'esig'); ?></a>
		</p>
		</div>
	</div>		
</div>

<!-- terms and condition start here -->
<div class="modal fade esig-terms-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Terms of Use</h4>
      </div>
      <div class="modal-body">
       <h1>Loading ........</h1>
      </div>
    </div>
  </div>
</div>
