<?php
/**
 * Campaign sharing.
 *
 * @package Fundify
 * @since Fundify 1.5
 */

global $campaign;

$message = apply_filters( 'fundify_share_message', sprintf( __( 'Check out %s on %s! %s', 'fundify' ), $post->post_title, get_bloginfo( 'name' ), get_permalink() ) );
?>

<div class="entry-share">
	<?php _e( 'Share this campaign', 'fundify' ); ?>

	<a href="<?php echo esc_url( sprintf( 'http://twitter.com/home?status=%s', urlencode( $message ) ) ); ?>" target="_blank" class="share-twitter"><i class="icon-twitter"></i></a>
	
	<a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" target="_blank" class="share-google"><i class="icon-gplus"></i></a>

	<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'blog' ); ?>
	<a href="http://www.facebook.com/sharer.php?s=100
		&p[url]=<?php echo urlencode( get_permalink() ); ?>
		&p[images][0]=<?php echo urlencode( $image[0]); ?>
		&p[title]=<?php echo urlencode( $post->post_title ); ?>
		&p[summary]=<?php echo urlencode( $message ); ?>" class="share-facebook"><i class="icon-facebook"></i></a>
	
	<a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php echo urlencode( $image[0]); ?>&description=<?php echo urlencode( get_the_excerpt()); ?>" target="_blank" class="share-pinterest"><i class="icon-pinterest"></i></a>
	
	<a href="<?php the_permalink(); ?>" target="_blank" class="share-link"><i class="icon-link"></i></a>
	
	<a href="share-widget" class="share-widget fancybox"><i class="icon-code"></i></a>

	<div id="share-widget" class="fundify-modal">
		<?php get_template_part( 'modal', 'campaign-widget' ); ?>
	</div>
</div>
