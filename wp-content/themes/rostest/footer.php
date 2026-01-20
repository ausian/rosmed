<footer class="site-footer">
	<div class="container footer-inner">
		<div class="footer-left">
			<strong><?php echo esc_html( get_bloginfo( 'name' ) ); ?></strong>
			<span class="footer-sep">•</span>
			<span><?php echo esc_html( gmdate( 'Y' ) ); ?></span>
		</div>
		<div class="footer-right">
			<?php
			$doctors_url = get_post_type_archive_link( 'doctors' );
			if ( $doctors_url ) :
				?>
				<a href="<?php echo esc_url( $doctors_url ); ?>"><?php esc_html_e( 'Doctors archive', 'rostest' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</footer>

<?php
wp_footer();
?>
</div>
</body>
</html>
