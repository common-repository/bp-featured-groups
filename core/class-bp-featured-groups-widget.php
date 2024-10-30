<?php
/**
 * Plugin widget to list featured groups using widget
 *
 * @package bp-featured-groups
 */

// Exit if file accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * BP_Featured_Groups_List_Widget widget
 */
class BP_Featured_Groups_List_Widget extends WP_Widget {

	/**
	 * The constructor.
	 *
	 * @param string $name Widget name.
	 * @param array  $widget_options Widget options.
	 */
	public function __construct( $name = '', $widget_options = array() ) {

		if ( empty( $name ) ) {
			$name = esc_attr__( 'BuddyPress Featured Groups', 'bp-featured-groups' );
		}

		parent::__construct( false, $name, $widget_options );
	}

	/**
	 * Display widget content.
	 *
	 * @param array $args widget args.
	 * @param array $instance current widget instance.
	 */
	public function widget( $args, $instance ) {

		$avatar_size = isset( $instance['avatar_size'] ) ? $instance['avatar_size'] : '';
		$member_type = isset( $instance['group_type'] ) ? $instance['group_type'] : '';

		bp_featured_groups()->set( 'max', $instance['max'] );
		bp_featured_groups()->set( 'avatar_size', $avatar_size );
		bp_featured_groups()->set( 'view', $instance['view'] );
		bp_featured_groups()->set( 'context', 'widget' );
		bp_featured_groups()->set( 'member_type', $member_type );

		$slide_auto           = ( $instance['slide_auto'] ) ? true : false;
		$slide_pause_on_hover = ( $instance['slide_pauseOnHover'] ) ? true : false;
		$slide_controls       = ( $instance['slide_controls'] ) ? true : false;
		$slide_loop           = ( $instance['slide_loop'] ) ? true : false;

		$slider_settings = array(
			'item'           => $instance['slide_item'],
			'slide-margin'   => $instance['slide_slideMargin'],
			'mode'           => $instance['slide_mode'], // slide.
			'speed'          => $instance['slide_speed'],
			'auto'           => $slide_auto,
			'pause-on-hover' => $slide_pause_on_hover,
			'controls'       => $slide_controls,
			'loop'           => $slide_loop,
		);

		bp_featured_groups()->set( 'slider-settings', $slider_settings );

		if ( $avatar_size > BP_AVATAR_THUMB_WIDTH ) {
			bp_featured_groups()->set( 'avatar_type', 'full' );
		} else {
			bp_featured_groups()->set( 'avatar_type', 'thumb' );
		}

		// log loop start.
		bp_featured_groups()->start_loop();

		echo esc_html($args['before_widget']);

		echo esc_html($args['before_title']) . esc_html( apply_filters( 'widget_title', $instance['title'] , $instance, $this->id_base ) ) . esc_html($args['after_title']) ;

		?>

		<div class="bp-featured-groups-widget">
			<?php bp_fg_load_groups_list( $instance['view'], 'widget' ); ?>
		</div>

		<?php echo esc_html($args['after_widget']); ?>

		<?php
		bp_featured_groups()->end_loop();// mark loop end.
	}

