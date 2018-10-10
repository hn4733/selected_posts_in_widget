<?php
/*
Plugin Name: Selected Posts in Widget
Plugin URI: https://timeandupdate.com/selected_posts_in_widget/
Description: Add Selected Posts in your Widget Area.
Version: 1.0
Author: Time and Update
Author URI: https://timeandupdate.com/
License: TCIY
*/
// The widget class

$select_array = [
	'select_num' => ['select','select2','select3','select4','select5','select6','select7','select8','select9','select10','select11','select12','select13','select14','select15'],
	'select_post' => ['1st','2nd','3rd','4th','5th','6th','7th','8th','9th','10th','11th','12th','13th','14th','15th']
];
global $select_array;

class Selected_Posts_in_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'selected_posts_in_widget',
			__( 'Selected Posts Widget', 'text_domain' ),
			array(
				'customize_selective_refresh' => true,
			)
		);
	}

	public function form( $instance ) {
		$defaults = array(
			'title'    => 'Editors Choices',
			'select'   => '',
			'select2'   => '02',
			'select3'   => '03',
			'select4'   => '04',
			'select5'   => '05',
			'select6'   => '06',
			'select7'   => '07',
			'select8'   => '08',
			'select9'   => '09',
			'select10'   => '10',
			'select11'   => '11',
			'select12'   => '12',
			'select13'   => '13',
			'select14'   => '14',
			'select15'   => '15',
		);

		extract( wp_parse_args( ( array ) $instance, $defaults ) );

		// Widget Title
		$html = '<p>';
			$html .= '<label for="'.esc_attr( $this->get_field_id( 'title' ) ).'">'._e( 'Widget Title', 'text_domain' ).'</label>';
			$html .= '<input class="widefat" id="'.esc_attr( $this->get_field_id( 'title' ) ).'" name="'.esc_attr( $this->get_field_name( 'title' ) ).'" type="text" value="'.esc_attr( $title ).'"/>';
		$html .= '</p>';
		echo $html;

		// Dropdown
		$options = array();
		// query for your post type
		$post_type_query  = new WP_Query(  
			array (  
				'posts_per_page' => -1  
			)  
		);   
		// we need the array of posts
		$posts_array = $post_type_query->posts;   
		// create a list with needed information
		// the key equals the ID, the value is the post_title
		$options = wp_list_pluck( $posts_array, 'post_title', 'ID' );

		global $select_array;
			
		foreach (array_combine($select_array['select_num'], $select_array['select_post']) as $select_num => $select_post) {
			$select_selection = '<p>';
				$select_selection .= '<label for="'.$this->get_field_id( $select_num ).'">'._e( "Select ".$select_post." Post", "text_domain" ).'</label>';
				$select_selection .= '<select name="'.$this->get_field_name( $select_num ).'" id="'.$this->get_field_id( $select_num ).'" class="widefat">';
				
					foreach ( $options as $key => $name ) {
						$select_selection .= '<option value="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" '. selected( $select, $key, false ) . '>'. $name . '</option>';
					}
				$select_selection .= '</select>';	
			$select_selection .= '</p>';

			echo $select_selection;
		}
	}

	public function update( $new_instance, $old_instance ) {
		global $select_array;
		
		$instance = $old_instance;
		$instance['title'] = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
		foreach ($select_array['select_num'] as $select_num_update) {
			$instance[$select_num_update] = isset( $new_instance[$select_num_update] ) ? wp_strip_all_tags( $new_instance[$select_num_update] ) : '';
		}
		return $instance;
	}
	public function widget( $args, $instance ) {
		extract( $args );
		$title = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';

		global $select_array;

		foreach ($select_array['select_num'] as $select_num_widget) {
 			if (isset($instance[$select_num_widget]) && $instance[$select_num_widget]) {
 				$widget[] = $instance[$select_num_widget];
 			}
		}

		// WordPress core before_widget hook (always include )
		echo '
			'. $before_widget;
			// Display the widget
			echo '
				<div class="widget-text wp_widget_plugin_box">
				';
					if ( $title ) {
						echo '	'.$before_title . $title . $after_title;
					}
					echo'
					<ul>';
					foreach ($widget as $select_num_widget2) {
						if ( $select_num_widget2 ) {	
							echo '<li><a href="'. post_permalink($select_num_widget2) .'">'. get_the_title($select_num_widget2) .'</a></li>';
						}
					}
					echo '<ul>';
				echo '</div>';

			// WordPress core after_widget hook (always include )
			echo '
			' . $after_widget . '
		';
	}
}

function register_selected_posts_in_widget() {
	register_widget( 'Selected_Posts_in_Widget' );
}
add_action( 'widgets_init', 'register_selected_posts_in_widget' );
?>
