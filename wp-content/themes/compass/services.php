<?php 
/*
* Template Name: Services
*/
get_header(); ?>

<section id="page">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<div class="heading">
			<h1 class="section-header">
				<div class="main-title"><?php the_title(); ?></div>
				<div class="subtitle"><?php the_field('subtitle') ?></div>
			</h1>
			<div class="heading-arrow"></div>
		</div>
	<?php endwhile; endif; ?>
	
	<article class="service-info">
		<?php if(get_field('service_deets')): ?>
			<?php while (has_sub_field('service_deets')): ?>
				<div class="info-block">
					<div class="info-block-details service-descriptions">
						<div class="inner-service-descriptions">
							<p><?php the_sub_field('description_block') ?></p>
						</div>
					</div>
					<img src="<?php the_sub_field('related_photo_block') ?>" alt="">
				</div>
			<?php endwhile; ?>
		<?php endif; ?>
	</article>
	
	<article class="pull-quote">
		<div class="client-testimony">
			<div class="testimony-headshot roundy">
				<img src="<?php the_field('pull_quote_headshot'); ?>" alt="<?php the_field('pull_quote_name'); ?>">
			</div>
			<div class="half-circle"></div>
			<div class="client-quote">
				<span class="client-quote-name">
					<strong><?php the_field('pull_quote_name'); ?></strong>
						<?php if(is_page('sls')): ?>
					 		- SLS Client
					 	<?php else: ?>
					 		- ILS Client
					 	<?php endif; ?>		
				</span>
				<p><?php the_field('pull_quote_quote'); ?></p>
			</div>
		</div>
		<?php if(is_page('sls')): ?>
			<a href="<?php bloginfo('url'); ?>/success-stories/sls">Read more SLS Success Stories like this one.</a>
		<?php else: ?>
			<a href="<?php bloginfo('url'); ?>/success-stories/ils">Read more ILS Success Stories like this one.</a>
		<?php endif; ?>
	</article>
	
	
</section><!-- #page -->

<?php get_footer(); ?>

