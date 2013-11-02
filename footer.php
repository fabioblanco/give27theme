<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Fundify
 * @since Fundify 1.0
 */
?>

	<footer id="footer">
		<div class="container">
			<div class="last-widget">
				<?php if ( fundify_is_crowdfunding() ) : ?>
				<h3><?php _e( 'Get the Stats', 'fundify' ); ?></h3>
				<ul>
					<li><?php printf( '<strong>%s</strong> %s', wp_count_posts( 'download' )->publish, _n( edd_get_label_singular(), edd_get_label_plural(), wp_count_posts( 'download' )->publish ) ); ?></li>
					<li><?php printf( __( '<strong>%s</strong> Funded', 'fundify' ), edd_currency_filter( edd_format_amount( edd_get_total_earnings() ) ) ); ?></li>
				</ul>
				<?php endif; ?>

				<div class="copy">
					<p><?php printf( __( '&copy; Copyright %s %s', 'fundify' ), get_bloginfo( 'name' ), date( 'Y' ) ); ?></p>
				</div>
			</div>
		</div>
		<!-- / container -->
	</footer>
	<!-- / footer -->

	<?php wp_footer(); ?>
</body>
</html>
