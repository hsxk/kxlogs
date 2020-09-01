<?php
/**
 * Template Name: Photocard Cover Template
 * Template Post Type: post, page
 *
 * @package WordPress
 */

get_header();
?>

<main id="site-content" role="main">

	<?php

	if ( have_posts() ) {

		while ( have_posts() ) {
			the_post();
			wp_enqueue_style( 'photocard_css', get_stylesheet_directory_uri() . '/assets/css/photocard.css' );
			get_template_part( 'template-parts/photocard' );
			get_template_part( 'template-parts/content-cover' );
		}
	}

	?>

</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer(); ?>
