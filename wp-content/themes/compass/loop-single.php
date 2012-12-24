<?php
/**
 * The loop that displays a single post.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop-single.php.
 *
 */
?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h1 class="entry-title"><?php the_title(); ?></h1>

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

					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'smm' ), 'after' => '</div>' ) ); ?>
					</div><!-- .entry-content -->

<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description, show a bio on their entries  ?>
					<div id="entry-author-info">
						<div id="author-avatar" class="roundy">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'smm_author_bio_avatar_size', 155 ) ); ?>
						</div><!-- #author-avatar -->
						<div id="author-description">
							<h3><?php printf( esc_attr__( 'About %s', 'smm' ), get_the_author() ); ?></h3>
							<?php the_author_meta( 'description' ); ?>
						</div><!-- #author-description -->
					</div><!-- #entry-author-info -->
<?php endif; ?>
					<?php comments_number(); ?> | <span class="show-comments">Hide/Show Comments</span>
				</div><!-- #post-## -->
				

				<?php comments_template( '', true ); ?>

<?php endwhile; // end of the loop. ?>