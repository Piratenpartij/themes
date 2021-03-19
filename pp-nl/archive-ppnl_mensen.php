<?php
/**
 * Piratenpartij Nederland.
 *
 * This file contains changes to the default ppnl_mensen archive in Piratenpartij Nederland Theme.
 *
 * @package Piratenpartij Nederland
 * @author  Forsite 
 * @license GPL-2.0+
 * @link    https://piratenpartij.nl/
 */

add_action( 'pre_get_posts', 'ppnl_set_posts_per_page_for_mensen' );
/*
 * Set Post per Page to infinite  
 */
function ppnl_set_posts_per_page_for_mensen( $query ) {
  if ( !is_admin() && $query->is_main_query() ) {
    $query->set( 'posts_per_page', '-1' );
  }
}


//* Remove the post info function
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'ppnl_mensen_archief_content' );
/**
 * Filtering the content and replacing with taxonomy term list.
 * 
 * @param   string  $content   The content
 * @return  string             The modified content
 */
function ppnl_mensen_archief_content() {
	
	$custom_terms = get_terms( 'afdelingen' );

	foreach( $custom_terms as $custom_term ) {
	    wp_reset_query();
	    $args = array( 'post_type' => 'ppnl_mensen',
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'afdelingen',
	                'field'    => 'slug',
	                'terms'    => $custom_term->slug,
	            ),
	        ),
	     );

	     $loop = new WP_Query( $args );

	     
	     if( $loop->have_posts() ) {

	     	echo '<h2 class="afdeling">'.$custom_term->name.'</h2>';

	        while( $loop->have_posts() ) : $loop->the_post();
	        	$permalink = get_the_permalink();
	     		$title = get_the_title();
	     		
	        	echo '<article class="'. join( ' ', get_post_class( 'piraten' ) ) .'">';
        			echo '<div class="piraat one-half first">'; 
        				echo '<a href="' . $permalink . '">';
        					the_post_thumbnail( 'piraat' );
    					echo '</a>';
        			echo '</div>'; 
	            	echo '<div class="entry-content one-half">';
	            		echo  '<h3 class="entry-title" itemprop="headline"><a href="' .$permalink . '" rel="bookmark">' . $title . '</a></h3>';
	            		if ( $ppnl_functie = get_post_meta( get_the_ID(), '_ppnl_functie', true ) ) {
							// if we're good to go print!
							echo '<h4>' . $ppnl_functie . '</h4>';
							
						}
						if ( $ppnl_shortbio = get_post_meta( get_the_ID(), '_ppnl_shortbio', true ) ) {
							// if we're good to go print!
							echo '' . $ppnl_shortbio . '';
							
						}
						
	            	echo '</div>'; 
	            echo '</article>'; 
	        endwhile;
	     }
	}
}

genesis();