<?php
/**
 * Groups list template
 */

// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

	$view = bp_featured_groups()->get( 'view' );
	$featured = bp_fg_get_featured(); 
	if ( bp_has_groups( bp_ajax_querystring( 'groups' ) . '&user_id="0"&include=' . $featured . '&slug=&type=alphabetical' ) ) : ?>
	<div style="display: flex;" class="featured-groups-<?php echo esc_attr( $view ); ?>" >
	<?php while ( bp_groups() ) : bp_the_group(); ?>
		<div style="display: grid; margin-left: auto; margin-right: auto;" class="featured-group-item">
			<div class="item-avatar-bpfg">
				<a href="<?php bp_group_permalink() ?>" title="<?php echo esc_attr( bp_get_group_name() ); ?>">
					<?php bp_group_avatar( bp_fg_get_avatar_args() ); ?>
				</a>
			</div>
			<div class="item">
				<div class="item-title" style="text-align: center;">
					<a href="<?php bp_group_permalink(); ?>" title="<?php echo esc_attr( bp_get_group_name() ); ?>">
						<?php bp_group_name(); ?>
					</a>
				</div>
			</div>
		</div>
	<?php endwhile; ?>
	</div>

<?php else : ?>

	<div id="message" class="info">
		<p><?php esc_attr_e( 'Sorry, no groups were found.', 'bp-featured-groups' ); ?></p>
	</div>

<?php endif; ?>
