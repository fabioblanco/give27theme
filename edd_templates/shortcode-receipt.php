<?php
/**
 * This template is used to display the purchase summary with [edd_receipt]
 */
global $edd_receipt_args, $edd_options;

$payment   = get_post( $edd_receipt_args['id'] );
$meta      = edd_get_payment_meta( $payment->ID );
$cart      = edd_get_payment_meta_cart_details( $payment->ID, true );
$user      = edd_get_payment_meta_user_info( $payment->ID );
$status    = edd_get_payment_status( $payment, true );
?>
<table id="edd_purchase_receipt">
	<thead>
		<?php do_action( 'edd_payment_receipt_before', $payment, $edd_receipt_args ); ?>

		<?php if ( $edd_receipt_args['payment_id'] ) : ?>
		<tr>
			<th><strong><?php _e( 'Payment', 'edd' ); ?>:</strong></th>
			<th>#<?php echo $payment->ID; ?></th>
		</tr>
		<?php endif; ?>
	</thead>

	<tbody>

		<?php if ( $edd_receipt_args['date'] ) : ?>
		<tr>
			<td><strong><?php _e( 'Date', 'edd' ); ?>:</strong></td>
			<td><?php echo date_i18n( get_option( 'date_format' ), strtotime( $meta['date'] ) ); ?></td>
		</tr>
		<?php endif; ?>
		<tr>
			<td class="edd_receipt_payment_status"><strong><?php _e( 'Payment Status', 'edd' ); ?>:</strong></td>
			<td class="edd_receipt_payment_status <?php echo strtolower( $status ); ?>"><?php echo $status; ?></td>
		</tr>
		<?php if ( ( $fees = edd_get_payment_fees( $payment->ID, $meta ) ) ) : ?>
		<tr>
			<td><strong><?php _e( 'Fees', 'edd' ); ?>:</strong></td>
			<td>
				<ul class="edd_receipt_fees">
				<?php foreach( $fees as $fee ) : ?>
					<li>
						<span class="edd_fee_label"><?php echo esc_html( $fee['label'] ); ?></span>
						<span class="edd_fee_sep">&nbsp;&ndash;&nbsp;</span>
						<span class="edd_fee_amount"><?php echo edd_currency_filter( edd_format_amount( $fee['amount'] ) ); ?></span>
					</li>
				<?php endforeach; ?>
				</ul>
			</td>
		</tr>
		<?php endif; ?>
		<?php if ( $edd_receipt_args[ 'price' ] ) : ?>

			<tr>
				<td><strong><?php _e( 'Subtotal', 'edd' ); ?></strong></td>
				<td>
					<?php echo edd_payment_subtotal( $payment->ID ); ?>
				</td>
			</tr>
			<?php if( edd_use_taxes() ) : ?>
			<tr>
				<td><strong><?php _e( 'Tax', 'edd' ); ?></strong></td>
				<td><?php echo edd_payment_tax( $payment->ID ); ?></td>
			</tr>
			<?php endif; ?>
			<tr>
				<td><strong><?php _e( 'Total Donation', 'edd' ); ?>:</strong></td>
				<td><?php

					echo edd_payment_amount( $payment->ID );

					if ( edd_use_taxes() && $edd_options['checkout_include_tax'] == 'yes' ) :
						printf( ' ' . __('(includes %s tax)', 'edd'), edd_payment_tax( $payment->ID ) );
					endif; ?>
				</td>
			</tr>
		<?php endif; ?>

		<?php if ( $edd_receipt_args['discount'] && $user['discount'] != 'none' ) : ?>
			<tr>
				<td><strong><?php _e( 'Discount(s)', 'edd' ); ?>:</strong></td>
				<td><?php echo $user['discount']; ?></td>
			</tr>
		<?php endif; ?>

		<?php if ( $edd_receipt_args['payment_method'] ) : ?>
			<tr>
				<td><strong><?php _e( 'Payment Method', 'edd' ); ?>:</strong></td>
				<td><?php echo edd_get_gateway_checkout_label( edd_get_payment_gateway( $payment->ID ) ); ?></td>
			</tr>
		<?php endif; ?>

		<?php if ( $edd_receipt_args['payment_key'] ) : ?>
			<tr>
				<td><strong><?php _e( 'Payment Key', 'edd' ); ?>:</strong></td>
				<td><?php echo get_post_meta( $payment->ID, '_edd_payment_purchase_key', true ); ?></td>
			</tr>
		<?php endif; ?>

		<?php do_action( 'edd_payment_receipt_after', $payment, $edd_receipt_args ); ?>
	</tbody>
