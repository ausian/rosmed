<?php
get_header();

$archive_url = get_post_type_archive_link( 'doctors' );

$current_specialization = isset( $_GET['specialization'] ) ? sanitize_text_field( wp_unslash( $_GET['specialization'] ) ) : '';
$current_city           = isset( $_GET['city'] ) ? sanitize_text_field( wp_unslash( $_GET['city'] ) ) : '';
$current_sort           = isset( $_GET['sort'] ) ? sanitize_text_field( wp_unslash( $_GET['sort'] ) ) : '';

$specializations = get_terms(
	array(
		'taxonomy'   => 'specialization',
		'hide_empty' => false,
	)
);

$cities = get_terms(
	array(
		'taxonomy'   => 'city',
		'hide_empty' => false,
	)
);
?>

<main id="content" class="container">
	<header class="page-head">
		<h1 class="page-title"><?php post_type_archive_title(); ?></h1>
		<p class="page-subtitle"><?php esc_html_e( 'Browse doctors and compare experience, price and rating.', 'rostest' ); ?></p>
	</header>

	<form class="filters card" method="get" action="<?php echo esc_url( $archive_url ?: home_url( '/' ) ); ?>">
		<div class="filters-grid">
			<div class="filters-field">
				<label class="filters-label" for="filter-specialization"><?php esc_html_e( 'Specialization', 'rostest' ); ?></label>
				<select id="filter-specialization" name="specialization">
					<option value=""><?php esc_html_e( 'Any', 'rostest' ); ?></option>
					<?php if ( ! is_wp_error( $specializations ) ) : ?>
						<?php foreach ( $specializations as $term ) : ?>
							<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $current_specialization, $term->slug ); ?>>
								<?php echo esc_html( $term->name ); ?>
							</option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>

			<div class="filters-field">
				<label class="filters-label" for="filter-city"><?php esc_html_e( 'City', 'rostest' ); ?></label>
				<select id="filter-city" name="city">
					<option value=""><?php esc_html_e( 'Any', 'rostest' ); ?></option>
					<?php if ( ! is_wp_error( $cities ) ) : ?>
						<?php foreach ( $cities as $term ) : ?>
							<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $current_city, $term->slug ); ?>>
								<?php echo esc_html( $term->name ); ?>
							</option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>

			<div class="filters-field">
				<label class="filters-label" for="filter-sort"><?php esc_html_e( 'Sort', 'rostest' ); ?></label>
				<select id="filter-sort" name="sort">
					<option value=""><?php esc_html_e( 'Default', 'rostest' ); ?></option>
					<option value="rating" <?php selected( $current_sort, 'rating' ); ?>><?php esc_html_e( 'By rating (desc)', 'rostest' ); ?></option>
					<option value="price" <?php selected( $current_sort, 'price' ); ?>><?php esc_html_e( 'By price (asc)', 'rostest' ); ?></option>
					<option value="experience" <?php selected( $current_sort, 'experience' ); ?>><?php esc_html_e( 'By experience (desc)', 'rostest' ); ?></option>
				</select>
			</div>

			<div class="filters-actions">
				<button class="btn btn-primary" type="submit"><?php esc_html_e( 'Apply', 'rostest' ); ?></button>
				<?php if ( $current_specialization || $current_city || $current_sort ) : ?>
					<a class="btn" href="<?php echo esc_url( $archive_url ?: home_url( '/' ) ); ?>"><?php esc_html_e( 'Reset', 'rostest' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</form>

	<?php if ( have_posts() ) : ?>
		<div class="grid">
			<?php while ( have_posts() ) : ?>
				<?php
				the_post();
				$post_id = get_the_ID();
				$fields  = rostest_get_doctor_fields( $post_id );
				$terms   = rostest_get_doctor_terms_preview( $post_id );
				$rating_width = ( $fields['rating'] / 5 ) * 100;
				$price_number = number_format_i18n( $fields['price_from'], 0 );
				$price_text   = $fields['price_from'] > 0
					? sprintf( __( 'from %s ₽', 'rostest' ), $price_number )
					: __( '—', 'rostest' );
				?>

				<article <?php post_class( 'doctor-card' ); ?>>
					<a class="doctor-card__link" href="<?php the_permalink(); ?>">
						<div class="doctor-card__media">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'medium' ); ?>
							<?php else : ?>
								<img alt="" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='800' height='500'%3E%3Crect width='100%25' height='100%25' fill='%23e2e8f0'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='%2364758b' font-size='24' font-family='Arial'%3ENo photo%3C/text%3E%3C/svg%3E">
							<?php endif; ?>
						</div>

						<div class="doctor-card__body">
							<h2 class="doctor-card__title"><?php the_title(); ?></h2>

							<?php if ( ! empty( $terms['specializations'] ) || ! empty( $terms['cities'] ) ) : ?>
								<p class="doctor-card__subtitle">
									<?php
									$parts = array();
									if ( ! empty( $terms['specializations'] ) ) {
										$parts[] = esc_html( implode( ', ', $terms['specializations'] ) );
									}
									if ( ! empty( $terms['cities'] ) ) {
										$parts[] = esc_html( implode( ', ', $terms['cities'] ) );
									}
									echo esc_html( implode( ' • ', $parts ) );
									?>
								</p>
							<?php endif; ?>

							<div class="doctor-card__meta">
								<div class="doctor-card__meta-item">
									<span class="meta-label"><?php esc_html_e( 'Experience', 'rostest' ); ?></span>
									<span class="meta-value"><?php echo esc_html( $fields['experience'] ); ?> <?php esc_html_e( 'years', 'rostest' ); ?></span>
								</div>
								<div class="doctor-card__meta-item">
									<span class="meta-label"><?php esc_html_e( 'Price', 'rostest' ); ?></span>
									<span class="meta-value"><?php echo esc_html( $price_text ); ?></span>
								</div>
								<div class="doctor-card__meta-item">
									<span class="meta-label"><?php esc_html_e( 'Rating', 'rostest' ); ?></span>
									<span class="meta-value rating">
										<span class="stars" style="--rating-width: <?php echo esc_attr( $rating_width ); ?>%;"></span>
										<span><?php echo esc_html( number_format_i18n( $fields['rating'], 1 ) ); ?></span>
									</span>
								</div>
							</div>
						</div>
					</a>
				</article>
			<?php endwhile; ?>
		</div>

		<div class="pagination">
			<?php
			$add_args = array();
			if ( $current_specialization ) {
				$add_args['specialization'] = $current_specialization;
			}
			if ( $current_city ) {
				$add_args['city'] = $current_city;
			}
			if ( $current_sort ) {
				$add_args['sort'] = $current_sort;
			}

			the_posts_pagination(
				array(
					'mid_size'           => 1,
					'add_args'           => $add_args,
					'prev_text'          => esc_html__( 'Prev', 'rostest' ),
					'next_text'          => esc_html__( 'Next', 'rostest' ),
					'screen_reader_text' => '',
				)
			);
			?>
		</div>
	<?php else : ?>
		<p class="card" style="margin:0;"><?php esc_html_e( 'No doctors found.', 'rostest' ); ?></p>
	<?php endif; ?>
</main>

<?php
get_footer();
