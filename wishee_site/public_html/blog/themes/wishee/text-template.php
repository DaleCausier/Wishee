<?php
/**
 * The template for displaying all pages.
 *
 * Template Name: Textual Content
 * Template Description: Textual
 *
 * @package wishee
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
        	<div class = "inside">
            	<div class = "left">
            		<h2><?php the_title('');?></h2>
                    <?php the_field('main_content')?>
                    
                   
           
                </div>
                <div class = "right">
                <?php get_sidebar();?>
                </div>
                
                 <div id ="container">
                 <h3>Meet the Wishee Team</h3>
            			<?php if( have_rows('squares') ): while ( have_rows('squares') ) : the_row(); ?>
            				<div class = "background-container">
                    			<div id="thumbnail">
                					<?php if(get_sub_field('thumbnail')) echo get_repeater_image_with_alt(get_sub_field('thumbnail')); ?>
                				</div><!--#thumbnail-->
                				<div class = "Bio">
                    				<h4><?php the_sub_field('name'); ?></h4>
                        				<h5><?php the_sub_field('position'); ?></h5>
                        				<p><?php the_sub_field('bio'); ?></p>
                        		</div><!--#listing-right-->
				</div><!--#background-container-->
			<?php endwhile; endif; ?> 
           </div>
            
            </div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php

get_footer();
