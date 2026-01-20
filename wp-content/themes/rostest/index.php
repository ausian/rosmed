<?php
get_header();
?>

<main id="content" class="site">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<article <?php post_class(); ?>>
				<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
				<?php the_excerpt(); ?>
			</article>
		<?php endwhile; ?>
	<?php else : ?>
		<p><?php esc_html_e( 'No posts found.', 'rostest' ); ?></p>
	<?php endif; ?>
</main>

<?php
get_footer();
