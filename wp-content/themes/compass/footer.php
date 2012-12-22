		<footer role="contentinfo">
			<div class="container">
				<div class="row">
					<div class="span3" id="col1">
						<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
					</div>
					<div class="span3" id="col2">
						<?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
					</div>
					<div class="span3" id="col3">
						<?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
					</div>
					<div class="span3" id="col4">
						<?php dynamic_sidebar( 'fourth-footer-widget-area' ); ?>
					</div>
					<div class="span3" id="col5">
						<ul class="unstyled">
							<h3>Contact</h3>
							<address>
								<?php the_field('corporate_street_address', 'option'); ?><br>
								<?php the_field('corporate_city_state_zip','option'); ?><br>
								<?php the_field('corporate_phone_number','option'); ?>
							</address>
						</ul>
						<a href="<?php the_field('facebook','option'); ?>" title="Be our fan on Facebook"><img src="<?php bloginfo('template_directory'); ?>/img/facebook.png" alt="Facebook" width="8" height="17"></a>
						<a href="<?php the_field('twitter','option'); ?>" title="Follow us on Twitter"><img src="<?php bloginfo('template_directory'); ?>/img/twitter.png" alt="Twitter" width="20" height="19"></a>
						<a href="http://maps.google.com/maps?saddr=&daddr=<?php the_field('corporate_street_address', 'option'); ?>,<?php the_field('corporate_city_state_zip','option'); ?>" title="Get directions to our corporate office"><img src="<?php bloginfo('template_directory'); ?>/img/directions.png" alt="Directions" width="20" height="20"></a>
					</div>
				</div>
				
				<div class="row">
					<!-- Begin MailChimp Signup Form -->

					<div id="mc_embed_signup" class="pull-right">
						<form action="http://compasscares.us6.list-manage.com/subscribe/post?u=405128a681aba7bee3fc7ca70&amp;id=7a9099056a" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
							<label for="mce-EMAIL">Stay up-to-date with all our FUN!</label>
							<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="Email Address" required>
							<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe">
						</form>
					</div>

					<!--End mc_embed_signup-->
				</div>
				<div style="clear:both;"></div>
			
				<div id="site-info" class="pull-right">
					&copy;<?php echo date ('Y'); ?><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<?php bloginfo( 'name' ); ?></a>
					 | <a href="http://twitter.github.com/bootstrap" target="_blank">Bootstrap Docs</a><!-- Remove for production -->
				</div><!-- #site-info -->
			</div>
		</footer>
	</div> <!-- #wrapper -->

	    <!-- Le javascript
	    ================================================== -->
	    <!-- Placed at the end of the document so the pages load faster -->
	    <script src="<?php bloginfo('template_directory'); ?>/js/bootstrap-transition.js"></script>
	    <script src="<?php bloginfo('template_directory'); ?>/js/bootstrap-alert.js"></script>
	    <script src="<?php bloginfo('template_directory'); ?>/js/bootstrap-modal.js"></script>
	    <script src="<?php bloginfo('template_directory'); ?>/js/bootstrap-dropdown.js"></script>
	    <script src="<?php bloginfo('template_directory'); ?>/js/bootstrap-scrollspy.js"></script>
	    <script src="<?php bloginfo('template_directory'); ?>/js/bootstrap-tab.js"></script>
	    <script src="<?php bloginfo('template_directory'); ?>/js/bootstrap-tooltip.js"></script>
	    <script src="<?php bloginfo('template_directory'); ?>/js/bootstrap-popover.js"></script>
	    <script src="<?php bloginfo('template_directory'); ?>/js/bootstrap-button.js"></script>
	    <script src="<?php bloginfo('template_directory'); ?>/js/bootstrap-collapse.js"></script>
	    <script src="<?php bloginfo('template_directory'); ?>/js/bootstrap-carousel.js"></script>
	    <script src="<?php bloginfo('template_directory'); ?>/js/bootstrap-typeahead.js"></script>
	    <script src="<?php bloginfo('template_directory'); ?>/js/slider.js"></script>
	    <script src="<?php bloginfo('template_directory'); ?>/js/scrollto.js"></script>
	    <script src="<?php bloginfo('template_directory'); ?>/js/localscroll.js"></script>
	    <script src="<?php bloginfo('template_directory'); ?>/js/serialscroll.js"></script>
	    <script src="<?php bloginfo('template_directory'); ?>/js/lettering.js"></script>

	    <!-- Remove these before deploying to production -->
		<script src="<?php bloginfo ('template_directory'); ?>/js/hashgrid.js" type="text/javascript"></script>
		<!-- scripts concatenated and minified via ant build script-->
		<script src="<?php bloginfo ('template_directory'); ?>/js/plugins.js"></script>
		<script src="<?php bloginfo ('template_directory'); ?>/js/script.js"></script>

		

	<?php wp_footer(); ?>
	</body>
</html>