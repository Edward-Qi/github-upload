<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package sparkling
 */
?>
		</div><!-- close .row -->
	</div><!-- close .container -->
</div><!-- close .site-content -->
	<div class="footer-bawah col-md-12">
	<div id="footer-area">
		<div class="container footer-inner">
			<div class="row">
				<?php get_sidebar( 'footer' ); ?>
			</div>
		</div>
	</div>
	</div>

</div><!-- #page -->
<?php wp_footer(); ?>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/assets/js/custom/popUpBanners.js"></script>
<script type="text/javascript">
	initPopUpBanner("<?php echo get_template_directory_uri(); ?>");
</script>
</body>
</html>
