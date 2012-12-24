<?php get_header(); ?>

<section id="page" class="row">
	<div class="blog-posts span10">
		<h1 class="page-title"><?php
			printf( __( 'Tag: %s', 'smm' ), '<span>' . single_tag_title( '', false ) . '</span>' );?>
		</h1>

		<?php get_template_part( 'loop', 'tag' ); ?>
	</div>
	<?php get_sidebar(); ?>
</section><!-- #page -->

<?php get_footer(); ?>
