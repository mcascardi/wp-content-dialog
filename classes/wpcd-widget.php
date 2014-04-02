<?php

if ( ! class_exists( 'WPCD_Widget' ) ) {
  
  /**
   * Handles plugin settings and user profile meta fields
   */
  class WPCD_Widget extends WP_Widget {
    protected $settings;
    
    function __construct()
    {
      parent::__construct(
			  'wpcd_widget', // Base ID
			  'ContentDialog', // Name
			  array( 'description' => 'A Content Dialog Widget') // Args
			  );
    }
    


    /**
     * Register widget with WordPress.
     */
    function register_hook_callbacks() {
      add_action( 'widgets_init', function(){
	  register_widget( 'WPCD_Widget' );
	});
    }
    
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
      $title = apply_filters( 'widget_title', $instance['title'] );

      echo $args['before_widget'];
      if ( ! empty( $title ) )
	echo $args['before_title'] . $title . $args['after_title'];
      echo WPCD_Core::getCtaCode(get_option('wpcd_settings'));
      echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
      if ( isset( $instance[ 'title' ] ) ) {
	$title = $instance[ 'title' ];
      }
      else {
	$title = 'Content Dialog';
      }
      
      $theTitle = $this->get_field_id('title');
      $name = $this->get_field_name( 'title' );
      $value = esc_attr( $title );
      $template = <<<HTML
 <p>
   <label for="{$theTitle}">Title:</label> 
   <input class="widefat" id="{$theTitle}" name="{$name}" type="text" value="{$value}">
 </p>
 
HTML;
      echo $template;

    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
      $instance = array();
      $instance['title'] = ( 
			    (! empty( $new_instance['title'] ) ) ? 
			    strip_tags( $new_instance['title'] ) : ''
			     );
      
      return $instance;
    }
	
  }
}
