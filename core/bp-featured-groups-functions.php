<?php
// Exit if file accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get a list og valid view options
 *
 * @return array
 */
function bp_fg_get_views_options() {
	$options = array(
		'list'    => esc_attr__( 'List', 'bp-featured-groups' ),
		'slider'  => esc_attr__( 'Slider', 'bp-featured-groups' ),
		'default' => esc_attr__( 'Theme Default', 'bp-featured-groups' ),
	);

	return $options;
}

/**
 * Print slider data attributes.
 *
 * @param array $settings array of atts.
 */
function bp_groups_slider_data_attributes( $settings = array() ) {
	foreach ( $settings as $key => $val ) {
		echo "data-" . esc_attr($key) . "='" . esc_attr( $val ) . "' ";
	}
}

/**
 * Load featured groups list.
 *
 * @param string $view_type selected view type.
 * @param string $context widget/shortcode etc.
 * @param bool   $load load or return.
 *
 * @return mixed|string|void
 */
function bp_fg_load_groups_list( $view_type, $context = 'widget', $load = true ) {

	if ( ! in_array( $view_type, array( 'default', 'list', 'slider', 'grid', 'list2' ) ) ) {
		$view_type = 'list';// fallback to list if invalid view type.
	}

	// in case of default view type, we load it from theme.
	if ( $view_type == 'default' ) {
		$located = bp_locate_template( array( 'groups/groups-loop.php' ), false, false );
	} else {

		$templates = array(
			"groups/featured/{$context}/groups-loop-{$view_type}.php",
			"groups/featured/groups-loop-{$view_type}.php",
		);

		$located = bp_locate_template( $templates, false, false );

		if ( ! $located ) {
			$located = bp_featured_groups()->get_path() . 'templates/groups-loop-' . $view_type . '.php';
		}
	}

	$located = apply_filters( 'bp_featured_groups_located_template', $located, $view_type, $context );

	if ( $load ) {
		require $located;
	} else {
		return $located;
	}
}

/**
 * Get avatar args
 *
 * @return array
 */
function bp_fg_get_avatar_args() {

	$avatar_size = bp_featured_groups()->get( 'avatar_size' );
	$avatar_type = bp_featured_groups()->get( 'avatar_type' );

	return array(
		'type'   => $avatar_type,
		'width'  => $avatar_size,
		'height' => $avatar_size,
	);
}


/**
 * Get comma delimited list of featured groups
 *
 * @return comma delimited list of featured group id's
 */
 
function bp_fg_get_featured() {
	
	$groups = groups_get_groups();
	$featured = '';
	$groups = $groups['groups'];
	if ( isset( $groups ) ) {
		foreach ( $groups as $group ) {
			if (bp_featured_groups()->is_featured( $group->id ) == 1 ) {
				$featured .= $group->id . ',';
			}
		}
	}
	
	return $featured;
	
}

function bp_fg_show_featured_in_directory() {
	
	if ( bp_is_user_profile() || bp_is_user() ) {
		return;
	}
	
	$content = 'shortcode';
	
	$atts = bpfg_get_banner_settings();
	
	//No output search string
	$search_text = esc_attr( esc_attr__( 'There were no groups found.', 'bp-featured-groups' ) );
	
	if ( $atts['enabled'] ) {
		$output = bp_featured_groups_shortcode( $atts, $content );
		if ( ! strpos( $output, $search_text ) ) {
			if ( $atts['title_before'] ) {
				echo '<h2>' . esc_html($atts['title_before']) . '</h2>';
			}
			echo $output; //escaped in bp_featured_groups_shortcode()
			if ( $atts['title_after'] ) {
				echo '<h2>' . esc_html($atts['title_after']) . '</h2>';
			}
		}
	}
}

add_action( 'bp_before_groups_loop', 'bp_fg_show_featured_in_directory' );

function bpfg_get_banner_settings() {

	$settings = get_option( 'bpfg_groups_directory_banner' );
	
	$atts = array(
		'enabled'			   => false,
		'title_before'		   => '',
		'title_after'		   => '',
		'view'                 => 'grid',
		'max'                  => 5,
		'avatar_size'          => '200px',
		'avatar_type'		   => 'full',
		'group_type'           => '',
	);

	if ( is_array( $settings ) ) {
		foreach ( $settings as $option => $setting ) {
			$settings[$option] = sanitize_text_field( $setting );
		}
		$atts = shortcode_atts( $atts, $settings);
	}
	
	return $atts;
	
}
