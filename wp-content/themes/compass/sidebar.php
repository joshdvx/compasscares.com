<section id="sidebar" class="span3">

	<ul class="unstyled">
		<li id="search" class="widget-container widget_search">
			<?php get_template_part('searchform'); ?>
		</li>
		<li class="rss"><a href="<?php bloginfo('rss_url') ?>"><img src="<?php bloginfo('template_directory'); ?>/img/rss.png" alt="RSS">Subscribe</a></li>
		<?php if ( ! dynamic_sidebar( 'primary-widget-area' ) ) : endif; ?>
	</ul>

</section><!-- #sidebar -->