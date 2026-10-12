<?php
/**
 * The template for displaying the footer (EasyLifeDaily BD)
 *
 * @package EasyLifeDaily
 */

?>
	</div><!-- .site-content -->

	<footer class="site-footer">
		<div class="footer-inner">
			<div class="footer-widgets">
				<!-- About EasyLifeDaily -->
				<div class="footer-widget">
					<h3 class="widget-title">About Easy Life Daily</h3>
					<p>easylifedaily.com is one of Bangladesh's leading online shopping platforms offering authentic gadgets, home appliances, kitchen tools, and lifestyle products with fast Cash on Delivery across the nation.</p>
				</div>

				<!-- Quick Links -->
				<div class="footer-widget">
					<h3 class="widget-title">Quick Links</h3>
					<ul>
						<li><a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url('/') ); ?>">All Products / Shop</a></li>
						<li><a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( get_option('woocommerce_myaccount_page_id') ) : home_url('/') ); ?>">My Account & Orders</a></li>
						<li><a href="<?php echo esc_url( class_exists('WooCommerce') ? wc_get_cart_url() : home_url('/') ); ?>">Shopping Cart</a></li>
						<li><a href="<?php echo esc_url( class_exists('WooCommerce') ? wc_get_checkout_url() : home_url('/') ); ?>">Order Checkout</a></li>
					</ul>
				</div>

				<!-- Customer Service -->
				<div class="footer-widget">
					<h3 class="widget-title">Customer Care</h3>
					<ul>
						<li><a href="#">Privacy Policy</a></li>
						<li><a href="#">Terms & Conditions</a></li>
						<li><a href="#">Return & Refund Policy</a></li>
						<li><a href="#">Delivery & Shipping Info</a></li>
					</ul>
				</div>

				<!-- Official Contact Info -->
				<div class="footer-widget">
					<h3 class="widget-title">Contact & Store Info</h3>
					<div class="contact-list-item">
						<span class="icon">📍</span>
						<span>Ayesha Garden, Saidnagar, Natun Bazar, Bhatara, Dhaka-1212</span>
					</div>
					<?php 
					$footer_phone = get_theme_mod( 'easylifedaily_phone_number', get_theme_mod( 'alishop_phone_number', '01617533063' ) );
					$footer_wa    = get_theme_mod( 'easylifedaily_whatsapp_number', get_theme_mod( 'alishop_whatsapp_number', '01617533063' ) );
					$clean_footer_wa = preg_replace( '/[^0-9]/', '', $footer_wa );
					if ( substr( $clean_footer_wa, 0, 2 ) !== '88' && strlen( $clean_footer_wa ) === 11 ) {
						$clean_footer_wa = '88' . $clean_footer_wa;
					}
					?>
					<div class="contact-list-item">
						<span class="icon">📞</span>
						<span><a href="tel:<?php echo esc_attr( preg_replace('/[^0-9+]/', '', $footer_phone) ); ?>" style="color:#FFF; font-weight:700;"><?php echo esc_html( $footer_phone ); ?></a></span>
					</div>
					<div class="contact-list-item">
						<span class="icon">✉️</span>
						<span>support@easylifedaily.com</span>
					</div>
				</div>
			</div>

			<!-- Payment Method Logos & Badges -->
			<div class="payment-methods-bar">
				<div class="payment-title">We Accept Secure Payment Methods:</div>
				<div class="payment-badges">
					<span class="payment-badge">💵 Cash on Delivery</span>
					<span class="payment-badge" style="background:#E2136E; color:#FFF;">bKash</span>
					<span class="payment-badge" style="background:#F7931E; color:#FFF;">Nagad</span>
					<span class="payment-badge" style="background:#8C3494; color:#FFF;">Rocket</span>
					<span class="payment-badge" style="background:#1A1F71; color:#FFF;">Visa / Master</span>
				</div>
			</div>

			<div class="site-info">
				&copy; <?php echo date('Y'); ?> <strong>Easy Life Daily Bangladesh</strong> (easylifedaily.com). All Rights Reserved.
			</div><!-- .site-info -->
		</div>
	</footer><!-- .site-footer -->

