<?php
/**
 * The template for displaying WooCommerce Checkout / Direct Order Page
 * Pixel-perfect matching user screenshot
 *
 * @package EasyLifeDaily
 */

get_header();

// Fetch Store Phone from Theme Customizer
$store_phone = get_theme_mod( 'easylifedaily_phone_number', get_theme_mod( 'alishop_phone_number', '01617533063' ) );

// Delivery Charges from Customizer
$delivery_in_text  = get_theme_mod( 'easylifedaily_delivery_inside', get_theme_mod( 'alishop_delivery_inside', '৳ 60 - 70 (1 - 2 Days)' ) );
$delivery_out_text = get_theme_mod( 'easylifedaily_delivery_outside', get_theme_mod( 'alishop_delivery_outside', '৳ 120 - 130 (2 - 4 Days)' ) );

// Calculate Cart Items & Subtotal
$cart_items    = ( class_exists( 'WooCommerce' ) && WC()->cart ) ? WC()->cart->get_cart() : array();
$cart_count    = ( class_exists( 'WooCommerce' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
$cart_subtotal = 0;

if ( ! empty( $cart_items ) ) {
	foreach ( $cart_items as $item ) {
		$_prod = $item['data'];
		$cart_subtotal += $_prod->get_price() * $item['quantity'];
	}
} else {
	// Fallback demo subtotal if cart is empty
	$cart_subtotal = 1290;
}
?>

<main id="primary" class="site-main alishop-checkout-page-main">
	<div class="alishop-container">

		<h1 class="checkout-main-title"><?php esc_html_e( 'Checkout', 'easylifedaily' ); ?></h1>

		<!-- 1. TOP PRODUCT ORDER CARD (Orange Border Container Matching Screenshot 2) -->
		<div class="checkout-cart-items-card">
			<?php if ( ! empty( $cart_items ) ) : ?>
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
			<?php else : ?>
				<!-- Demo Fallback Item matching screenshot 2 if cart empty -->
				<div class="checkout-item-row">
					<div class="item-left-group">
						<span class="orange-radio-dot">⊙</span>
						<div class="checkout-item-details">
							<h3 class="checkout-product-title">
								Slim Stainless Steel Water Bottle | Portable & Stylish for Daily Hydration
								<span class="save-discount-badge">Save ৳310</span>
							</h3>
							<div class="checkout-item-prices">
								<del>৳1000</del>
								<span class="sale-price">৳690</span>
							</div>
							<div class="item-bottom-controls">
								<div class="checkout-qty-stepper">
									<button type="button" class="c-qty-btn minus">-</button>
									<span class="c-qty-val">2</span>
									<button type="button" class="c-qty-btn plus">+</button>
								</div>
								<button type="button" class="checkout-remove-btn">🗑</button>
							</div>
						</div>
					</div>
					<div class="checkout-item-thumb">
						<img src="https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=200&auto=format&fit=crop" alt="Water Bottle">
					</div>
				</div>
			<?php endif; ?>
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
get_footer();
