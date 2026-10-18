<?php
/**
 * The template for displaying WooCommerce Checkout / Direct Order Page & Thank You / Order Received Page
 *
 * @package EasyLifeDaily
 */

get_header();

// Fetch Store Phone from Theme Customizer
$store_phone = get_theme_mod( 'easylifedaily_phone_number', get_theme_mod( 'alishop_phone_number', '01617533063' ) );

// Delivery Charges from Customizer
$delivery_in_text  = get_theme_mod( 'easylifedaily_delivery_inside', get_theme_mod( 'alishop_delivery_inside', '৳ 60 - 70 (1 - 2 Days)' ) );
$delivery_out_text = get_theme_mod( 'easylifedaily_delivery_outside', get_theme_mod( 'alishop_delivery_outside', '৳ 120 - 130 (2 - 4 Days)' ) );

// Check if we are on WooCommerce order-received / thank you page
global $wp;
$is_order_received = false;
$order_id          = 0;
$order             = false;

if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' ) ) {
	$is_order_received = true;
	if ( isset( $wp->query_vars['order-received'] ) && ! empty( $wp->query_vars['order-received'] ) ) {
		$order_id = absint( $wp->query_vars['order-received'] );
	}
}

// Fallback check query params or URI for order-received endpoint
if ( ! $order_id ) {
	if ( isset( $_GET['order-received'] ) ) {
		$is_order_received = true;
		$order_id = absint( $_GET['order-received'] );
	} elseif ( isset( $_GET['order_id'] ) ) {
		$is_order_received = true;
		$order_id = absint( $_GET['order_id'] );
	} elseif ( ! empty( $_SERVER['REQUEST_URI'] ) && preg_match( '/order-received\/(\d+)/', $_SERVER['REQUEST_URI'], $matches ) ) {
		$is_order_received = true;
		$order_id = absint( $matches[1] );
	}
}

if ( $order_id > 0 && class_exists( 'WooCommerce' ) ) {
	$order = wc_get_order( $order_id );
}

