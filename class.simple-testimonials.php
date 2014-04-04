<?php

final class simple_testimonial_plugin {

	public function __construct(){
		$plugin_name = 'simple_testimonial_plugin';
		
		add_action( 'init', array( __CLASS__, "simple_testimonials_cpt" ) );
		
		add_action( 'manage_posts_custom_column', array( __CLASS__, 'testimonial_column' ), 10, 2 ); 
		add_filter( 'manage_edit-simple_testimonial_columns', array( __CLASS__, 'testimonial_columns' ), 5 ); //give CPT name
		
		add_action("add_meta_boxes", array( __CLASS__, 'simple_testimonial_add_custom_box' ));
		add_action("save_post", array( __CLASS__, 'simple_testimonial_save_postdata' ));
		
		add_action('widgets_init', create_function('', 'return register_widget("Simple_Testimonial_Widget_Class");'));
		
	}
	
	public static function install () {
		update_option( 'ct_activated', time() );
	}
	
	public static function testimonial_columns ( $columns ) {

		unset( $columns['date'] );
		$columns['client_designation'] = 'Designation';
		$columns['client_company'] = 'Company';
		$columns['testimonial_thumbnail'] = 'Thumbnail';
		$columns['date'] = 'Date';

		return $columns;

	}
	
	public static function testimonial_column ( $column, $post_id ) {

		global $post;
		
		if( $post->post_type != 'simple_testimonial' )
			return;

		switch( $column ) {

			case 'testimonial_thumbnail':

				if( has_post_thumbnail( $post->ID ) )
					echo wp_get_attachment_image( get_post_thumbnail_id( $post->ID ), array( 64, 64 ) );
				else
					echo 'No thumbnail supplied';

				break;
				
			case 'client_designation':	
				$client_designation = get_post_meta($post->ID, '_client_designation', true);
				echo $client_designation == '' ? '<em>N/A</em>' : $client_designation;
				break;
			
			case 'client_company':	
				$client_company = get_post_meta($post->ID, '_client_company', true);	
				echo $client_company = '' ? '<em>N/A</em>' : $client_company;
				break;

			default:

				$value = get_post_meta( $post->ID, $column, true );
				echo $value == '' ? '<em>N/A</em>' : $value;
		}
		
	}

	public function simple_testimonials_cpt() {
		
		$labels = array(
			'name'               => _x( 'Testimonial', 'post type general name' ),
			'singular_name'      => _x( 'Testimonial', 'post type singular name' ),
			'add_new'            => _x( 'Add New Testimonial', 'book' ),
			'add_new_item'       => __( 'Add New Testimonial' ),
			'edit_item'          => __( 'Edit Testimonial' ),
			'new_item'           => __( 'New Testimonial' ),
			'all_items'          => __( 'All Testimonial' ),
			'view_item'          => __( 'View Testimonial' ),
			'search_items'       => __( 'Search Testimonial' ),
			'not_found'          => __( 'Not Found' ),
			'not_found_in_trash' => __( 'Not Found Testimonial in the Trash' ), 
			'parent_item_colon'  => '',
			'menu_name'          => 'Testimonial'
		);
		
		$args = array(
			'labels' => $labels,
			'description'   => 'Holds Testimonial and Testimonial specific data',
			'public'        => true,
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'thumbnail'),
			'has_archive'   => true
		);
		register_post_type( 'simple_testimonial', $args );	
	}
	
	public function simple_testimonial_add_custom_box(){
		global $plugin_name;
		add_meta_box( 
			"options",
			__("Testimonial Client description", $plugin_name),
			array( __CLASS__, 'simple_testimonial_theme_inner_custom_box' ),
			"simple_testimonial",
			"normal",
			"high"
		);
	}
	
	public function simple_testimonial_theme_inner_custom_box($post){
	
		wp_nonce_field(plugin_basename( __FILE__ ), $plugin_name . "_noncename");
	
		echo '
		<table>
			<tr>
				<td>
					<label for="client_designation">' . __('Client Designation', $plugin_name) . ':</label>
				</td>
				<td>
					<input class="regular-text" type="text" id="client_designation" name="client_designation" value="' . esc_attr(get_post_meta($post->ID,  "_client_designation", true)) . '" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="client_company">' . __('Client Company', $plugin_name) . ':</label>
				</td>
				<td>
					<input class="regular-text" type="text" id="client_company" name="client_company" value="' . esc_attr(get_post_meta($post->ID, "_client_company", true)) . '" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="company_url">' .__('Client Company Web Address', $plugin_name) . ':</label> 
				</td>
				<td>
					<input class="regular-text" type="text" id="company_url" name="company_url" value="' . esc_attr(get_post_meta($post->ID, "_company_url", true)) . '" />
				</td>
				
			</tr>';
			
			echo '</table>';
	}
	
	
	public function simple_testimonial_save_postdata($post_id){
		global $plugin_name;
		
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
			return;
	
		if (!wp_verify_nonce($_POST[$plugin_name . '_noncename'], plugin_basename( __FILE__ )))
			return;
	
		if(!current_user_can('edit_post', $post_id))
			return;
			
		update_post_meta($post_id, "_client_designation", $_POST["client_designation"]);
		update_post_meta($post_id, "_client_company", $_POST["client_company"]);
		update_post_meta($post_id, "_company_url", $_POST["company_url"]);
	}	
}
?>