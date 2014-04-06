<?php function testimonial_shortcode( $atts ){ ?>


<?php	$output ='<div class="testimonial-container">'; ?>

<?php 

		
	$args = array(
		'post_type' 		=> 'simple_testimonial',
		'post_status' 		=> 'publish',
		'posts_per_page'	=> isset( $atts['per_page'] ) ? $atts['per_page'] : 2,
		'order'				=> 'DESC'
	);	
	$wp_query = new WP_Query($args);
?>
		
        <?php $i = 1; while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
		
		ob_start();?>
        
        <?php $last = $wp_query->post_count; ?>
        
       <?php if($i%2==1){?>
       <?php $output .= '<div class="c-wrapper'; if($i==$last) $output .=' c-wrapper-last'; $output .='">'; ?>
  	   <?php } ?>
       
         
            <?php $output .= '<div class="client-box '; ?> <?php if($i%2==1) $output .= 'right-bdr'; $output .= '">'; ?>
			
                <?php $output .= '<div class="client-img">' ?>
                    <?php if ( has_post_thumbnail() ) {
                             $output .= get_the_post_thumbnail(get_the_ID());
                          } 
                    ?>
                <?php $output .= '</div>'; ?>
                <?php $output .= '<div class="client-saying">'. nl2br(get_the_content()). '</div>';
                
                $output .= '<div class="meta-wrapper">';
                    $output .= '<div class="client-name">'. get_the_title(). '</div>';
                    $output .= '<div class="client-designation">'.$client_designation = get_post_meta(get_the_ID(), '_client_designation', true)." -". '</div>';
                    $client_company = get_post_meta(get_the_ID(), '_client_company', true);
                    $client_url = get_post_meta(get_the_ID(), '_company_url', true); 
                    $output .= '<div class="client-company">'; $output .= '<a href="'.$client_url.'" target="_blank">'.$client_company.'</a> </div>
                </div>
                
            </div>'; ?>
            
         <?php if($i%2==0) {?>
       	 <?php $output .= '</div>'; ?>
         <?php } ?>  


        <?php ob_end_clean();
		      $i++; endwhile; ?>
        
        <?php echo $output .= '</div>'; ?>


<?php 	
}

add_shortcode("simple_testimonials", "testimonial_shortcode");
?>