<?php
/**
 * BuddyPress - Groups Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter().
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */

?>

<?php

/**
 * Fires before the display of groups from the groups loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_featured_groups_loop' );
$view = bp_featured_groups()->get( 'view' );
$featured = bp_fg_get_featured();  ?>

<?php if ( bp_get_current_group_directory_type() ) : ?>
	<p class="current-group-type"><?php bp_current_group_directory_type_message() ?></p>
<?php endif; ?>

<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' ) . '&user_id="0"&include=' . $featured . '&slug=&type=alphabetical' ) ) : ?>

	<ul id="groups-list" class="item-list" aria-live="assertive" aria-atomic="true" aria-relevant="all">

	<?php while ( bp_groups() ) : bp_the_group(); ?>

		<li style="list-style-type: none;"<?php bp_group_class(); ?>>
			<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
			<div class="list-wrap">
				<div class="item-avatar">
					<a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( 'type=thumb&width=50&height=50' ); ?></a>
				</div>
			<?php endif; ?>

			<div class="item">
				<div class="item-title"><?php bp_group_link(); ?></div>
				<div class="item-meta"><span class="activity" data-livestamp="<?php esc_attr(bp_core_iso8601_date( bp_get_group_last_active( 0, array( 'relative' => false ))) ); ?>"><?php printf( esc_attr__( 'active %s', 'bp-featured-groups' ), esc_attr(bp_get_group_last_active()) ); ?></span></div>

				<div class="item-desc"><?php bp_group_description_excerpt(); ?></div>


			</div>

			<div class="action">

				<div class="meta">

					<?php bp_group_type(); ?> / <?php bp_group_member_count(); ?>

				</div>

			</div>

			<div class="clear"></div>
			</div>
		</li>

	<?php endwhile; ?>

	</ul>

	<?php

	/**
	 * Fires after the listing of the groups list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_featured_groups_list' ); 
	else: ?>

	<div id="message" class="info">
		<p><?php esc_attr_e( 'There were no groups found.', 'bp-featured-groups' ); ?></p>
	</div>

<?php endif; 
