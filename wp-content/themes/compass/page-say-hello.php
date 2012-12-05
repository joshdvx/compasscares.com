<?php get_header(); ?>

		<section id="page" class="span12">

			<?php get_template_part( 'loop', 'page' ); ?>
	
	<h1>Locations</h1>
	<?php if(get_field('offices')): ?>
		<?php while (has_sub_field('offices')): ?>
			<?php the_sub_field('office_city') ?><br>
			<?php the_sub_field('office_address') ?><br>
			<?php the_sub_field('office_phone') ?>
		<?php endwhile; ?>
	<?php endif; ?>
	
	<h1>Meet the Team</h1>
	<?php if(get_field('team_members')): ?>
		<?php while (has_sub_field('team_members')): ?>
			<img src="<?php the_sub_field('team_headshot') ?>" alt="<?php the_sub_field('team_name') ?>" width="100" height="100"><br>
			<?php the_sub_field('team_name') ?><br>
			<?php the_sub_field('team_position') ?><br>
			<a href="mailto:<?php the_sub_field('team_email') ?>"><?php the_sub_field('team_email') ?></a>
		<?php endwhile; ?>
	<?php endif; ?>
	
	<h1>Friends Who Can Help</h1>
	<?php if(get_field('friends_who_can_help')): ?>
		<?php while (has_sub_field('friends_who_can_help')): ?>
		<a href="<?php the_sub_field('friends_website_url') ?>"><?php the_sub_field('friends_name') ?></a><br>
			<?php the_sub_field('friends_phone_number') ?>
		<?php endwhile; ?>
	<?php endif; ?>
			
		</section><!-- #page -->

<?php get_footer(); ?>
