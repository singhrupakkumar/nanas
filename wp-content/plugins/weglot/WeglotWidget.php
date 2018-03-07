<?php

class WeglotWidget extends WP_Widget {

	public function __construct() {
		parent::__construct(false, $name = __('Weglot Translate', 'weglot') );
    }

    public function WeglotWidget() {
		parent::__construct(false, $name = __('Weglot Translate', 'weglot') );
    }

    public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		return $instance;
	}

    function widget($args, $instance) {

		$title = apply_filters( 'widget_title', $instance['title'] );



		$tt = ( ! empty( $title ) ) ? $args['before_title'] . $title . $args['after_title']:"";

		$button = Weglot::Instance()->returnWidgetCode();
		echo $args['before_widget'].$tt.$button.$args['after_widget'];
    }

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = "";
		}
		// Widget admin form
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'weglot'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
	}
}