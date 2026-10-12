<?php
/**
 * The header for our theme (EasyLifeDaily BD)
 *
 * @package EasyLifeDaily
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="site-container">
	
	<header class="site-header">
		<!-- Top Bar -->
		<div class="top-bar">
			<div class="top-bar-inner">
				<div class="top-bar-left">
					<span><?php echo esc_html( get_theme_mod( 'easylifedaily_top_announcement', get_theme_mod( 'alishop_top_announcement', '🔥 Welcome to EasyLifeDaily online eCommerce store | Cash on Delivery across Bangladesh!' ) ) ); ?></span>
				</div>
				<div class="top-bar-right">
					<span>Follow us:</span>
					<a href="https://www.facebook.com/profile.php?id=61570898863722" target="_blank" rel="noopener">Facebook</a>
					<span>|</span>
					<a href="https://www.youtube.com/@EasyLifeDailyBD" target="_blank" rel="noopener">YouTube</a>
					<span>|</span>
					<?php $top_phone = get_theme_mod( 'easylifedaily_phone_number', get_theme_mod( 'alishop_phone_number', '01617533063' ) ); ?>
					<a href="tel:<?php echo esc_attr( preg_replace('/[^0-9+]/', '', $top_phone) ); ?>">📞 <?php echo esc_html( $top_phone ); ?></a>
				</div>
			</div>
		</div>
		
		<!-- Main Header -->
		<div class="header-main">
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="logo-text">
						Easy<span>LifeDaily</span>
					</a>
					<?php
				}
				?>
			</div><!-- .site-branding -->
			
			<div class="header-search">
				<?php 
				if ( class_exists( 'WooCommerce' ) ) {
					get_product_search_form();
				} else {
					?>
					<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<input type="search" class="search-field" placeholder="Search for products, gadgets & appliances..." value="<?php echo get_search_query(); ?>" name="s" />
						<button type="submit" class="search-submit">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
						</button>
					</form>
					<?php
				}
				?>
			</div>
			
			<div class="header-actions">
				<?php $header_phone = get_theme_mod( 'easylifedaily_phone_number', get_theme_mod( 'alishop_phone_number', '01617533063' ) ); ?>
				<a href="tel:<?php echo esc_attr( preg_replace('/[^0-9+]/', '', $header_phone) ); ?>" class="header-action-item header-call-item">
					<div class="icon-wrapper">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
					</div>
					<div class="header-action-text">
						<span class="label">Call Center</span>
						<span class="val"><?php echo esc_html( $header_phone ); ?></span>
					</div>
				</a>

				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<?php alishop_render_header_cart(); ?>
				<?php endif; ?>

				<a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( get_option('woocommerce_myaccount_page_id') ) : home_url('/') ); ?>" class="header-action-item header-account-item">
					<div class="icon-wrapper">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
					</div>
					<div class="header-action-text">
						<span class="label">Account</span>
						<span class="val">Login / Register</span>
					</div>
				</a>

				<!-- Mobile Hamburger Category Drawer Button -->
				<button type="button" class="mobile-cat-hamburger-btn" onclick="alishopToggleCategoryDrawer()" aria-label="Toggle Categories Menu">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
				</button>
			</div>
		</div><!-- .header-main -->
		
		<!-- Category Navigation -->
		<nav id="site-navigation" class="main-navigation">
			<div class="navigation-inner">
				<?php
				if ( has_nav_menu( 'menu-1' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
							'menu_class'     => 'nav-menu-list',
							'container'      => false,
							'fallback_cb'    => false,
						)
					);
				} else {
					// Fallback categories list if no menu assigned
					?>
					<ul class="nav-menu-list">
						<li class="current-menu-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
						<li><a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : '#' ); ?>">Gadgets & Electronics</a></li>
						<li><a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : '#' ); ?>">Home Appliances</a></li>
						<li><a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : '#' ); ?>">Kitchen Tools</a></li>
						<li><a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : '#' ); ?>">Baby Products & Toys</a></li>
						<li><a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : '#' ); ?>">Hot Deals 🔥</a></li>
					</ul>
					<?php
				}
				?>
			</div>
		</nav><!-- #site-navigation -->
	</header><!-- .site-header -->
	
	<div class="site-content">
