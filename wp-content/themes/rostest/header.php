<?php
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#content"><?php esc_html_e( 'Skip to content', 'rostest' ); ?></a>

<div class="page">
<header class="site-header">
	<div class="container header-inner">
		<div class="brand">
			<a class="brand-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<span class="brand-mark" aria-hidden="true"></span>
				<span class="brand-text"><?php bloginfo( 'name' ); ?></span>
			</a>
		</div>

		<nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'rostest' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'nav-list',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
			} else {
				$doctors_url = get_post_type_archive_link( 'doctors' );
				if ( $doctors_url ) {
					echo '<ul class="nav-list"><li><a href="' . esc_url( $doctors_url ) . '">' . esc_html__( 'Doctors', 'rostest' ) . '</a></li></ul>';
				}
			}
			?>
		</nav>
	</div>
</header>