	/**
	 * Update widget settings.
	 *
	 * @param array $new_instance new widget settings.
	 * @param array $old_instance old widget settings.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		$avatar_size = isset( $new_instance['avatar_size'] ) ? wp_strip_all_tags( $new_instance['avatar_size'] ) : '';
		$group_type = isset( $new_instance['group_type'] ) ? $new_instance['group_type'] : '';

		$view_options            = bp_fg_get_views_options();
		$view                    = key_exists( $new_instance['view'], $view_options ) ? $new_instance['view'] : 'list';
		$instance                = $old_instance;
		$instance['title']       = wp_strip_all_tags( $new_instance['title'] );
		$instance['max']         = wp_strip_all_tags( $new_instance['max'] );
		$instance['avatar_size'] = $avatar_size;
		$instance['view']        = $view;
		// not validating as admins are not supposed to be fooling around.
		$instance['group_type']        = $group_type;
		$instance['slide_item']         = wp_strip_all_tags( $new_instance['slide_item'] );
		$instance['slide_slideMargin']  = wp_strip_all_tags( $new_instance['slide_slideMargin'] );
		$instance['slide_mode']         = $new_instance['slide_mode']; // slide, fade.
		$instance['slide_speed']        = wp_strip_all_tags( $new_instance['slide_speed'] );
		$instance['slide_auto']         = $new_instance['slide_auto'];
		$instance['slide_pauseOnHover'] = $new_instance['slide_pauseOnHover'];
		$instance['slide_controls']     = $new_instance['slide_controls'];
		$instance['slide_loop']         = $new_instance['slide_loop'];

		return $instance;
	}

	/**
	 * Display widget settings form.
	 *
	 * @param Object $instance widget instance.
	 *
	 * @return void
	 */
	public function form( $instance ) {

		$defaults = array(
			'title'              => esc_attr__( 'Featured Groups', 'bp-featured-groups' ),
			'max'                => 5,
			'avatar_size'        => '',
			'view'               => 'list',
			'group_type'        => '',
			'slide_item'         => 1,
			'slide_slideMargin'  => 0,
			'slide_mode'         => 'slide', // slide, fade.
			'slide_speed'        => 400,
			'slide_auto'         => true,
			'slide_pauseOnHover' => false,
			'slide_controls'     => false,
			'slide_loop'         => true,
		);

		$instance             = wp_parse_args( (array) $instance, $defaults );
		$title                = wp_strip_all_tags( $instance['title'] );
		$max                  = wp_strip_all_tags( $instance['max'] );
		$avatar_size          = wp_strip_all_tags( $instance['avatar_size'] );
		$view                 = $instance['view'];
		$group_type           = $instance['group_type'];
		$view_options         = bp_fg_get_views_options();
		$member_types         = bp_groups_get_group_types( array(), 'objects' );
		$slide_item           = wp_strip_all_tags( $instance['slide_item'] );
		$slide_slide_margin   = wp_strip_all_tags( $instance['slide_slideMargin'] );
		$slide_mode           = $instance['slide_mode'];
		$slide_speed          = wp_strip_all_tags( $instance['slide_speed'] );
		$slide_auto           = $instance['slide_auto'];
		$slide_pause_on_hover = $instance['slide_pauseOnHover'];
		$slide_controls       = $instance['slide_controls'];
		$slide_loop           = $instance['slide_loop'];

		?>
		<p>
			<label>
				<?php esc_attr_e( 'Title:', 'bp-featured-groups' ); ?><br/>
				<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"
				       name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text"
				       value="<?php echo esc_attr( $title ); ?>" class="widefat"/>
			</label>
		</p>

		<p>
			<label>
				<?php esc_attr_e( 'Max. number of groups to show:', 'bp-featured-groups' ); ?>
				<input class="tiny-text" id="<?php echo esc_attr($this->get_field_id( 'max' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'max' )); ?>" type="text" value="<?php echo esc_attr( $max ); ?>" />
			</label>
		</p>

        <p>
            <label>
				<?php esc_attr_e( 'Avatar size:', 'bp-featured-groups' ); ?>
                <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id( 'avatar_size')); ?>" name="<?php echo esc_attr($this->get_field_name( 'avatar_size' )); ?>" type="text" value="<?php echo esc_attr( $avatar_size ); ?>" />
            </label>
        </p>

        <?php if ( ! empty( $group_types ) ) : ?>
            <p>
                <label>
					<?php esc_attr_e( 'Filter by Group Type', 'bp-featured-groups' ); ?>
                    <select id="<?php echo esc_attr($this->get_field_id( 'group_type' )); ?>" name ="<?php echo esc_attr($this->get_field_name( 'group_type' )); ?>">
                        <option value="" <?php selected( $group_type, "" ); ?> ><?php esc_attr_e( 'N/A', 'bp-featured-groups' );?> </option>
						<?php foreach ( $group_types as $group_type_obj ): ?>
                            <option value="<?php echo esc_attr( $group_type_obj->name ); ?>" <?php selected( $group_type, $group_type_obj->name ); ?> ><?php echo esc_html( $group_type_obj->labels['singular_name'] ); ?></option>
						<?php endforeach; ?>
                    </select>
                </label>
                <br/>
            </p>
        <?php endif;?>