</div><!-- .site-container -->

	<!-- Floating WhatsApp & Phone Call Buttons -->
	<div class="floating-contact-buttons">
		<a href="https://wa.me/<?php echo esc_attr( $clean_footer_wa ); ?>" target="_blank" class="floating-btn floating-whatsapp" title="Chat on WhatsApp">
			💬
		</a>
		<a href="tel:<?php echo esc_attr( preg_replace('/[^0-9+]/', '', $footer_phone) ); ?>" class="floating-btn floating-call" title="Call Hotline">
			📞
		</a>
	</div>

	<!-- Mobile Bottom Fixed Navigation (Matching Screenshots 1, 2, 3) -->
	<div class="mobile-bottom-nav">
		<a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url('/') ); ?>" class="mobile-nav-item <?php echo ( class_exists('WooCommerce') && is_shop() ) ? 'active' : ''; ?>">
			<div class="nav-icon-box">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
			</div>
			<span>Shop</span>
		</a>

		<a href="<?php echo esc_url( get_option('page_for_posts') ? get_permalink( get_option('page_for_posts') ) : home_url('/') ); ?>" class="mobile-nav-item">
			<div class="nav-icon-box">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
			</div>
			<span>Blog</span>
		</a>

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mobile-nav-item mobile-nav-home <?php echo is_front_page() ? 'active' : ''; ?>">
			<div class="home-circle-btn">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
			</div>
			<span>Home</span>
		</a>

		<a href="javascript:void(0)" onclick="alishopFocusMobileSearch()" class="mobile-nav-item">
			<div class="nav-icon-box">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
			</div>
			<span>Search</span>
		</a>

		<a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( get_option('woocommerce_myaccount_page_id') ) : home_url('/') ); ?>" class="mobile-nav-item">
			<div class="nav-icon-box">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
			</div>
			<span>Login</span>
		</a>
	</div>

	<!-- Dynamic Floating Cart Box Widget -->
	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<?php alishop_render_floating_cart(); ?>
	<?php endif; ?>

	<!-- Side Cart Popup Drawer Container -->
	<div id="alishopCartDrawerOverlay" class="alishop-cart-drawer-overlay" onclick="alishopCloseCartDrawer()"></div>
	<div id="alishopCartDrawerPanel" class="alishop-cart-drawer-panel">
		<?php if ( class_exists( 'WooCommerce' ) ) : ?>
			<?php alishop_render_side_cart_drawer(); ?>
		<?php endif; ?>
	</div>

	<!-- Mobile Category Right-Side Popup Drawer (Matching Screenshot 2) -->
	<div id="alishopCategoryDrawerOverlay" class="alishop-cat-drawer-overlay" onclick="alishopToggleCategoryDrawer()"></div>
	<div id="alishopCategoryDrawerPanel" class="alishop-cat-drawer-panel">
		<div class="cat-drawer-header">
			<h3>Categories</h3>
			<button type="button" class="cat-drawer-close" onclick="alishopToggleCategoryDrawer()" aria-label="Close Categories">✕</button>
		</div>
		<div class="cat-drawer-body">
			<ul class="cat-drawer-menu">
				<?php
				if ( class_exists( 'WooCommerce' ) && taxonomy_exists( 'product_cat' ) ) {
					$categories = get_terms( array(
						'taxonomy'   => 'product_cat',
						'hide_empty' => false,
						'parent'     => 0,
					) );

					if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
						foreach ( $categories as $cat ) {
							$subcats  = get_terms( array(
								'taxonomy'   => 'product_cat',
								'hide_empty' => false,
								'parent'     => $cat->term_id,
							) );
							$has_subs = ! empty( $subcats ) && ! is_wp_error( $subcats );
							?>
							<li class="cat-drawer-item <?php echo $has_subs ? 'has-subs' : ''; ?>">
								<a href="<?php echo esc_url( add_query_arg( 'cat', $cat->slug, get_permalink( wc_get_page_id( 'shop' ) ) ) ); ?>">
									<span><?php echo esc_html( $cat->name ); ?></span>
									<span class="sub-toggle-icon">˅</span>
								</a>
								<?php if ( $has_subs ) : ?>
									<ul class="cat-drawer-sub-menu">
										<?php foreach ( $subcats as $sub ) : ?>
											<li>
												<a href="<?php echo esc_url( add_query_arg( 'cat', $sub->slug, get_permalink( wc_get_page_id( 'shop' ) ) ) ); ?>">
													<?php echo esc_html( $sub->name ); ?>
												</a>
											</li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</li>
							<?php
						}
					}
				} else {
					// Fallback category items matching Screenshot 2
					$demo_cats = array(
						'Gadgets', 'Home Appliances', 'Home & Living', 'Home Workout Accessories', 
						'Islamic Accessories', 'Lights', 'Personal Care', 'Pet Accessories', 
						'Portable Products', 'Toys & Games'
					);
					foreach ( $demo_cats as $d_cat ) {
						?>
						<li class="cat-drawer-item">
							<a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : '#' ); ?>">
								<span><?php echo esc_html( $d_cat ); ?></span>
								<span class="sub-toggle-icon">˅</span>
							</a>
						</li>
						<?php
					}
				}
				?>
			</ul>
		</div>
	</div>

