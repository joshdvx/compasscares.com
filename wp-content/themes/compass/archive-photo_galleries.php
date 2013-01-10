<?php get_header(); ?>

<section id="page" class="photo-gallery">
	
	<div class="heading">
		<h1 class="section-header">
			<div class="main-title">We Look Good and We Know It</div>
			<div class="subtitle">Photo Gallery</div>
		</h1>
		<div class="heading-arrow"></div>
	</div>
	
	<ul class="nav nav-tabs gallery-filters">
		<li class="filters">Filters:</li>
		<li class="active">
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
			<div class="span4 single-photos">
				<?php
					$images = get_field('select_photos');
					if( $images ): ?>
			            <?php foreach( $images as $image ): ?>
			                <a href="<?php the_permalink(); ?>"><img src="<?php echo $image['sizes']['photo_gallery']; ?>" alt="<?php echo $image['alt']; ?>" width="280" height="240" /></a>
		                	<?php break; ?>
		            	<?php endforeach; ?>
				    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				    <p><?php echo get_the_term_list( $post->ID, 'regions', '', ', ', '' ); ?> - <?php echo count($images); ?> photos</p>
				<?php endif; ?>
			</div><!-- .single-photos -->
		<?php endwhile; endif; ?>
	</div><!-- .row -->
		
</section><!-- #page -->
<?php get_footer(); ?>