        <p>
			<label>
				<?php esc_attr_e( 'Display View:', 'bp-featured-groups' ); ?>
				<select class="bpfg-widget-admin-widget-view-options" id="<?php echo esc_attr($this->get_field_id( 'view' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'view' )); ?>">
                    <?php foreach ( $view_options as $value => $option_name ) : ?>
						<option value="<?php echo esc_attr($value) ?>" <?php selected( $view, $value ) ?>><?php echo esc_attr($option_name); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</p>
        <?php $display_style = $view == 'slider' ? 'block': 'none';?>
        <div class="bpfg-widget-admin-widget-slide-options" style="display:<?php echo esc_attr($display_style);?>;">
            <h3><?php esc_attr_e( 'Slide Options', 'bp-featured-groups' );?></h3>
        <p>
            <label>
				<?php esc_attr_e( 'Slide items:', 'bp-featured-groups' ); ?>
                <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id( 'slide_items' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'slide_item' )); ?>" type="text" value="<?php echo esc_attr( $slide_item ); ?>" />
            </label>
        </p>

        <p>
            <label>
				<?php esc_attr_e( 'Slide margin:', 'bp-featured-groups' ); ?>
                <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id( 'slide_slideMargin' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'slide_slideMargin' )); ?>" type="text" value="<?php echo esc_attr( $slide_slide_margin ); ?>" />
            </label>
        </p>

        <p>
            <label>
				<?php esc_attr_e( 'Slide mode', 'bp-featured-groups' ); ?>
                <select id="<?php echo esc_attr($this->get_field_id( 'slide_mode' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'slide_mode' )); ?>">
                    <option value="slide" <?php selected( $slide_mode, 'slide' )?>><?php esc_attr_e( 'Slide', 'bp-featured-groups' ); ?></option>
                    <option value="fade" <?php selected( $slide_mode, 'fade' )?>><?php esc_attr_e( 'Fade', 'bp-featured-groups' ); ?></option>
                </select>
            </label>
        </p>

        <p>
            <label>
				<?php esc_attr_e( 'Slide speed:', 'bp-featured-groups' ); ?>
                <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id( 'slide_speed' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'slide_speed' )); ?>" type="text" value="<?php echo esc_attr( $slide_speed ); ?>" />
            </label>
        </p>

        <p>
            <label>
				<?php esc_attr_e( 'Slide auto:', 'bp-featured-groups' ); ?>
                <select id="<?php echo esc_attr($this->get_field_id( 'slide_auto' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'slide_auto' )); ?>">
                    <option value="1" <?php selected( $slide_auto, 1 )?>><?php esc_attr_e( 'True', 'bp-featured-groups' ) ?></option>
                    <option value="0" <?php selected( $slide_auto, 0 )?>><?php esc_attr_e( 'False', 'bp-featured-groups' ) ?></option>
                </select>
            </label>
        </p>

        <p>
            <label>
	            <?php esc_attr_e( 'Slide pause Hover:', 'bp-featured-groups' ); ?>
                <select id="<?php echo esc_attr($this->get_field_id( 'slide_pauseOnHover' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'slide_pauseOnHover' )); ?>">
                    <option value="1" <?php selected( $slide_pause_on_hover, 1 )?>><?php esc_attr_e( 'True', 'bp-featured-groups' ) ?></option>
                    <option value="0" <?php selected( $slide_pause_on_hover, 0 )?>><?php esc_attr_e( 'False', 'bp-featured-groups' ) ?></option>
                </select>
            </label>
        </p>

        <p>
            <label>
		        <?php esc_attr_e( 'Slide controls:', 'bp-featured-groups' ); ?>
                <select id="<?php echo esc_attr($this->get_field_id( 'slide_controls' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'slide_controls' )); ?>">
                    <option value="1" <?php selected( $slide_controls, 1 )?>><?php esc_attr_e( 'True', 'bp-featured-groups' ) ?></option>
                    <option value="0" <?php selected( $slide_controls, 0 )?>><?php esc_attr_e( 'False', 'bp-featured-groups' ) ?></option>
                </select>
            </label>
        </p>

        <p>
            <label>
				<?php esc_attr_e( 'Slide loop:', 'bp-featured-groups' ); ?>
                <select id="<?php echo esc_attr($this->get_field_id( 'slide_loop' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'slide_loop' )); ?>">
                    <option value="1" <?php selected( $slide_loop, 1 )?>><?php esc_attr_e( 'True', 'bp-featured-groups' ) ?></option>
                    <option value="0" <?php selected( $slide_loop, 0 )?>><?php esc_attr_e( 'False', 'bp-featured-groups' ) ?></option>
                </select>
            </label>
        </p>
</div>
		<?php
	}
}

/**
 * Register Widget
 */
function bp_featured_groups_register_widgets() {
	register_widget( 'BP_Featured_Groups_List_Widget' );
}

add_action( 'bp_widgets_init', 'bp_featured_groups_register_widgets' );
