<?php
	$temp = $wp_query;
	$wp_query = null;
	$wp_query = new WP_Query();
	$wp_query->query('&paged='.$paged);
	while ($wp_query->have_posts()) : $wp_query->the_post();
?>

 <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<h2 class="entry-title">
			<a href="<?php the_permalink(); ?>"><?php the_title();  ?></a>
		</h2>
		<div class="post-author">By <?php the_author(); ?> <a href="http://twitter.com/<?php the_author_meta( 'twitter' ); ?>">@<?php the_author_meta( 'twitter' ); ?></a>
		</div>
		<div class="post-date"><?php the_date(); ?></div>
		
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( array (530, 260) ); ?></a>
		
		<div class="entry-utility">
		<?php if ( count( get_the_category() ) ) : ?>
			<span class="cat-links">
				<?php printf( __( '<span class="%1$s">Posted in</span> %2$s', 'smm' ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ', ' ) ); ?>
			</span>
			
		<?php endif; ?>
		<?php
			$tags_list = get_the_tag_list( '', ', ' );
			if ( $tags_list ):
		?>
			<span class="meta-sep">&nbsp;&nbsp;&nbsp;</span>
			<span class="tag-links">
				<?php printf( __( '<span class="%1$s">Tagged</span> %2$s', 'smm' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?>
			</span>
			
		<?php endif; ?>
			
		</div><!-- .entry-utility -->
		<?php the_excerpt(); ?>
	</article><!-- #post -->

	<?php endwhile; ?>

<?php bootstrap_pagination(); ?>

<?php $wp_query = null; $wp_query = $temp;?>