<?php

// Exit if file accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Create Menu Option
add_action( 'admin_menu', 'bpfg_admin_add_page' );

function bpfg_admin_add_page() {
	
	$options_page = esc_attr_x('BP Featured Groups','bp-featured-groups');
	
	add_options_page( $options_page, $options_page, 'manage_options', 'bpfg', 'bpfg_options_page' );

}

// Construct the Save Changes button
function bpfg_options_page() {

?>
	
	<div class="wrap">
		
		<h2><?php esc_attr_e( 'BP Featured Groups', 'bp-featured-groups' ) ?></h2>
		
		<form action="options.php" method="post">
			
			<?php settings_fields( 'bpfg' ); ?>
			
			<?php do_settings_sections( 'bpfg' ); ?>
			
			<input name="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes', 'bp-featured-groups' ); ?>" class="button button-primary" />
		
		</form>
	
	</div>
	
<?php
}

add_action( 'admin_init', 'bpfg_admin_init' );

//Set up the Settings Sections and Fields
function bpfg_admin_init(){
	
	// Create Settings
	$settings = bpfg_get_banner_settings();
	$option_group = 'bpfg';
	$option_name = 'bpfg_groups_directory_banner';
	
	register_setting( $option_group, $option_name );

	$banner_options = 'bpfg_banner_options';
	register_setting( $option_group, $banner_options );
	
	// Create Settings page Section for BP Featured Groups
	$settings_section = 'bpfg_banner';
	$page = 'bpfg';
	add_settings_section( $settings_section, esc_attr__( 'Banner Options', 'bp-featured-groups' ), 'bpfg_main_section_text_output', $page );

	// Add Banner Enable field.
	add_settings_field( $option_name . '_enable', esc_attr__('Enable Banner', 'bp-featured-groups' ), 'bpfg_enable_input_renderer', $page, $settings_section, esc_attr( $settings['enabled'] ) );

	// Add Title Before field.
	add_settings_field( $option_name . '_title_before', esc_attr__('Title Before', 'bp-featured-groups' ), 'bpfg_title_before_input_renderer', $page, $settings_section, esc_attr( $settings['title_before'] ) );

	// Add Title After field.
	add_settings_field( $option_name . '_title_after', esc_attr__('Title After', 'bp-featured-groups' ), 'bpfg_title_after_input_renderer', $page, $settings_section, esc_attr( $settings['title_after'] ) );

	// Add Max field.
	add_settings_field( $option_name . '_max', esc_attr__('Max Groups to show', 'bp-featured-groups' ), 'bpfg_max_input_renderer', $page, $settings_section, esc_attr( $settings['max'] ) );

	// Add Avatar Size field.
	add_settings_field( $option_name . '_avatar_size', esc_attr__('Avatar_size', 'bp-featured-groups' ), 'bpfg_avatar_size_input_renderer', $page, $settings_section, esc_attr( $settings['avatar_size'] ) );

	// Add Avatar Type field.
	add_settings_field( $option_name . '_avatar_type', esc_attr__('Avatar Type', 'bp-featured-groups' ), 'bpfg_avatar_type_input_renderer', $page, $settings_section, esc_attr( $settings['avatar_type'] ) );

	// Add Group Type field.
	add_settings_field( $option_name . '_group_type', esc_attr__('Group Type', 'bp-featured-groups' ), 'bpfg_group_type_input_renderer', $page, $settings_section, esc_attr( $settings['group_type'] ) );

	// Add Banner Type field.
	add_settings_field( $option_name . '_banner_type', esc_attr__('Banner Type', 'bp-featured-groups' ), 'bpfg_banner_type_input_renderer', $page, $settings_section, esc_attr( $settings['view'] ) );
	
}

// Page Information for Groups Directory featured groups banner 
function bpfg_main_section_text_output() {
	
	echo '<p>' , esc_attr__('You can choose to add a banner to the groups directory page showing the featured groups. Here you can enable the banner, set a title to display before and after the banner and specify the size and type of avatar to display.', 'bp-featured-groups') . '</p>';
	
}

// Enable setting content render
function bpfg_enable_input_renderer( $enabled = false ) {
	
	echo '<input type="checkbox" name="bpfg_groups_directory_banner[enabled]" ' . ( $enabled == true ? 'checked="checked"' : '' ) . '>' . esc_attr__( 'Group Directory Banner Enabled', 'bp-featured-groups' ) . '<br>';

}

// Title Before setting content render
function bpfg_title_before_input_renderer( $title_before = '' ) {
	
	echo '<input type="text" name="bpfg_groups_directory_banner[title_before]" value="' . esc_html($title_before) . '" >' . esc_attr__( 'Add a title before the banner', 'bp-featured-groups' ) . '<br>';

}

// Title After setting content render
function bpfg_title_after_input_renderer( $title_after = '' ) {
	
	echo '<input type="text" name="bpfg_groups_directory_banner[title_after]" value="' . esc_html($title_after) . '" >' . esc_attr__( 'Add a title after the banner', 'bp-featured-groups' ) . '<br>';

}

// Max setting content render
function bpfg_max_input_renderer( $max = 4 ) {
	
	echo '<input type="number" min=1 max=20 name="bpfg_groups_directory_banner[max]" value="' . esc_attr($max) . '" >' . esc_attr__( 'Maximum groups to show in the banner', 'bp-featured-groups' ) . '<br>';

}

// Avatar Size setting content render
function bpfg_avatar_size_input_renderer( $avatar_size = '' ) {
	
	echo '<input type="text" name="bpfg_groups_directory_banner[avatar_size]" value="' . esc_attr($avatar_size) . '" >' . esc_attr__( 'Avatar Size, e.g. 100px, 100% etc.', 'bp-featured-groups' ) . '<br>';

}

// Page Input box for avatar type.
function bpfg_avatar_type_input_renderer( $avatar_type = 'thumb' ){
	
	$avatar_types = Array( 
		'full' 		=> esc_attr__( 'Full Size', 'bp-featured-groups' ),
		'thumb' 	=> esc_attr__( 'Thumbnail', 'bp-featured-groups' )
	);
	
	echo '<select name="bpfg_groups_directory_banner[avatar_type]">';
	
	foreach ( $avatar_types as $item => $type) {
		
		echo '<option value="'. esc_attr($item) .'" ' . ( $avatar_type == $item ? 'selected="selected"' : '' ).'>'. esc_attr($type) .'</option>';
	
	}
	
	echo '</select>';

}

// Group Type setting content render
function bpfg_group_type_input_renderer( $group_type = '' ) {
	
	echo '<input type="text" name="bpfg_groups_directory_banner[group_type]" value="' . esc_attr($group_type) . '" >' . esc_attr__( 'Specify a group type to display (leave blank if you have not configured group types)', 'bp-featured-groups' ) . '<br>';

}


// Page Input box for banner type.
function bpfg_banner_type_input_renderer( $banner_type = 'grid' ){
	
	$banner_types = Array( 
		'grid' 		=> esc_attr__( 'Grid', 'bp-featured-groups' ),
		'list'		=> esc_attr__( 'List Type 1', 'bp-featured-groups' ),
		'list2' 	=> esc_attr__( 'List Type 2', 'bp-featured-groups' )
	);
	
	echo '<select name="bpfg_groups_directory_banner[view]">';
	
	foreach ( $banner_types as $item => $type) {
		
		echo '<option value="'. esc_attr($item) .'" ' . ( $banner_type == $item ? 'selected="selected"' : '' ).'>'. esc_attr($type) .'</option>';
	
	}
	
	echo '</select>';

}

