    		</div><!-- .row -->
		</div> <!-- /container -->
		<footer role="contentinfo">
		<hr>
			<div class="container">
			
			<ul class="unstyled">
			<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>
			</ul>
			
			<?php dynamic_sidebar( 'second-footer-widget-area' ); ?>
			
			<?php dynamic_sidebar( 'third-footer-widget-area' ); ?>
			
			<?php dynamic_sidebar( 'fourth-footer-widget-area' ); ?>
			
			<ul class="unstyled">
				<h3>Contact</h3>
				<address>
					<?php the_field('corporate_street_address', 'option'); ?><br>
					<?php the_field('corporate_city_state_zip','option'); ?><br>
					<?php the_field('corporate_phone_number','option'); ?>
				</address>
				<a href="<?php the_field('facebook','option'); ?>"><img src="#" alt="">Facebook</a><br>
				<a href="<?php the_field('twitter','option'); ?>"><img src="#" alt="">Twitter</a><br>
				<a href="<?php the_field('twitter','option'); ?>"><img src="#" alt="">Directions</a><br>
			</ul>
			
			
				<div id="site-info" class="pull-right">
					&copy;<?php echo date ('Y'); ?><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<?php bloginfo( 'name' ); ?></a>
					 | <a href="http://twitter.github.com/bootstrap" target="_blank">Bootstrap Docs</a><!-- Remove for production -->
				</div><!-- #site-info -->
			</div>
		</footer>

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

	<!-- scripts concatenated and minified via ant build script-->
	<script src="<?php bloginfo ('template_directory'); ?>/js/plugins.js"></script>
	<script src="<?php bloginfo ('template_directory'); ?>/js/script.js"></script>

	<!-- Remove these before deploying to production -->
	<script src="<?php bloginfo ('template_directory'); ?>/js/hashgrid.js" type="text/javascript"></script>

<?php wp_footer(); ?>
	</body>
</html>