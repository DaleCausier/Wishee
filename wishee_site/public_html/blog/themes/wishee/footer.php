<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package wishee
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class = "outside">
        	<div class = "purple">
                <div class ="inside footer">
                    <div id = "footer-left">
                        <p>Wishee is currently in development and is available under an invitation only basis.</p>
                        <p>All Rights Reserved - Wishee 2016.</p>
                        <p>Wishee has been developed by BuizKitz.</p> 
                    </div>
                    <div id = "footer-right">
                    <?php $image = get_field('footer_logo','options'); if( !empty($image) ): ?>
						<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
				    <?php endif; ?>
                    </div>
               </div>
           </div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
<script>jQuery('.background-container').matchHeight();</script>

</body>
</html>