</table>

<?php if ( $edd_receipt_args[ 'products' ] ) : ?>

	<h3><?php echo apply_filters( 'edd_payment_receipt_products_title', __( 'Donations', 'edd' ) ); ?></h3>

	<table id="edd_purchase_receipt_products">
		<thead>
			<th><?php _e( 'Name', 'edd' ); ?></th>
			<?php if ( edd_use_skus() ) { ?>
				<th><?php _e( 'SKU', 'edd' ); ?></th>
			<?php } ?>
			<?php if ( edd_item_quanities_enabled() ) { ?>
				<th><?php _e( 'Quantity', 'edd' ); ?></th>
			<?php } ?>
			<th><?php _e( 'Donation amount', 'edd' ); ?></th>
		</thead>

		<tbody>
		<?php foreach ( $cart as $key => $item ) : ?>
			<?php if( empty( $item['in_bundle'] ) ) : ?>
			<tr>
				<td>

					<?php
					$price_id       = edd_get_cart_item_price_id( $item );
					$download_files = edd_get_download_files( $item['id'], $price_id );
					?>

					<div class="edd_purchase_receipt_product_name">
						<?php echo esc_html( $item['name'] ); ?>
						<?php if( ! is_null( $price_id ) ) : ?>
						<span class="edd_purchase_receipt_price_name">&nbsp;&ndash;&nbsp;<?php echo edd_get_price_option_name( $item['id'], $price_id ); ?></span>
						<?php endif; ?>
					</div>

					<?php if ( $edd_receipt_args['notes'] ) : ?>
						<div class="edd_purchase_receipt_product_notes"><?php echo edd_get_product_notes( $item['id'] ); ?></div>
					<?php endif; ?>

				</td>
				<?php if ( edd_use_skus() ) : ?>
					<td><?php echo edd_get_download_sku( $item['id'] ); ?></td>
				<?php endif; ?>
				<?php if ( edd_item_quanities_enabled() ) { ?>
					<td><?php echo $item['quantity']; ?></td>
				<?php } ?>
				<td>
					<?php if( empty( $item['in_bundle'] ) ) : // Only show price when product is not part of a bundle ?>
						<?php echo edd_currency_filter( edd_format_amount( $item[ 'price' ] ) ); ?>
					<?php endif; ?>
				</td>
			</tr>
			<?php endif; ?>
		<?php endforeach; ?>
		</tbody>

		<tfoot>
			<tr>
				<?php
				$colspan = '';
				if( edd_use_skus() && edd_item_quanities_enabled() ) {
					$colspan = ' colspan="3"';
				} elseif( edd_use_skus() || edd_item_quanities_enabled() ) {
					$colspan = ' colspan="2"';
				}
				?>
				<td<?php echo $colspan; ?>><strong><?php _e( 'Total Donation', 'edd' ); ?>:</strong></td>

				<td>
					<?php
					echo edd_payment_amount( $payment->ID );
					if ( edd_use_taxes() && ( ! edd_prices_show_tax_on_checkout() && $edd_options['prices_include_tax'] == 'yes' ) ) {
						echo ' ' . __( '(incl. tax)', 'edd' );
					} else if ( edd_use_taxes() && $edd_options['checkout_include_tax'] == 'yes' ) {
						printf( ' ' . __( '(includes %s tax)', 'edd' ), edd_payment_tax( $payment->ID ) );
					} ?>
				</td>
			</tr>
		</tfoot>

	</table>
<?php endif; ?>
