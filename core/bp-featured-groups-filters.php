<?php
/**
 * List filtering.
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Filter bp_has_groups() transparently for listing our groups
 *
 * @param array $args args array.
 *
 * @return mixed
 */
function bp_featured_groups_filter_groups_list( $args ) {
	// our scope must be featured.
	if ( bp_featured_groups()->in_the_loop() || ( isset( $args['scope'] ) && $args['scope'] == 'featured' ) ) {

		$groups = groups_get_groups();
		$groups = $groups['groups'];
		$group_includes = array();
		$group_excludes = array();
		if ( $groups ) {
			foreach ( $groups as $group ) {
				$featured = groups_get_groupmeta( $group->id, '_is_featured', true );
				if ( isset( $featured ) && $featured == 1 ) {
					$group_includes[] = $group->id;
				} else {
					$group_excludes[] = $group->id;
				}
			}
		}
		
		$args['include'] = $group_includes;
		$args['exclude'] = $group_excludes;
		// which other params are we allowing?
		$max         = bp_featured_groups()->get( 'max' );
		$group_type  = bp_featured_groups()->get( 'group_type' );

		if ( $max ) {
			$args['per_page'] = absint( $max );
			$args['max']      = absint( $max );
		}

		if ( $group_type ) {
			$args['group_type'] = $group_type;
		}
			
	}

	return $args;
}

add_filter( 'bp_after_has_groups_parse_args', 'bp_featured_groups_filter_groups_list' );
