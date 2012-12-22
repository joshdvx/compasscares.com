<?php get_header(); ?>

<section id="page">

	<?php get_template_part( 'loop', 'page' ); ?>
	
	<?php if(get_field('compass_leadership')): ?>
		<?php while (has_sub_field('compass_leadership')): ?>
		
			<div class="leader-photo roundy">
				<img src="<?php the_sub_field('leadership_headshot') ?>" width="155" height="155"><br>
			</div>
			
			<?php the_sub_field('leadership_name') ?><br>
			<?php the_sub_field('leader_position') ?><br>
			<div class="bio-intro">
				<?php the_sub_field('leadership_bio_1') ?>
			</div>
			<div class="full-bio">
				<?php the_sub_field('leadership_bio_2') ?>
				<button>Read More</button>
			</div>
			
		<?php endwhile; ?>
	<?php endif; ?>
	
</section><!-- #page -->

<?php get_footer(); ?>