// Calculate Cart Items & Subtotal for standard checkout
$cart_items    = ( class_exists( 'WooCommerce' ) && WC()->cart ) ? WC()->cart->get_cart() : array();
$cart_count    = ( class_exists( 'WooCommerce' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
$cart_subtotal = 0;

if ( ! empty( $cart_items ) ) {
	foreach ( $cart_items as $item ) {
		$_prod = $item['data'];
		$cart_subtotal += $_prod->get_price() * $item['quantity'];
	}
}

/* ==========================================================================
   CASE 1: ORDER RECEIVED / THANK YOU PAGE (Success Screen)
   ========================================================================== */
if ( $is_order_received ) :
	?>
	<main id="primary" class="site-main alishop-checkout-page-main">
		<div class="alishop-container">
			<div class="order-received-container">

				<!-- Success Header Card -->
				<div class="order-success-card">
					<div class="success-icon-badge">
						<svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
							<polyline points="20 6 9 17 4 12"></polyline>
						</svg>
					</div>
					<h1 class="order-success-title"><?php esc_html_e( 'ধন্যবাদ! আপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে।', 'easylifedaily' ); ?></h1>
					<p class="order-success-subtitle"><?php esc_html_e( 'আমাদের প্রতিনিধি খুব শীঘ্রই কল করে আপনার অর্ডারটি কনফার্ম করবেন।', 'easylifedaily' ); ?></p>
				</div>

				<?php if ( $order ) : 
					$order_items    = $order->get_items();
					$order_subtotal = $order->get_subtotal();
					$shipping_total = $order->get_shipping_total();
					$grand_total    = $order->get_total();
				?>
					<!-- Key Order Meta Info Grid -->
					<div class="order-meta-info-grid">
						<div class="meta-info-item">
							<span class="meta-info-label"><?php esc_html_e( 'অর্ডার নম্বর', 'easylifedaily' ); ?></span>
							<span class="meta-info-val">#<?php echo esc_html( $order->get_order_number() ); ?></span>
						</div>
						<div class="meta-info-item">
							<span class="meta-info-label"><?php esc_html_e( 'তারিখ', 'easylifedaily' ); ?></span>
							<span class="meta-info-val"><?php echo esc_html( date_i18n( 'd M, Y', strtotime( $order->get_date_created() ) ) ); ?></span>
						</div>
						<div class="meta-info-item">
							<span class="meta-info-label"><?php esc_html_e( 'পেমেন্ট মেথড', 'easylifedaily' ); ?></span>
							<span class="meta-info-val"><?php echo esc_html( $order->get_payment_method_title() ? $order->get_payment_method_title() : 'ক্যাশ অন ডেলিভারি' ); ?></span>
						</div>
						<div class="meta-info-item highlight">
							<span class="meta-info-label"><?php esc_html_e( 'সর্বমোট টাকা', 'easylifedaily' ); ?></span>
							<span class="meta-info-val">৳<?php echo esc_html( number_format( (float) $grand_total, 0 ) ); ?></span>
						</div>
					</div>

					<!-- Two Columns Details: Left = Ordered Products, Right = Customer Info & Action -->
					<div class="order-details-two-col">

						<!-- Left Col: Ordered Products Details -->
						<div class="order-products-card">
							<h2 class="card-section-title">
								<span>📦</span> <?php esc_html_e( 'অর্ডারকৃত পণ্যের বিবরণ', 'easylifedaily' ); ?>
							</h2>

							<div class="order-items-list">
								<?php foreach ( $order_items as $item_id => $item ) : 
									$product       = $item->get_product();
									$item_name     = $item->get_name();
									$item_qty      = $item->get_quantity();
									$item_total    = $item->get_total();
									$thumbnail_url = $product ? get_the_post_thumbnail_url( $product->get_id(), 'thumbnail' ) : '';
									if ( ! $thumbnail_url ) {
										$thumbnail_url = wc_placeholder_img_src();
									}
								?>
									<div class="order-item-row">
										<div class="order-item-thumb">
											<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( $item_name ); ?>">
										</div>
										<div class="order-item-info">
											<h4 class="order-item-name"><?php echo esc_html( $item_name ); ?></h4>
											<span class="order-item-qty">পরিমাণ: <?php echo esc_html( $item_qty ); ?> টি</span>
										</div>
										<div class="order-item-price">
											৳<?php echo esc_html( number_format( (float) $item_total, 0 ) ); ?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>

							<!-- Pricing Calculation Breakdown -->
							<div class="order-pricing-breakdown">
								<div class="pricing-row">
									<span><?php esc_html_e( 'সাবটোটাল', 'easylifedaily' ); ?></span>
									<span>৳<?php echo esc_html( number_format( (float) $order_subtotal, 0 ) ); ?></span>
								</div>
								<div class="pricing-row">
									<span><?php esc_html_e( 'ডেলিভারি চার্জ', 'easylifedaily' ); ?> (<?php echo esc_html( $order->get_shipping_method() ? $order->get_shipping_method() : 'হোম ডেলিভারি' ); ?>)</span>
									<span>৳<?php echo esc_html( number_format( (float) $shipping_total, 0 ) ); ?></span>
								</div>
								<div class="pricing-row total-row">
									<span><?php esc_html_e( 'মোট প্রদেয় বিল', 'easylifedaily' ); ?></span>
									<span class="final-amt">৳<?php echo esc_html( number_format( (float) $grand_total, 0 ) ); ?></span>
								</div>
							</div>
						</div>

						<!-- Right Col: Customer Delivery Info & Quick Actions -->
						<div class="order-customer-card">
							<h2 class="card-section-title">
								<span>👤</span> <?php esc_html_e( 'ডেলিভারি তথ্য', 'easylifedaily' ); ?>
							</h2>

							<div class="customer-info-list">
								<div class="customer-info-row">
									<span class="info-label"><?php esc_html_e( 'নাম:', 'easylifedaily' ); ?></span>
									<span class="info-val"><?php echo esc_html( $order->get_formatted_billing_full_name() ); ?></span>
								</div>
								<div class="customer-info-row">
									<span class="info-label"><?php esc_html_e( 'ফোন:', 'easylifedaily' ); ?></span>
									<span class="info-val"><?php echo esc_html( $order->get_billing_phone() ); ?></span>
								</div>
								<div class="customer-info-row">
									<span class="info-label"><?php esc_html_e( 'ঠিকানা:', 'easylifedaily' ); ?></span>
									<span class="info-val"><?php echo esc_html( $order->get_billing_address_1() ); ?></span>
								</div>
								<?php if ( $order->get_customer_note() ) : ?>
									<div class="customer-info-row">
										<span class="info-label"><?php esc_html_e( 'নোট:', 'easylifedaily' ); ?></span>
										<span class="info-val"><?php echo esc_html( $order->get_customer_note() ); ?></span>
									</div>
								<?php endif; ?>
							</div>

							<div class="order-action-buttons">
								<a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url('/') ); ?>" class="btn-continue-shop">
									<?php esc_html_e( 'আরো কেনাকাটা করুন →', 'easylifedaily' ); ?>
								</a>
								<a href="<?php echo esc_url( home_url('/') ); ?>" class="btn-return-home">
									<?php esc_html_e( 'হোমপেজে ফিরে যান', 'easylifedaily' ); ?>
								</a>
								<a href="tel:<?php echo esc_attr( preg_replace('/[^0-9+]/', '', $store_phone) ); ?>" class="direct-call-btn">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
									আমাদের সরাসরি কল করুন <?php echo esc_html( $store_phone ); ?>
								</a>
							</div>
						</div>

					</div>

				<?php else : ?>
					<div class="checkout-empty-cart-card">
						<div class="empty-cart-icon-circle">✅</div>
						<h2 class="empty-cart-heading"><?php esc_html_e( 'আপনার অর্ডারটি গ্রহণ করা হয়েছে!', 'easylifedaily' ); ?></h2>
						<p class="empty-cart-desc"><?php esc_html_e( 'অর্ডার সংক্রান্ত যেকোনো তথ্যের জন্য আমাদের কাস্টমার সাপোর্টে যোগাযোগ করুন।', 'easylifedaily' ); ?></p>
						<a href="<?php echo esc_url( home_url('/') ); ?>" class="btn-shop-now"><?php esc_html_e( 'হোমপেজে ফিরে যান', 'easylifedaily' ); ?></a>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</main>
	<?php

/* ==========================================================================
   CASE 2: EMPTY CART ON CHECKOUT (When not on thank you page)
   ========================================================================== */
elseif ( empty( $cart_items ) ) :
	?>
	<main id="primary" class="site-main alishop-checkout-page-main">
		<div class="alishop-container">
			<div class="checkout-empty-cart-card">
				<div class="empty-cart-icon-circle">🛒</div>
				<h2 class="empty-cart-heading"><?php esc_html_e( 'আপনার শপিং কার্ট বর্তমানে খালি!', 'easylifedaily' ); ?></h2>
				<p class="empty-cart-desc"><?php esc_html_e( 'অর্ডার সম্পন্ন করার জন্য আপনার পছন্দের পণ্য কার্টে যোগ করুন।', 'easylifedaily' ); ?></p>
				<a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url('/') ); ?>" class="btn-shop-now">
					<?php esc_html_e( 'পণ্য দেখুন ও কেনাকাটা করুন →', 'easylifedaily' ); ?>
				</a>
			</div>
		</div>
	</main>
	<?php

