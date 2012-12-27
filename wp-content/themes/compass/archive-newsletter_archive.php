<?php get_header(); ?>

<section id="page">

	<div class="heading">
		<h1 class="section-header">
			Newsletter Archive
		</h1>
		<div class="heading-arrow"></div>
	</div><!-- .heading -->
	
	<div class="newsletter-archive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>Month</th>
				</tr>
			</thead>
			<tbody>
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<tr>
					<td><?php the_title(); ?></td>
					<td><a href="<?php the_field('newsletter_upload') ?>">Download Now</a></td>
				</tr>
				<?php endwhile; else: ?>
					<p>Sorry, no newsletters to download at this time.</p>
				<?php endif; ?>
			</tbody>
		</table>
	</div><!-- .newsletter-archive -->

</section><!-- #page -->

<?php get_footer(); ?>
