<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Fundify
 * @since Fundify 1.0
 */

get_header(); ?>

	<div class="title pattern-<?php echo rand(1,4); ?>">
		<div class="container">
			<?php while ( have_posts() ) : the_post(); ?>
			<h1><?php the_title() ;?></h1>
			<?php endwhile; ?>
		</div>
		<!-- / container -->
	</div>
	<div id="content">
		<div class="container">
			<div id="main-content">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content', 'single' ); ?>
				<?php endwhile; ?>
			</div>
			<?php //get_sidebar(); ?>
		</div>
		<!-- / container -->
	</div>
	<!-- / content -->

<?php get_footer(); ?>
