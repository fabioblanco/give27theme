<?php

/**
 * Register only the sidebar widget. See fundify/functions.php:82
 */
function new_fundify_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'fundify' ),
		'id' => 'sidebar-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}

/**
 * Fix the logic that selects the giving amount since our button is not in the modal window. 
 * See crowdfunding.js:57-59 in the appthemer-crowdfunding plugin.
 */
function enqueue_button_fixer_script() {
  wp_enqueue_script('fundify-child-button-fixer-script', get_stylesheet_directory_uri() . '/js/buttonfixerscript.js', array('atcf-scripts', 'jquery') );
}
add_action('wp_enqueue_scripts', 'enqueue_button_fixer_script');

/**
 * Download labels. Change from "Campaigns" "Families"
 * @param array $labels The preset labels
 * @return array $labels The modified labels
 */
function family_labels( $labels ) {
  $labels =  apply_filters( 'atcf_campaign_labels', array(
    'name' 				=> __( 'Families', 'atcf' ),
    'singular_name' 	=> __( 'Family', 'atcf' ),
    'add_new' 			=> __( 'Add New', 'atcf' ),
    'add_new_item' 		=> __( 'Add New Family', 'atcf' ),
    'edit_item' 		=> __( 'Edit Family', 'atcf' ),
    'new_item' 			=> __( 'New Family', 'atcf' ),
    'all_items' 		=> __( 'All Families', 'atcf' ),
    'view_item' 		=> __( 'View Family', 'atcf' ),
    'search_items' 		=> __( 'Search Families', 'atcf' ),
    'not_found' 		=> __( 'No Families found', 'atcf' ),
    'not_found_in_trash'=> __( 'No Families found in Trash', 'atcf' ),
    'parent_item_colon' => '',
    'menu_name' 		=> __( 'Families', 'atcf' )
  ) );

  return $labels;
}

/**
 * Further change "Campaign" & "Campaigns" to "Family" and "Families"
 * @param array $labels The preset labels
 * @return array $labels The modified labels
 */
function family_names( $labels ) {
  $cpt_labels = family_labels( array() );

  $labels = array(
    'singular' => $cpt_labels[ 'singular_name' ],
    'plural'   => $cpt_labels[ 'name' ]
  );

  return $labels;
}

function new_atcf_edd_purchase_form_user_info() {
	if ( ! atcf_theme_supports( 'anonymous-backers' ) )
		return;
?>
	<p id="edd-anon-wrap">
		<label class="edd-label" for="edd-anon">
			<input class="edd-input" type="checkbox" name="edd_anon" id="edd-anon" style=
			"vertical-align: middle;" />
			<?php _e( 'Hide name on donors list?', 'atcf' ); ?>
		</label>
	</p>
<?php
}

/**
 * The ol' switcheroo 
 */
define( 'EDD_SLUG', 'families' );
function change_old_fundify() {
  remove_action('widgets_init','fundify_widgets_init');
  add_action('widgets_init','new_fundify_widgets_init');

  remove_action( 'edd_purchase_form_user_info', 'atcf_edd_purchase_form_user_info' );
  add_action( 'edd_purchase_form_user_info', 'new_atcf_edd_purchase_form_user_info' );

  add_filter( 'edd_download_labels', 'family_labels', 20 );
  add_filter( 'edd_default_downloads_name', 'family_names', 20 );
}
add_action('after_setup_theme','change_old_fundify');

