<?php
/**
 * The template for displaying WooCommerce Shop & Category Archives
 * Matching EasyLifeDaily.com Shop Page
 *
 * @package EasyLifeDaily
 */

get_header();

// Get active filter values from GET or WooCommerce query context
$current_cat       = get_query_var( 'product_cat' );
if ( empty( $current_cat ) && isset( $_GET['cat'] ) ) {
	$current_cat = sanitize_text_field( $_GET['cat'] );
}

$min_price_val     = isset( $_GET['min_price'] ) ? sanitize_text_field( $_GET['min_price'] ) : '';
$max_price_val     = isset( $_GET['max_price'] ) ? sanitize_text_field( $_GET['max_price'] ) : '';
$orderby_val       = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'date';
$posts_per_page    = isset( $_GET['posts_per_page'] ) ? absint( $_GET['posts_per_page'] ) : 20;
$paged             = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

// Category name for display
$current_cat_obj  = ! empty( $current_cat ) ? get_term_by( 'slug', $current_cat, 'product_cat' ) : null;
$active_cat_name  = $current_cat_obj ? $current_cat_obj->name : __( 'All Categories', 'alishop-clone' );
?>

<main id="primary" class="site-main alishop-shop-page-main">
	<div class="alishop-container">

		<form method="get" id="alishopShopFilterForm" action="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url('/') ); ?>">
			
			<div class="alishop-shop-layout">

				<!-- 1. LEFT SIDEBAR: FILTERS -->
				<aside class="alishop-shop-sidebar">

					<!-- CATEGORY Widget Box -->
					<div class="shop-widget-box">
						<h3 class="widget-box-title"><?php esc_html_e( 'CATEGORY', 'alishop-clone' ); ?></h3>
						<ul class="shop-category-tree">
							<li class="<?php echo empty( $current_cat ) ? 'active' : ''; ?>">
								<a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url('/') ); ?>">
									<span><?php esc_html_e( 'All Categories', 'alishop-clone' ); ?></span>
								</a>
							</li>
							<?php
							if ( taxonomy_exists( 'product_cat' ) ) {
								$categories = get_terms( array(
									'taxonomy'   => 'product_cat',
									'hide_empty' => false,
									'parent'     => 0,
								) );

								if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
									foreach ( $categories as $cat ) {
										$is_active = ( $current_cat === $cat->slug );
										$subcats   = get_terms( array(
											'taxonomy'   => 'product_cat',
											'hide_empty' => false,
											'parent'     => $cat->term_id,
										) );
										$has_subs  = ! empty( $subcats ) && ! is_wp_error( $subcats );
										?>
										<li class="cat-item <?php echo $is_active ? 'active' : ''; ?> <?php echo $has_subs ? 'has-children' : ''; ?>">
											<a href="<?php echo esc_url( add_query_arg( 'cat', $cat->slug, class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url('/') ) ); ?>">
												<span><?php echo esc_html( $cat->name ); ?></span>
												<?php if ( $has_subs ) : ?>
													<span class="chevron-icon">˅</span>
												<?php endif; ?>
											</a>
											<?php if ( $has_subs ) : ?>
												<ul class="sub-category-list">
													<?php foreach ( $subcats as $sub ) : 
														$sub_active = ( $current_cat === $sub->slug );
													?>
														<li class="<?php echo $sub_active ? 'active' : ''; ?>">
															<a href="<?php echo esc_url( add_query_arg( 'cat', $sub->slug, class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url('/') ) ); ?>">
																<span><?php echo esc_html( $sub->name ); ?></span>
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
								// Demo Fallback Categories
								$demo_cats = array(
									'Gadgets', 'Home Appliances', 'Home & Living', 'Home Workout Accessories', 
									'Islamic Accessories', 'Lights', 'Personal Care', 'Pet Accessories', 
									'Portable Products', 'Toys & Games'
								);
								foreach ( $demo_cats as $d_cat ) {
									$d_slug = sanitize_title( $d_cat );
									?>
									<li class="cat-item <?php echo ( $current_cat === $d_slug ) ? 'active' : ''; ?>">
										<a href="#">
											<span><?php echo esc_html( $d_cat ); ?></span>
											<span class="chevron-icon">˅</span>
										</a>
									</li>
									<?php
								}
							}
							?>
						</ul>
					</div>

					<!-- PRICE RANGE Widget Box -->
					<div class="shop-widget-box">
						<h3 class="widget-box-title"><?php esc_html_e( 'PRICE RANGE', 'alishop-clone' ); ?></h3>

						<!-- Dual Range Slider -->
						<div class="price-slider-wrapper">
							<input type="range" class="price-range-slider" min="0" max="10000" step="50" value="<?php echo ! empty( $max_price_val ) ? esc_attr( $max_price_val ) : '10000'; ?>" oninput="alishopSyncPriceSlider(this.value)">
						</div>

						<div class="price-inputs-row">
							<div class="price-input-box">
								<span class="currency-symbol">৳</span>
								<input type="number" name="min_price" id="minPriceInput" placeholder="Min p" value="<?php echo esc_attr( $min_price_val ); ?>">
							</div>
							<div class="price-input-box">
								<span class="currency-symbol">৳</span>
								<input type="number" name="max_price" id="maxPriceInput" placeholder="10000" value="<?php echo esc_attr( $max_price_val ); ?>">
							</div>
						</div>

						<!-- Radio Price Presets -->
						<div class="price-radio-presets">
							<label class="price-radio-item">
								<input type="radio" name="price_preset" value="" <?php checked( empty( $min_price_val ) && empty( $max_price_val ) ); ?> onclick="alishopSetPricePreset('', '')">
								<span>All Price</span>
							</label>
							<label class="price-radio-item">
								<input type="radio" name="price_preset" value="0-300" <?php checked( $min_price_val === '0' && $max_price_val === '300' ); ?> onclick="alishopSetPricePreset('0', '300')">
								<span>৳0 to ৳300</span>
							</label>
							<label class="price-radio-item">
								<input type="radio" name="price_preset" value="300-500" <?php checked( $min_price_val === '300' && $max_price_val === '500' ); ?> onclick="alishopSetPricePreset('300', '500')">
								<span>৳300 to ৳500</span>
							</label>
							<label class="price-radio-item">
								<input type="radio" name="price_preset" value="500-1000" <?php checked( $min_price_val === '500' && $max_price_val === '1000' ); ?> onclick="alishopSetPricePreset('500', '1000')">
								<span>৳500 to ৳1,000</span>
							</label>
							<label class="price-radio-item">
								<input type="radio" name="price_preset" value="1000-10000" <?php checked( $min_price_val === '1000' && $max_price_val === '10000' ); ?> onclick="alishopSetPricePreset('1000', '10000')">
								<span>৳1,000 to ৳10,000</span>
							</label>
						</div>

						<button type="submit" class="apply-price-filter-btn">Filter Price</button>
					</div>

				</aside>

				<!-- 2. RIGHT MAIN CONTENT AREA -->
				<section class="alishop-shop-content">

					<!-- Top Header Controls Bar -->
					<div class="shop-top-header-bar">
						<button type="button" class="mobile-filter-icon-btn" onclick="alishopToggleShopMobileFilter()" aria-label="Toggle Filter Options">
							<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#FF6600" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
						</button>

						<div class="active-filters-info">
							<span class="label">Active Filters:</span>
							<span class="filter-tag"><?php echo esc_html( $active_cat_name ); ?></span>
							<?php if ( ! empty( $current_cat ) || ! empty( $min_price_val ) || ! empty( $max_price_val ) ) : ?>
								<a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url('/') ); ?>" class="clear-all-filters">Clear All ✕</a>
							<?php endif; ?>
						</div>

						<div class="shop-controls-right">
							<!-- Hidden Category Input -->
							<?php if ( ! empty( $current_cat ) ) : ?>
								<input type="hidden" name="cat" value="<?php echo esc_attr( $current_cat ); ?>">
							<?php endif; ?>

							<!-- Sort By -->
							<div class="control-select-box">
								<label for="shopSortBy">Sort by:</label>
								<select name="orderby" id="shopSortBy" onchange="document.getElementById('alishopShopFilterForm').submit()">
									<option value="date" <?php selected( $orderby_val, 'date' ); ?>>Newest First</option>
									<option value="price" <?php selected( $orderby_val, 'price' ); ?>>Price: Low to High</option>
									<option value="price-desc" <?php selected( $orderby_val, 'price-desc' ); ?>>Price: High to Low</option>
									<option value="popularity" <?php selected( $orderby_val, 'popularity' ); ?>>Popularity</option>
									<option value="rating" <?php selected( $orderby_val, 'rating' ); ?>>Average Rating</option>
								</select>
							</div>

							<!-- Show Count -->
							<div class="control-select-box">
								<label for="shopShowCount">Show:</label>
								<select name="posts_per_page" id="shopShowCount" onchange="document.getElementById('alishopShopFilterForm').submit()">
									<option value="20" <?php selected( $posts_per_page, 20 ); ?>>20 per page</option>
									<option value="12" <?php selected( $posts_per_page, 12 ); ?>>12 per page</option>
									<option value="36" <?php selected( $posts_per_page, 36 ); ?>>36 per page</option>
									<option value="48" <?php selected( $posts_per_page, 48 ); ?>>48 per page</option>
								</select>
							</div>
						</div>
					</div>

					<!-- Product Grid (5 Columns Layout) -->
					<div class="alishop-shop-products-grid">
						<?php
						$has_products = false;

						if ( class_exists( 'WooCommerce' ) ) {
							$args = array(
								'post_type'      => 'product',
								'posts_per_page' => $posts_per_page,
								'paged'          => $paged,
								'post_status'    => 'publish',
							);

							// Category Filter
							if ( ! empty( $current_cat ) ) {
								$args['tax_query'] = array(
									array(
										'taxonomy' => 'product_cat',
										'field'    => 'slug',
										'terms'    => $current_cat,
									),
								);
							}

							// Ordering
							switch ( $orderby_val ) {
								case 'price':
									$args['meta_key'] = '_price';
									$args['orderby']  = 'meta_value_num';
									$args['order']    = 'ASC';
									break;
								case 'price-desc':
									$args['meta_key'] = '_price';
									$args['orderby']  = 'meta_value_num';
									$args['order']    = 'DESC';
									break;
								case 'popularity':
									$args['meta_key'] = 'total_sales';
									$args['orderby']  = 'meta_value_num';
									$args['order']    = 'DESC';
									break;
								case 'rating':
									$args['meta_key'] = '_wc_average_rating';
									$args['orderby']  = 'meta_value_num';
									$args['order']    = 'DESC';
									break;
								case 'date':
								default:
									$args['orderby']  = 'date';
									$args['order']    = 'DESC';
									break;
							}

							// Price Range Filter
							if ( $min_price_val !== '' || $max_price_val !== '' ) {
								$meta_price_query = array( 'key' => '_price', 'type' => 'NUMERIC' );
								if ( $min_price_val !== '' && $max_price_val !== '' ) {
									$meta_price_query['value']   = array( (float) $min_price_val, (float) $max_price_val );
									$meta_price_query['compare'] = 'BETWEEN';
								} elseif ( $min_price_val !== '' ) {
									$meta_price_query['value']   = (float) $min_price_val;
									$meta_price_query['compare'] = '>=';
								} elseif ( $max_price_val !== '' ) {
									$meta_price_query['value']   = (float) $max_price_val;
									$meta_price_query['compare'] = '<=';
								}
								$args['meta_query'] = array( $meta_price_query );
							}

							$shop_query = new WP_Query( $args );

							if ( $shop_query->have_posts() ) {
								$has_products = true;
								while ( $shop_query->have_posts() ) {
									$shop_query->the_post();
									global $product;
									if ( ! $product ) continue;

									$product_id       = $product->get_id();
									$title            = get_the_title();
									$link             = get_permalink();
									$reg_price        = $product->get_regular_price();
									$sale_price       = $product->get_price();
									$discount         = 0;
									if ( $reg_price && $sale_price && $reg_price > $sale_price ) {
										$discount = round( (($reg_price - $sale_price) / $reg_price) * 100 );
									}
									$img_url = get_the_post_thumbnail_url( $product_id, 'medium' );
									if ( ! $img_url ) {
										$img_url = wc_placeholder_img_src();
									}
									$add_to_cart_url  = $product->add_to_cart_url();
									$add_to_cart_text = $product->add_to_cart_text();
									$product_type     = $product->get_type();
									$sku              = $product->get_sku();
									$checkout_url     = class_exists( 'WooCommerce' ) ? wc_get_checkout_url() : home_url( '/checkout/' );
									$order_now_url    = ( $product->is_type( 'variable' ) || ! $product->is_purchasable() ) ? $link : add_query_arg( 'add-to-cart', $product_id, $checkout_url );
									?>
									<div class="alishop-product-card-item">
										<div class="card-thumb-box">
											<?php if ( $discount > 0 ) : ?>
												<span class="card-discount-tag"><?php echo esc_html( $discount ); ?>% OFF</span>
											<?php endif; ?>
											<a href="<?php echo esc_url( $link ); ?>">
												<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
											</a>
										</div>
										<div class="card-body-box">
											<h3 class="card-product-title">
												<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $title ); ?></a>
											</h3>
											<div class="card-price-box">
												<?php if ( $reg_price && $reg_price > $sale_price ) : ?>
													<del>৳<?php echo esc_html( $reg_price ); ?></del>
												<?php endif; ?>
												<span class="card-current-price">৳<?php echo esc_html( $sale_price ); ?></span>
											</div>
											<div class="card-action-btns">
												<a href="<?php echo esc_url( $add_to_cart_url ); ?>" 
												   data-quantity="1" 
												   class="button product_type_<?php echo esc_attr( $product_type ); ?> add_to_cart_button ajax_add_to_cart card-add-cart-btn" 
												   data-product_id="<?php echo esc_attr( $product_id ); ?>" 
												   data-product_sku="<?php echo esc_attr( $sku ); ?>" 
												   aria-label="<?php echo esc_attr( $product->add_to_cart_description() ); ?>" 
												   title="<?php echo esc_attr( ! empty( $add_to_cart_text ) ? $add_to_cart_text : 'Add to Cart' ); ?>" 
												   rel="nofollow">
													<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
												</a>
												<a href="<?php echo esc_url( $order_now_url ); ?>" class="card-order-btn">
													<?php echo esc_html( alishop_get_order_button_text() ); ?>
												</a>
											</div>
										</div>
									</div>
									<?php
								}

								// Numbered Pagination
								echo '<div class="shop-pagination-wrapper">';
								echo paginate_links( array(
									'total'     => $shop_query->max_num_pages,
									'current'   => $paged,
									'prev_text' => '← Prev',
									'next_text' => 'Next →',
								) );
								echo '</div>';

								wp_reset_postdata();
							}
						}

						// Demo Fallback Products (Matching screenshot items) if no products returned
						if ( ! $has_products ) {
							$demo_shop_items = array(
								array('title' => 'Bed Mosquito Net With Pillow', 'reg' => 4000, 'sale' => 3200, 'off' => 20, 'img' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=500&auto=format&fit=crop'),
								array('title' => 'Mosquito Net With Pillow', 'reg' => 2500, 'sale' => 2050, 'off' => 18, 'img' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=500&auto=format&fit=crop'),
								array('title' => 'Hand Plane', 'reg' => 500, 'sale' => 190, 'off' => 62, 'img' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=500&auto=format&fit=crop'),
								array('title' => 'Flying Glider Aircraft Plane - Hand Throw Motor...', 'reg' => 1000, 'sale' => 590, 'off' => 41, 'img' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=500&auto=format&fit=crop'),
								array('title' => 'Multifunctional Emergency Rechargeable Tube Light', 'reg' => 2000, 'sale' => 1400, 'off' => 30, 'img' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&auto=format&fit=crop'),
								array('title' => 'Electric SS Steel Water Bottle', 'reg' => 1000, 'sale' => 590, 'off' => 41, 'img' => 'https://images.unsplash.com/photo-1523362628745-0c100150b504?w=500&auto=format&fit=crop'),
								array('title' => 'Plastic Bottle Healthy Small', 'reg' => 1000, 'sale' => 590, 'off' => 41, 'img' => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=500&auto=format&fit=crop'),
								array('title' => 'Plastic Bottle Healthy Big', 'reg' => 1000, 'sale' => 650, 'off' => 35, 'img' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&auto=format&fit=crop'),
								array('title' => 'Sports Plastic Water Bottle', 'reg' => 1000, 'sale' => 650, 'off' => 35, 'img' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=500&auto=format&fit=crop'),
								array('title' => 'Standing Bubble', 'reg' => 2000, 'sale' => 1590, 'off' => 21, 'img' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=500&auto=format&fit=crop'),
								array('title' => 'Portable Electric Juicer Machine', 'reg' => 2500, 'sale' => 1890, 'off' => 24, 'img' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=500&auto=format&fit=crop'),
								array('title' => 'Tire Repair kit', 'reg' => 500, 'sale' => 250, 'off' => 50, 'img' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&auto=format&fit=crop'),
								array('title' => 'Air Bean Bag with hand pumper', 'reg' => 4600, 'sale' => 2450, 'off' => 47, 'img' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=500&auto=format&fit=crop'),
								array('title' => 'Smart Water Bottle 300ml', 'reg' => 1500, 'sale' => 650, 'off' => 57, 'img' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=500&auto=format&fit=crop'),
								array('title' => 'Portable Electric Shower', 'reg' => 2800, 'sale' => 2670, 'off' => 5, 'img' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=500&auto=format&fit=crop')
							);

							foreach ( $demo_shop_items as $d_item ) {
								?>
								<div class="alishop-product-card-item">
									<div class="card-thumb-box">
										<span class="card-discount-tag"><?php echo esc_html( $d_item['off'] ); ?>% OFF</span>
										<a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url('/product-details/') ); ?>">
											<img src="<?php echo esc_url( $d_item['img'] ); ?>" alt="<?php echo esc_attr( $d_item['title'] ); ?>">
										</a>
									</div>
									<div class="card-body-box">
										<h3 class="card-product-title">
											<a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url('/product-details/') ); ?>"><?php echo esc_html( $d_item['title'] ); ?></a>
										</h3>
										<div class="card-price-box">
											<del>৳<?php echo esc_html( $d_item['reg'] ); ?></del>
											<span class="card-current-price">৳<?php echo esc_html( $d_item['sale'] ); ?></span>
										</div>
										<div class="card-action-btns">
											<a href="<?php echo esc_url( class_exists('WooCommerce') ? wc_get_cart_url() : home_url('/product-details/') ); ?>" class="card-add-cart-btn" aria-label="Add to Cart" title="Add to Cart">
												<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
											</a>
											<a href="<?php echo esc_url( class_exists('WooCommerce') ? wc_get_checkout_url() : home_url('/checkout/') ); ?>" class="card-order-btn">
												<?php echo esc_html( alishop_get_order_button_text() ); ?>
											</a>
										</div>
									</div>
								</div>
								<?php
							}
						}
						?>
					</div>

				</section>

			</div>

			<!-- Trust Features Bar (Matching Photo) -->
			<section class="alishop-trust-bar-container">
				<div class="trust-item">
					<div class="trust-icon-box">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#FF6600" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
					</div>
					<div class="trust-text-box">
						<h4>Happy Customers</h4>
						<p>Reliable reviews</p>
					</div>
				</div>
				<div class="trust-item">
					<div class="trust-icon-box">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#FF6600" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="M9 12l2 2 4-4"></path></svg>
					</div>
					<div class="trust-text-box">
						<h4>Secure Payment</h4>
						<p>100% Safe transactions</p>
					</div>
				</div>
				<div class="trust-item">
					<div class="trust-icon-box">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#FF6600" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
					</div>
					<div class="trust-text-box">
						<h4>Fast Delivery</h4>
						<p>Doorstep items delivery</p>
					</div>
				</div>
				<div class="trust-item">
					<div class="trust-icon-box">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#FF6600" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
					</div>
					<div class="trust-text-box">
						<h4>100% Satisfaction<br class="mob-br"> Guarantee</h4>
						<p>100% Satisfaction Guarantee</p>
					</div>
				</div>
			</section>

		</form>

	</div>
</main>

<script>
function alishopSyncPriceSlider(val) {
	document.getElementById('maxPriceInput').value = val;
}
function alishopSetPricePreset(minVal, maxVal) {
	document.getElementById('minPriceInput').value = minVal;
	document.getElementById('maxPriceInput').value = maxVal;
	document.getElementById('alishopShopFilterForm').submit();
}
function alishopToggleShopMobileFilter() {
	var sidebar = document.querySelector('.alishop-shop-sidebar');
	if (sidebar) {
		sidebar.classList.toggle('mobile-active');
	}
}
</script>

<?php
get_footer();
