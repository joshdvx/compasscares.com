<div class="col-sm-6"  <?php if (array_key_exists('esig-tooltip', $data)) { echo $data['esig-tooltip']; } ?>>
	<div style="pointer-events: none;" class="signature-wrapper-displayonly" id="signature-<?php echo $data['user_id']; ?>">
		<canvas class="sign-here pad <?php echo $data['css_classes']; ?>" width="425" height="100"></canvas>
		<input class="output" type="hidden" name="<?php echo $data['input_name']; ?>" value='<?php echo $data['signature']; ?>'>
	</div>
	<div class="signature-meta">
		<p >
			<?php echo $data['by_line'] . ' ' . $data['user_name']; ?><br/>
			<?php echo $data['sign_date']; ?>
		</p>
	</div>
</div>