<script>
// Category Right-Side Drawer Popup Toggle Function (Matching Screenshot 2)
function alishopToggleCategoryDrawer() {
	var overlay = document.getElementById('alishopCategoryDrawerOverlay');
	var panel = document.getElementById('alishopCategoryDrawerPanel');
	if (overlay && panel) {
		overlay.classList.toggle('open');
		panel.classList.toggle('open');
		if (panel.classList.contains('open')) {
			document.body.style.overflow = 'hidden';
		} else {
			document.body.style.overflow = '';
		}
	}
}

// Mobile Search Focus Helper Function
function alishopFocusMobileSearch() {
	var sInput = document.querySelector('.header-search input[type="search"]');
	if (sInput) {
		sInput.focus();
		sInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
	}
}

// Side Cart Popup Drawer JS Functions
function alishopOpenCartDrawer() {
	var overlay = document.getElementById('alishopCartDrawerOverlay');
	var panel = document.getElementById('alishopCartDrawerPanel');
	if (overlay && panel) {
		overlay.classList.add('open');
		panel.classList.add('open');
		document.body.style.overflow = 'hidden';
	}
}

function alishopCloseCartDrawer() {
	var overlay = document.getElementById('alishopCartDrawerOverlay');
	var panel = document.getElementById('alishopCartDrawerPanel');
	if (overlay && panel) {
		overlay.classList.remove('open');
		panel.classList.remove('open');
		document.body.style.overflow = '';
	}
}

function alishopUpdateCartItemQty(cartKey, newQty) {
	if (typeof jQuery !== 'undefined') {
		jQuery.post('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
			action: 'alishop_update_cart_qty',
			cart_item_key: cartKey,
			quantity: newQty
		}, function(res){
			if (res.success && res.data.fragments) {
				jQuery.each(res.data.fragments, function(key, value) {
					jQuery(key).replaceWith(value);
				});
				jQuery(document.body).trigger('updated_cart_totals');
			}
		});
	}
}

function alishopRemoveCartItem(cartKey) {
	if (typeof jQuery !== 'undefined') {
		jQuery.post('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
			action: 'alishop_remove_cart_item',
			cart_item_key: cartKey
		}, function(res){
			if (res.success && res.data.fragments) {
				jQuery.each(res.data.fragments, function(key, value) {
					jQuery(key).replaceWith(value);
				});
				jQuery(document.body).trigger('updated_cart_totals');
			}
		});
	}
}

// Global WooCommerce AJAX Add-to-Cart Helper Function
function alishopAddToCart(productId, qty) {
	qty = qty || 1;
	if ( typeof jQuery !== 'undefined' && typeof wc_add_to_cart_params !== 'undefined' ) {
		var data = {
			action: 'woocommerce_ajax_add_to_cart',
			product_id: productId,
			quantity: qty
		};
		jQuery.post( wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'add_to_cart' ), data, function( response ) {
			if ( ! response ) return;
			if ( response.error && response.product_url ) {
				window.location = response.product_url;
				return;
			}
			if ( response.fragments ) {
				jQuery( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash ] );
			}
		});
	} else {
		window.location.href = '?add-to-cart=' + productId + '&quantity=' + qty;
	}
}

// Global WooCommerce Buy Now -> Direct Checkout Helper Function
function alishopBuyNow(productId, qty) {
	qty = qty || 1;
	var checkoutUrl = '<?php echo esc_js( class_exists("WooCommerce") ? wc_get_checkout_url() : home_url("/checkout/") ); ?>';
	var sep = checkoutUrl.indexOf('?') !== -1 ? '&' : '?';
	window.location.href = checkoutUrl + sep + 'add-to-cart=' + productId + '&quantity=' + qty;
}

// Auto open side cart drawer when an item is added to cart via AJAX!
if ( typeof jQuery !== 'undefined' ) {
	jQuery( document.body ).on( 'added_to_cart', function( event, fragments, cart_hash ) {
		var $badges = jQuery( '.widget-count-badge, .header-action-item .badge' );
		$badges.addClass( 'cart-pop' );
		setTimeout( function() {
			$badges.removeClass( 'cart-pop' );
		}, 600 );
		
		alishopOpenCartDrawer();
	});
}
</script>

<?php wp_footer(); ?>
</body>
</html>

