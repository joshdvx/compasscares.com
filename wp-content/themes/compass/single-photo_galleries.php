<?php get_header(); ?>

<section id="page" class="photo-gallery">

	<div class="heading">
		<h1 class="section-header">
			<?php the_title(); ?>
			<span class="subtitle">Photo Gallery</span>
		</h1>
		<div class="heading-arrow"></div>
	</div><!-- .heading -->
	
	<ul class="nav nav-tabs gallery-filters">
		<li class="filters">Filters:</li>
		<li>
			<a href="<?php bloginfo('url') ?>/photo-gallery">ALL</a>
		</li>
		<li>
			<a href="<?php bloginfo('url') ?>/regions/fnrc">FNRC</a>
		</li>
		<li>
			<a href="<?php bloginfo('url') ?>/regions/rceb">RCEB</a>
		</li>
		<li>
			<a href="<?php bloginfo('url') ?>/regions/sarc">SARC</a>
		</li>
	</ul><!-- .gallery-filters -->
	
	<div class="row">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php
				$images = get_field('select_photos');
				if( $images ): ?>
		            <?php foreach( $images as $image ): ?>
		            <div class="span4 single-photos">
		                <a href="<?php echo $image['sizes']['large']; ?>" rel="lightbox" title="<?php echo $image['alt']; ?>"><img src="<?php echo $image['sizes']['photo_gallery']; ?>" alt="<?php echo $image['alt']; ?>" width="280" height="240" /></a>
		                <p class="photo-caption"><?php echo $image['alt']; ?></p>
	             	</div>
	           		 <?php endforeach; ?>
			<?php endif; ?>
		<?php endwhile; endif; ?>
	</div><!-- .row -->	
</section><!-- #page -->
<?php get_footer(); ?>
