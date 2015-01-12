	<?php 
	// To default a var, add it to an array
	$vars = array(
		'awaiting_class' // will default $data['awaiting_class']
	);
	$this->default_vals($data, $vars);
	
	include($this->rootDir . DS . 'partials/_tab-nav.php'); ?>

	<p>
	<a class="add-new-h2" href="admin.php?page=esign-view-document">Add New Document</a>
	</p>

	<?php echo $data['message']; ?>
	
	<?php echo $data['loop_head']; ?>
	
	<div class="header_left">
	<ul class="subsubsub">
		<!--<li class="all"><a class="<?php echo $data['all_class']; ?>" href="<?php echo $data['manage_all_url']; ?>" title="View all documents">Active Documents</a> <span class="count">(<?php echo $data['document_total']; ?>)</span> |</li>-->
		<li class="awaiting"><a class="<?php echo $data['awaiting_class']; ?>" href="<?php echo $data['manage_awaiting_url']; ?>" title="View documents currently awaiting signatures">Awaiting Signatures <span class="count">(<?php echo $data['total_awaiting']; ?>)</span></a> |</li>
		<li class="draft"><a class="<?php echo $data['draft_class']; ?>" href="<?php echo $data['manage_draft_url']; ?>" title="View documents in draft mode">Draft <span class="count">(<?php echo $data['total_draft']; ?>)</span></a> |</li>
		<li class="signed"><a class="<?php echo $data['signed_class']; ?>" href="<?php echo $data['manage_signed_url']; ?>" title="View signed documents">Signed <span class="count">(<?php echo $data['total_signed']; ?>)</span></a> |</li>
		<li class="trash"><a class="<?php echo $data['trash_class']; ?>" href="<?php echo $data['manage_trash_url']; ?>" title="View documents in trash">Trash <span class="count">(<?php echo $data['total_trash']; ?>)</span></a></li>
		<?php echo $data['document_filters']; ?>
		
	</ul>
	</div>

	<div class="header_right">
	<?php echo $data['esig_document_search_box']; ?>
	</div>
	
     <form name="esig_document_form" action="" method="post">
     
	<div class="esig-documents-list-wrap">
		<table cellspacing="0" class="wp-list-table widefat fixed esig-documents-list">
			<thead>
				<tr>
					<th class="check-column"><input name="selectall" type="checkbox" id="selectall" class="selectall" value=""></th>
					<th style="width: 245px;">Title</th>
					<th style="width: 145px;">Signer(s)</th>
					<th style="width: 160px;">Latest Activity</th>
					<th style="width: 100px;">Date</th>	
				</tr>
			</thead>
		
			<tfoot>
				<tr>
					<th class="check-column">
					<input name="selectall1" type="checkbox" id="selectall1" class="selectall" value=""></th>
					<th>Title</th>
					<th style="width: 145px;">Signer(s)</th>
					<th style="width: 160px;">Latest Activity</th>
					<th style="width: 100px;">Date</th>
				</tr>
			</tfoot>
			<tbody>