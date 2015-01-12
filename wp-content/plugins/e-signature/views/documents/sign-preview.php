<?php echo $data['message']; ?>

 <div align="center" class="esig_header_top"> <?php echo $data['document_logo']; ?></div>
  
<div class="document_id"><?php _e( 'Document ID:', 'esig' ); ?>  <?php echo $data['document_id']; ?></div>
<div class="document_date"><?php echo $data['document_date']; ?></div>
<div class="signed_on"><?php _e('Signed On :', 'esig'); ?>  <?php echo $data['blog_url']; ?></div>
<div class="document-sign-page">
    
	<p class="doc_title"><?php echo $data['document_title']; ?></p>
 
	<br />
	<?php echo $data['document_content']; ?>
</div>

<div class="signatures row">
	<form name="readonly" class="form-inline">
	
	<?php echo $data['recipient_signatures']; ?>

	<?php echo $data['owner_signature']; ?> 
	<input type="hidden" id="invite_hash" name="invite_hash" value="<?php echo $data['invite_hash']; ?>" />
	</form>
</div>

<div class="page-break-before row">
		<div class="esig-logo col-sm-8">
			<!--<img src="<?php // echo $data['assets_dir']; ?>/images/approveme-badge.svg" alt="WP E-Signature"/>-->
		</div>
		<div class="col-sm-4">
			<span class="esig-blogname"><?php echo $data['blog_name']; ?></span>
			<a href="<?php echo $data['blog_url']; ?>" class="esig-sitename" target="_blank"><?php echo $data['blog_url']; ?></a>
		</div>
	</div>

<div class="audit-wrapper">

	<?php echo $data['audit_report']; ?>
	
	<br/> <?php echo $data['auditsignatureid']; ?>

</div>

<body oncontextmenu="return false;"> 
