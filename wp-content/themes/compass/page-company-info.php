<?php get_header(); ?>

<section id="page" class="span12">

	<?php get_template_part( 'loop', 'page' ); ?>
	
	<?php if(get_field('compass_leadership')): ?>
		<?php while (has_sub_field('compass_leadership')): ?>
			<img src="<?php the_sub_field('leadership_headshot') ?>" width="100" height="100"><br>
			<?php the_sub_field('leadership_name') ?><br>
			<?php the_sub_field('leader_position') ?><br>
			<div class="leader-bio">
				<?php the_sub_field('leadership_bio') ?>
				<!-- <a href="#">Read More</a> -->
			</div>
			
		<?php endwhile; ?>
	<?php endif; ?>
	
</section><!-- #page -->

<?php get_footer(); ?>
