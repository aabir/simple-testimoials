<?php
/*
Plugin Name: Simple Testimonial
Description: Display testimonials with client name, designation and web address. 
Author: Shible Noman 
Version: 1.0
*/

// Ensure WordPress has been bootstrapped
if( !defined( 'ABSPATH' ) )
	exit;

$path = trailingslashit( dirname( __FILE__ ) );

// Ensure our class dependencies class has been defined

if( !class_exists( 'Simple_Testimonial_Plugin' ) )
require_once( $path . 'class.simple-testimonials.php' );

if( !class_exists( 'Simple_Testimonial_Widget_Class' ) )
require_once( $path . 'class.simple-testimonials-widget.php' );

require_once($path . 'shortcode.php');

// Boot Simple Testimonials
new Simple_Testimonial_Plugin();

?>
