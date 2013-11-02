<?php
/**
 * Template Name: Give 27 Front Page
 */

global $wp_query;

get_header(); 
?>

<div id="content">

<?php while ( have_posts() ) : the_post();?>
  <?php the_content(); ?>
<?php endwhile; ?>

</div>
<!-- / content -->
<?php get_footer(); ?>