/* ==========================================================================
   CASE 3: STANDARD CHECKOUT PAGE (Items in Cart)
   ========================================================================== */
else :
	?>
	<main id="primary" class="site-main alishop-checkout-page-main">
		<div class="alishop-container">

			<h1 class="checkout-main-title"><?php esc_html_e( 'Checkout', 'easylifedaily' ); ?></h1>

			<!-- 1. TOP PRODUCT ORDER CARD (Orange Border Container Matching User Specs) -->
			<div class="checkout-cart-items-card">
				<?php foreach ( $cart_items as $cart_item_key => $cart_item ) : 
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					if ( ! $_product || ! $_product->exists() ) continue;

					$product_name  = $_product->get_name();
					$reg_price     = $_product->get_regular_price();
					$sale_price    = $_product->get_price();
					$discount_amt  = ( $reg_price && $reg_price > $sale_price ) ? ( $reg_price - $sale_price ) * $cart_item['quantity'] : 0;
					$thumbnail_url = get_the_post_thumbnail_url( $_product->get_id(), 'thumbnail' );
					if ( ! $thumbnail_url ) {
						$thumbnail_url = wc_placeholder_img_src();
					}
				?>
					<div class="checkout-item-row" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">
						<div class="item-left-group">
							<span class="orange-radio-dot">⊙</span>
							<div class="checkout-item-details">
								<h3 class="checkout-product-title">
									<?php echo esc_html( $product_name ); ?>
									<?php if ( $discount_amt > 0 ) : ?>
										<span class="save-discount-badge">Save ৳<?php echo esc_html( $discount_amt ); ?></span>
									<?php endif; ?>
								</h3>
								<div class="checkout-item-prices">
									<?php if ( $reg_price && $reg_price > $sale_price ) : ?>
										<del>৳<?php echo esc_html( $reg_price ); ?></del>
									<?php endif; ?>
									<span class="sale-price">৳<?php echo esc_html( $sale_price ); ?></span>
								</div>
								<div class="item-bottom-controls">
									<div class="checkout-qty-stepper">
										<button type="button" class="c-qty-btn minus" onclick="alishopCheckoutUpdateQty('<?php echo esc_js( $cart_item_key ); ?>', <?php echo (int) $cart_item['quantity'] - 1; ?>)">-</button>
										<span class="c-qty-val"><?php echo esc_html( $cart_item['quantity'] ); ?></span>
										<button type="button" class="c-qty-btn plus" onclick="alishopCheckoutUpdateQty('<?php echo esc_js( $cart_item_key ); ?>', <?php echo (int) $cart_item['quantity'] + 1; ?>)">+</button>
									</div>
									<button type="button" class="checkout-remove-btn" onclick="alishopCheckoutRemoveItem('<?php echo esc_js( $cart_item_key ); ?>')" title="Remove Item">🗑</button>
								</div>
							</div>
						</div>

						<div class="checkout-item-thumb">
							<img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( $product_name ); ?>">
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- 2. TWO COLUMN CHECKOUT GRID -->
			<form id="alishopPageCheckoutForm" method="post" onsubmit="alishopSubmitCheckoutForm(event)">
				<div class="checkout-two-columns-grid">

					<!-- LEFT COL: BILLING DETAILS -->
					<div class="checkout-billing-card">
						<h2 class="billing-card-title"><?php esc_html_e( 'বিলিং ডিটেইল', 'easylifedaily' ); ?></h2>

						<div class="checkout-form-fields">
							<div class="form-input-group">
								<input type="text" name="cust_name" id="chkCustName" placeholder="নাম" required>
							</div>

							<div class="form-input-group">
								<input type="tel" name="cust_phone" id="chkCustPhone" placeholder="মোবাইল নম্বর *" required>
							</div>

							<div class="form-input-group">
								<input type="text" name="cust_address" id="chkCustAddress" placeholder="সম্পূর্ণ ঠিকানা" required>
							</div>

							<div class="form-input-group">
								<textarea name="cust_notes" id="chkCustNotes" rows="3" placeholder="আপনার মতামত থাকলে লিখুন"></textarea>
							</div>

							<!-- Shipping Location Options -->
							<div class="delivery-location-section">
								<label class="delivery-section-label"><?php esc_html_e( 'ডেলিভারি এলাকা *', 'easylifedaily' ); ?></label>

								<div class="shipping-radio-options">
									<label class="shipping-radio-card" id="shipInsideCard">
										<input type="radio" name="delivery_zone" value="inside" onclick="alishopSetShippingZone(60, this)">
										<span>ঢাকা শহরের ভিতরে <strong class="charge-amt">৳60</strong></span>
									</label>

									<label class="shipping-radio-card active" id="shipOutsideCard">
										<input type="radio" name="delivery_zone" value="outside" checked onclick="alishopSetShippingZone(100, this)">
										<span>ঢাকা শহরের বাইরে <strong class="charge-amt">৳100</strong></span>
									</label>
								</div>
							</div>
						</div>
					</div>

					<!-- RIGHT COL: SUMMARY & ACTIONS -->
					<div class="checkout-summary-card">
						<div class="summary-total-header">
							<span class="total-label"><?php esc_html_e( 'টোটাল', 'easylifedaily' ); ?></span>
							<span class="total-amount">৳<span id="chkGrandTotalDisplay"><?php echo esc_html( $cart_subtotal + 100 ); ?></span></span>
						</div>

						<button type="submit" id="btnConfirmOrder" class="confirm-order-btn">
							<?php esc_html_e( 'অর্ডার টি কনফার্ম করুন', 'easylifedaily' ); ?>
						</button>

						<a href="tel:<?php echo esc_attr( preg_replace('/[^0-9+]/', '', $store_phone) ); ?>" class="direct-call-btn">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
							আমাদের সাথে সরাসরি কথা বলুন <?php echo esc_html( $store_phone ); ?>
						</a>
					</div>

				</div>
			</form>

		</div>
	</main>

	<script>
	var currentSubtotal = <?php echo (float) $cart_subtotal; ?>;
	var currentShipping = 100;

	function alishopSetShippingZone(charge, elem) {
		currentShipping = charge;
		var cards = document.querySelectorAll('.shipping-radio-card');
		cards.forEach(function(c){ c.classList.remove('active'); });
		if (elem) {
			elem.closest('.shipping-radio-card').classList.add('active');
		}
		alishopUpdateCheckoutGrandTotal();
	}

	function alishopUpdateCheckoutGrandTotal() {
		var total = currentSubtotal + currentShipping;
		document.getElementById('chkGrandTotalDisplay').innerText = total;
	}

	function alishopCheckoutUpdateQty(cartKey, newQty) {
		if (typeof jQuery !== 'undefined') {
			jQuery.post('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
				action: 'alishop_update_cart_qty',
				cart_item_key: cartKey,
				quantity: newQty
			}, function(res){
				window.location.reload();
			});
		}
	}

	function alishopCheckoutRemoveItem(cartKey) {
		if (typeof jQuery !== 'undefined') {
			jQuery.post('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
				action: 'alishop_remove_cart_item',
				cart_item_key: cartKey
			}, function(res){
				window.location.reload();
			});
		}
	}

	function alishopSubmitCheckoutForm(e) {
		e.preventDefault();
		var btn = document.getElementById('btnConfirmOrder');
		var origText = btn.innerText;
		btn.disabled = true;
		btn.innerText = 'অর্ডার প্রসেস করা হচ্ছে...';

		var formData = {
			action: 'alishop_process_custom_checkout',
			cust_name: document.getElementById('chkCustName').value,
			cust_phone: document.getElementById('chkCustPhone').value,
			cust_address: document.getElementById('chkCustAddress').value,
			cust_notes: document.getElementById('chkCustNotes').value,
			delivery_zone: document.querySelector('input[name="delivery_zone"]:checked').value
		};

		if (typeof jQuery !== 'undefined') {
			jQuery.post('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', formData, function(res){
				if (res.success && res.data.redirect) {
					window.location.href = res.data.redirect;
				} else {
					alert(res.data && res.data.message ? res.data.message : 'অর্ডার প্রসেস করতে সমস্যা হয়েছে।');
					btn.disabled = false;
					btn.innerText = origText;
				}
			}).fail(function(){
				alert('ধন্যবাদ! আপনার অর্ডারটি গ্রহন করা হয়েছে। আমাদের প্রতিনিধি শীঘ্রই কল করবে।');
				btn.disabled = false;
				btn.innerText = origText;
			});
		}
	}
	</script>
	<?php
endif;

get_footer();

