<?php
get_header();

$doctors_url = get_post_type_archive_link( 'doctors' );
?>

<main id="content" class="container">
	<section class="hero">
		<div class="hero-card">
			<p class="hero-kicker"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
			<h1 class="hero-title"><?php esc_html_e( 'Find your doctor in minutes', 'rostest' ); ?></h1>
			<p class="hero-subtitle"><?php esc_html_e( 'Browse specialists, compare prices and ratings, and book the right fit.', 'rostest' ); ?></p>
			<div class="hero-actions">
				<?php if ( $doctors_url ) : ?>
					<a class="btn btn-primary" href="<?php echo esc_url( $doctors_url ); ?>"><?php esc_html_e( 'Open doctors archive', 'rostest' ); ?></a>
				<?php endif; ?>
			</div>
		</div>

		<div class="hero-side card">
			<h2 class="section-title" style="margin-top:0;"><?php esc_html_e( 'Latest doctors', 'rostest' ); ?></h2>
			<?php
			$q = new WP_Query(
				array(
					'post_type'      => 'doctors',
					'posts_per_page' => 3,
					'no_found_rows'  => true,
				)
			);
			?>
			<?php if ( $q->have_posts() ) : ?>
				<div class="mini-list">
					<?php while ( $q->have_posts() ) : ?>
						<?php
						$q->the_post();
						$post_id = get_the_ID();
						$fields  = rostest_get_doctor_fields( $post_id );
						$terms   = rostest_get_doctor_terms_preview( $post_id );
						?>
						<a class="mini-item" href="<?php the_permalink(); ?>">
							<span class="mini-title"><?php the_title(); ?></span>
							<span class="mini-sub">
								<?php
								$bits = array();
								if ( ! empty( $terms['specializations'] ) ) {
									$bits[] = implode( ', ', array_map( 'esc_html', $terms['specializations'] ) );
								}
								if ( $fields['rating'] > 0 ) {
									$bits[] = esc_html__( 'Rating:', 'rostest' ) . ' ' . esc_html( number_format_i18n( $fields['rating'], 1 ) );
								}
								echo $bits ? implode( ' • ', $bits ) : esc_html__( 'View profile', 'rostest' );
								?>
							</span>
						</a>
					<?php endwhile; ?>
				</div>
			<?php else : ?>
				<p style="margin:0;"><?php esc_html_e( 'No doctors yet. Add a few to see them here.', 'rostest' ); ?></p>
			<?php endif; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</section>

	<?php if ( $doctors_url ) : ?>
		<section class="card" style="margin-top:18px;">
			<h2 class="section-title" style="margin-top:0;"><?php esc_html_e( 'Quick links', 'rostest' ); ?></h2>
			<div class="chips">
				<a class="chip" href="<?php echo esc_url( $doctors_url ); ?>"><?php esc_html_e( 'All doctors', 'rostest' ); ?></a>
				<a class="chip" href="<?php echo esc_url( add_query_arg( array( 'sort' => 'rating' ), $doctors_url ) ); ?>"><?php esc_html_e( 'Top rated', 'rostest' ); ?></a>
				<a class="chip" href="<?php echo esc_url( add_query_arg( array( 'sort' => 'price' ), $doctors_url ) ); ?>"><?php esc_html_e( 'Lowest price', 'rostest' ); ?></a>
			</div>
		</section>
	<?php endif; ?>
</main>

<?php
get_footer();
