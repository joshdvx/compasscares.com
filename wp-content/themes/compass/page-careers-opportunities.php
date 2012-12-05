<?php get_header(); ?>

<section id="page" class="span12">
	<?php get_template_part( 'loop', 'page' ); ?>
	
	<h1>Career Success Stories</h1>
	<?php if(get_field('career_success_stories')): ?>
		<?php while (has_sub_field('career_success_stories')): ?>
			<img src="<?php the_sub_field('career_story_headshot') ?>" alt="<?php the_sub_field('career_story_name') ?>" width="100" height="100"><br>
			<?php the_sub_field('career_story_name') ?><br>
			<?php the_sub_field('career_success_story') ?><br>
		<?php endwhile; ?>
	<?php endif; ?>
	
	<h1>Training</h1>
	<?php if(get_field('training_resources')): ?>
		<?php while (has_sub_field('training_resources')): ?>
			<a href="<?php the_sub_field('training_file') ?>" title="<?php the_sub_field('training_title') ?>"><?php the_sub_field('training_title') ?></a><br>
		<?php endwhile; ?>
	<?php endif; ?>
</section><!-- #page -->

<?php get_footer(); ?>
