<?php
add_shortcode( 'bp-featured-groups', 'bp_featured_groups_shortcode' );
/**
 * Featured group as shortcde.
 *
 * @param array  $atts shortcode atts.
 * @param string $content content.
 *
 * @return string
 */
function bp_featured_groups_shortcode( $atts, $content = '' ) {
	$atts = shortcode_atts( array(
		'view'                 => 'list', // list, slider, default.
		'max'                  => 5,
		'avatar_size'          => '',
		'avatar_type'		   => 'thumb',
		'group_type'           => '',
		'slide-item'           => 1,
		'slide-slide-margin'   => 0,
		'slide-mode'           => 'slide', // slide, fade.
		'slide-speed'          => 400,
		'slide-auto'           => true,
		'slide-pause-on-hover' => false,
		'slide-controls'       => false,
		'slide-loop'           => true,
	), $atts );

	$max         = $atts['max'];
	$avatar_size = $atts['avatar_size'];
	$avatar_type = $atts['avatar_type'];
	$view        = $atts['view'];

	bp_featured_groups()->set( 'max', $max );
	bp_featured_groups()->set( 'avatar_size', $avatar_size );
	bp_featured_groups()->set( 'avatar_type', $avatar_type );
	bp_featured_groups()->set( 'view', $view );
	bp_featured_groups()->set( 'context', 'shortcode' );
	bp_featured_groups()->set( 'group_type', isset( $atts['group_type'] ) ? $atts['group_type'] : '' );

	unset( $atts['max'] );
	unset( $atts['view'] );

	$new_settings = array();

	foreach ( $atts as $key => $val ) {
		$new_settings[ str_replace( 'slide-', '', $key ) ] = $val;
	}

	bp_featured_groups()->set( 'slider-settings', $new_settings );

	if ( $avatar_size > BP_AVATAR_THUMB_WIDTH ) {
		bp_featured_groups()->set( 'avatar_type', 'full' );
	} else {
		bp_featured_groups()->set( 'avatar_type', 'thumb' );
	}

	// log loop start.
	bp_featured_groups()->start_loop();

	ob_start();
	?>

	<div class="bp-featured-groups-shortcode">
		<?php bp_fg_load_groups_list( $view, 'shortcode' ); ?>
	</div>
	<?php
	bp_featured_groups()->end_loop();// mark loop end.
	$content = ob_get_clean();
	return $content;
}
