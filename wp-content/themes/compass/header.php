<!DOCTYPE html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7 ]>
<html class="no-js ie6" <?php language_attributes(); ?>> 
<![endif]-->
<!--[if IE 7 ]>    
<html class="no-js ie7" <?php language_attributes(); ?>> 
<![endif]-->
<!--[if IE 8 ]>    
<html class="no-js ie8" <?php language_attributes(); ?>>
 <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> 
<html class="no-js" <?php language_attributes(); ?>> 
<!--<![endif]-->
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    	<title><?php
			global $page, $paged;
			wp_title( '|', true, 'right' );
			bloginfo( 'name' );
			$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description && ( is_home() || is_front_page() ) )
				echo " | $site_description";
			if ( $paged >= 2 || $page >= 2 )
				echo ' | ' . sprintf( __( 'Page %s', 'smm' ), max( $paged, $page ) );
			?></title>
		<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    	<meta name="description" content="">
    	<meta name="author" content="Sharp Machine Media">
	
		<link rel="profile" href="http://gmpg.org/xfn/11" />
    	<!-- Le styles -->
    	<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
    	<link href="<?php bloginfo('template_directory'); ?>/css/bootstrap.css" rel="stylesheet">
    	<link href="<?php bloginfo('template_directory'); ?>/css/print.css" rel="stylesheet" media="print">

		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

    	<!-- Le fav and touch icons -->
    	<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/img/favicon.ico">
            
		<script src="<?php bloginfo('template_directory'); ?>/js/modernizr-1.7.min.js"></script>
		<!--[if lte IE 8]><script src="<?php bloginfo('template_directory'); ?>/js/selectivizr-min.js"></script><![endif]-->
	
		<?php
			if ( is_singular() && get_option( 'thread_comments' ) )
				wp_enqueue_script( 'comment-reply' );
			wp_head();
		?>

	    <script src="<?php bloginfo('template_directory'); ?>/js/stickyheaders.js"></script>
	    <script>
			jQuery(document).ready(function($) {
			    // Sticky headings for homepage
				$(window).stickySectionHeaders({
					headlineSelector: '.heading',
					headlineHeight: 100,
					topPadding: 113
				});
			});
	    </script>
	</head>

	<body <?php body_class(); ?>>
		<header>
			<div class="navbar navbar-fixed-top">
				<div class="navbar-inner">
					<div class="container">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	            			<span class="icon-bar"></span>
	            			<span class="icon-bar"></span>
	            			<span class="icon-bar"></span>
						</a>
						<a class="brand" href="<?php bloginfo('url') ?>"><img src="<?php bloginfo('template_directory'); ?>/img/logo.png" alt="Logo"><h1 class="tagline"><?php bloginfo('description'); ?></h1></a>
						<div class="nav-collapse">
							<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'nav nav-primary', 'walker' => new Bootstrap_Menu_Walker ) ); ?>
							<?php wp_nav_menu( array( 'theme_location' => 'secondary', 'container' => false, 'menu_class' => 'nav nav-secondary pull-right', 'walker' => new Bootstrap_Menu_Walker ) ); ?>
						</div><!--/.nav-collapse -->
	        		</div>
				</div>
	    	</div>
    	</header>
    	<div id="wrapper">
