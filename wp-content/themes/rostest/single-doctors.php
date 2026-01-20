<?php
get_header();

while ( have_posts() ) :
	the_post();

	$post_id = get_the_ID();

	$fields = rostest_get_doctor_fields( $post_id );

	$experience = $fields['experience'];
	$price_from = $fields['price_from'];
	$rating     = $fields['rating'];

	$specializations = get_the_terms( $post_id, 'specialization' );
	$cities          = get_the_terms( $post_id, 'city' );

	$rating_width = ( $rating / 5 ) * 100;
	$price_number = number_format_i18n( $price_from, 0 );
	$price_text   = $price_from > 0
		? sprintf( __( 'from %s ₽', 'rostest' ), $price_number )
		: __( '—', 'rostest' );
	?>

	<main id="content" class="container">
		<article <?php post_class( 'card' ); ?>>
			<header class="doctor-header">
				<div class="doctor-photo">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'medium_large' ); ?>
					<?php else : ?>
						<img alt="" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='800' height='800'%3E%3Crect width='100%25' height='100%25' fill='%23e2e8f0'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='%2364758b' font-size='28' font-family='Arial'%3ENo photo%3C/text%3E%3C/svg%3E">
					<?php endif; ?>
				</div>

				<div>
					<h1 class="doctor-title"><?php the_title(); ?></h1>

					<p class="doctor-subtitle">
						<?php
						$parts = array();

						if ( ! is_wp_error( $specializations ) && ! empty( $specializations ) ) {
							$names   = wp_list_pluck( $specializations, 'name' );
							$parts[] = esc_html( implode( ', ', array_slice( $names, 0, 2 ) ) );
						}

						if ( ! is_wp_error( $cities ) && ! empty( $cities ) ) {
							$names   = wp_list_pluck( $cities, 'name' );
							$parts[] = esc_html( implode( ', ', array_slice( $names, 0, 1 ) ) );
						}

						echo $parts ? implode( ' • ', $parts ) : esc_html__( 'Doctor profile', 'rostest' );
						?>
					</p>

					<div class="doctor-meta">
						<div class="meta-item">
							<span class="meta-label"><?php esc_html_e( 'Experience', 'rostest' ); ?></span>
							<span class="meta-value">
								<?php echo esc_html( $experience ); ?>
								<?php esc_html_e( 'years', 'rostest' ); ?>
							</span>
						</div>
						<div class="meta-item">
							<span class="meta-label"><?php esc_html_e( 'Price', 'rostest' ); ?></span>
							<span class="meta-value">
								<?php echo esc_html( $price_text ); ?>
							</span>
						</div>
						<div class="meta-item">
							<span class="meta-label"><?php esc_html_e( 'Rating', 'rostest' ); ?></span>
							<span class="meta-value rating">
								<span class="stars" style="--rating-width: <?php echo esc_attr( $rating_width ); ?>%;"></span>
								<span><?php echo esc_html( number_format_i18n( $rating, 1 ) ); ?></span>
							</span>
						</div>
					</div>

				</div>
			</header>

			<div class="doctor-content">
				<?php if ( has_excerpt() ) : ?>
					<p><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>

				<?php the_content(); ?>
			</div>
		</article>
	</main>

	<?php
endwhile;

get_footer();
