<?php 

final class Simple_Testimonial_Widget_Class extends WP_Widget {
		
		function __construct() {
			
			$widget_ops = array('classname' => 'shop_cpt_widget_class', 'description' => __('Simple Testimonial Slide Widget', 'simple_testimonial_widget'));
			$control_ops = array('width' => 250, 'height' => 250);
			parent::WP_Widget(false, $name = __('Simple Testimonial', 'simple_testimonial_widget'), $widget_ops, $control_ops );
		}
	
		function form($instance) {
	
			// Check values
			if( $instance) {
				 $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
				 $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
			} 
			?>
	
			<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'simple_testimonial_widget'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'simple_testimonial_widget' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	
			
			<?php
		}
		
		function update($new_instance, $old_instance) {
			  $instance = $old_instance;
	
			  $instance['title'] = strip_tags($new_instance['title']);
			  $instance['number'] = (int) $new_instance['number'];
			 return $instance;
		}
		
		function widget($args, $instance) {
		
				$plugin_name = 'simple_testimonial_plugin';
				
			  	wp_enqueue_script( 'jquery');

				if(!is_admin() ){
				
					wp_enqueue_style( 'flexslider', plugin_dir_url( __FILE__ ) . 'css/flexslider.css' );
					wp_enqueue_script( 'flexslider', plugin_dir_url( __FILE__ ). 'js/jquery.flexslider-min.js');
					wp_enqueue_script( 'custom', plugin_dir_url( __FILE__ ). 'js/init.js');
				}?>
				
			<?php	
			   extract( $args );
			   // these are the widget options
			   $title = apply_filters('widget_title', $instance['title']);
	
			   echo $before_widget;
			   // Display the widget
			   echo '<div class="widget-slide">';
	
			   // Check if title is set
			   if ( $title ) {
				  echo $before_title . $title . $after_title;
			   }
			   
			   $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
				if ( ! $number )
					$number = 5;
	
			   ?>
			   <ul class="slides">
				<?php 
				$args='&post_type=simple_testimonial&order=DESC&posts_per_page='.$number.'';								
				$cust_loop = new WP_Query($args); 
				if ($cust_loop->have_posts()) : while ($cust_loop->have_posts()) : $cust_loop->the_post(); 
				
				?>
					
					
				<li>
                        <div class="testimonial-content">
                            <span class="quote"></span>
                            <?php echo get_the_content(); ?>
                            <div class="testimonial-arrow"></div>
                        </div>
                    <?php if ( has_post_thumbnail() ) { ?><?php the_post_thumbnail ('thumbnail', array('class' => 'aligncenter')); ?> <?php } ?>
                    <div class="meta-wrapper">
                        <div class="client-name"> <?php echo get_the_title(); ?> </div>
                        <div class="client-designation"> <?php echo $client_designation = get_post_meta(get_the_ID(), '_client_designation', true); echo " -"; ?> </div>
                        <?php $client_company = get_post_meta(get_the_ID(), '_client_company', true); ?>
                        <?php $client_url = get_post_meta(get_the_ID(), '_company_url', true); ?>
                        
                        <span class="client-company"> <a href="<?php echo $client_url; ?>" target="_blank"> <?php echo $client_company; ?></a> </span>
                	</div>
                    
					 
					 
                     <!--<?php echo get_the_title(); ?>
                     
					 <?php echo get_post_meta(get_the_ID(), '_client_designation', true); ?> -->
				</li>
					
				<?php  
				endwhile; endif; wp_reset_query(); ?> 
			 
			   <?php 
			   
			   echo '</ul> </div>';
			   echo $after_widget;
		}
		
	}