<?php
// Exit if file accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ajax action handler.
 */
class BP_Featured_Groups_Ajax_Action_Handler {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_bp_process_featured_groups_status', array( $this, 'handle' ) );
	}

	/**
	 * Handle marking/removing as featured member.
	 */
	public function handle() {

		$group_id = isset( $_POST['group_id'] ) ? absint( $_POST['group_id'] ) : 0;

		check_ajax_referer( 'bp-featured-groups-toggle-' . $group_id );

		if ( ! bp_featured_groups()->current_user_can_toggle_group_status() ) {
			wp_send_json_error( esc_attr__( "You don't have permission.", 'bp-featured-groups' ) );
		}

		$fm = bp_featured_groups();

		if ( $fm->is_featured( $group_id ) ) {
			$success   = $fm->remove_group( $group_id );
			$btn_label = esc_attr__( 'Set Featured', 'bp-featured-groups' );
		} else {
			$success   = $fm->add_group( $group_id );
			$btn_label = esc_attr__( 'Remove Featured', 'bp-featured-groups' );
		}

		if ( $success ) {
			wp_send_json_success( array( 'btn_label' => $btn_label ) );
		} else {
			wp_send_json_error( 'Something went wrong!', 'bp-featured-groups' );
		}
	}
}

new BP_Featured_Groups_Ajax_Action_Handler();
