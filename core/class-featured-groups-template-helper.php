<?php
// exit if file accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Template helper.
 */
class BP_Featured_Groups_Template_Helper {

	/**
	 * Setup actions.
	 */
	public function setup() {

		// buttons.
		add_action( 'bp_directory_groups_actions', array( $this, 'add_group_list_button' ) );
		add_action( 'bp_group_groups_list_item_action', array( $this, 'add_group_list_button' ) );
		add_action( 'bp_group_header_actions', array( $this, 'add_group_header_button' ) );
		// add to members directory?
		add_action( 'bp_groups_directory_group_filter', array( $this, 'directory_tab' ) );
	}

	/**
	 * Generate the button for the given group
	 *
	 * @param int $group_id group id.
	 */
	public function generate_button( $group_id ) {

		$is_featured = groups_get_groupmeta( $group_id, '_is_featured', true );

		$button_label = $is_featured ? esc_attr__( 'Remove Featured', 'bp-featured-groups' ) : esc_attr__( 'Set Featured', 'bp-featured-groups' );

		?>
		<div class="generic-button bp-featured-groups-button">
			<a href="#" data-nonce="<?php echo esc_attr(wp_create_nonce( 'bp-featured-groups-toggle-' . esc_attr($group_id) )) ?>"
			   data-group-id="<?php echo esc_attr($group_id) ?>">
				<?php echo esc_attr($button_label); ?>
			</a>
		</div>
		<?php
	}

	/**
	 * Add button on single user page(in group header).
	 *
	 * @return string
	 */
	public function add_group_header_button() {
		// checking authentication.
		if ( ! bp_featured_groups()->current_user_can_toggle_group_status() ) {
			return '';
		}

		$this->generate_button( bp_get_group_id() );
	}

	/**
	 * Add button to mark user as featured or un-featured.
	 * return if user is not able to mark user as featured or un-featured
	 *
	 * @return string
	 */
	public function add_group_list_button() {

		// checking permission.
		if ( ! bp_featured_groups()->current_user_can_toggle_group_status() ) {
			return '';
		}

		$this->generate_button( bp_get_group_id() );
	}

	/**
	 * Add featured tab on groups directory.
	 */
	public function directory_tab() {

		if ( ! apply_filters( 'bp_featured_groups_display_directory_tab', true ) ) {
			return;
		}
		?>
		<li id="groups-featured">
			<a href="<?php bp_groups_directory_permalink(); ?>"><?php printf( esc_attr__( 'Featured Groups %s', 'bp-featured-groups' ), '<span>' . esc_attr(bp_featured_groups()->get_count()) . '</span>' ); ?></a>
		</li>
		<?php
	}
}

$helper = new BP_Featured_Groups_Template_Helper();
$helper->setup();
