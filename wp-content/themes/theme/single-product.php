<?php
/**
 * The template for displaying WooCommerce Single Products & Custom Product Details
 * Matching EasyLifeDaily Specification
 *
 * @package EasyLifeDaily
 */

get_header();
?>

<main id="primary" class="site-main alishop-product-page-main">
    <div class="alishop-container">

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php 
            global $product;
            if ( class_exists( 'WooCommerce' ) && is_a( $product, 'WC_Product' ) ) {
                $product_id        = $product->get_id();
                $regular_price     = $product->get_regular_price();
                $sale_price        = $product->get_sale_price();
                $current_price     = $product->get_price();
                $title             = get_the_title();
                $image_id          = $product->get_image_id();
                $main_image_src    = wp_get_attachment_image_url( $image_id, 'full' );
                $attachment_ids    = $product->get_gallery_image_ids();
                
                // Calculate Discount Percentage
                $discount_percentage = 0;
                if ( $regular_price && $sale_price && $regular_price > $sale_price ) {
                    $discount_percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                }
            } else {
                // Demo fallback data when WooCommerce is not active or product is simple post
                $product_id        = get_the_ID();
                $regular_price     = '800';
                $sale_price        = '490';
                $current_price     = '490';
                $title             = get_the_title() ? get_the_title() : 'Helping Handle';
                $main_image_src    = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=800&auto=format&fit=crop';
                $attachment_ids    = array();
                $discount_percentage = 45;
            }

            // Fallback gallery images if none exist
            if ( empty( $main_image_src ) ) {
                $main_image_src = 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=800&auto=format&fit=crop';
            }
            ?>

            <!-- Top Main Product Card Box -->
            <div class="alishop-single-product-card">
                
                <!-- Left: Product Image Gallery -->
                <div class="alishop-product-gallery">
                    <div class="gallery-thumbs-vertical">
                        <div class="thumb-item active" onclick="alishopSwitchImage('<?php echo esc_url( $main_image_src ); ?>', this)">
                            <img src="<?php echo esc_url( $main_image_src ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                        </div>
                        <?php 
                        if ( ! empty( $attachment_ids ) ) :
                            foreach ( $attachment_ids as $attachment_id ) :
                                $thumb_src = wp_get_attachment_image_url( $attachment_id, 'full' );
                                ?>
                                <div class="thumb-item" onclick="alishopSwitchImage('<?php echo esc_url( $thumb_src ); ?>', this)">
                                    <img src="<?php echo esc_url( $thumb_src ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                                </div>
                                <?php
                            endforeach;
                        else :
                            // Demo gallery thumbnails if no gallery uploaded
                            $demo_thumbs = array(
                                'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=800&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=800&auto=format&fit=crop',
                                'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop'
                            );
                            foreach ( $demo_thumbs as $idx => $d_thumb ) :
                                ?>
                                <div class="thumb-item <?php echo $idx === 0 ? 'active' : ''; ?>" onclick="alishopSwitchImage('<?php echo esc_url( $d_thumb ); ?>', this)">
                                    <img src="<?php echo esc_url( $d_thumb ); ?>" alt="Gallery Image">
                                </div>
                                <?php
                            endforeach;
                        endif;
                        ?>
                    </div>

                    <div class="gallery-main-display" onclick="alishopOpenImageZoom()" title="Click to zoom image">
                        <img id="alishopMainProductImage" src="<?php echo esc_url( $main_image_src ); ?>" alt="<?php echo esc_attr( $title ); ?>">
                        <span class="click-to-view-badge" onclick="event.stopPropagation(); alishopOpenImageZoom();">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            CLICK TO VIEW
                        </span>
                    </div>
                </div>

                <!-- Right: Product Purchase & Contact Details -->
                <div class="alishop-product-summary">
                    <h1 class="product-main-title"><?php echo esc_html( $title ); ?></h1>
                    
                    <div class="product-price-row">
                        <?php if ( ! empty( $regular_price ) && $regular_price > $current_price ) : ?>
                            <del class="old-price">৳<?php echo esc_html( $regular_price ); ?></del>
                        <?php endif; ?>
                        
                        <span class="sale-price">৳<?php echo esc_html( $current_price ); ?></span>
                        
                        <?php if ( $discount_percentage > 0 ) : ?>
                            <span class="discount-pill-badge"><?php echo esc_html( $discount_percentage ); ?>% OFF</span>
                        <?php endif; ?>
                    </div>

                    <!-- Quantity Counter -->
                    <div class="product-quantity-row">
                        <span class="qty-label">Quantity:</span>
                        <div class="qty-stepper">
                            <button type="button" class="qty-btn minus" onclick="alishopUpdateQty(-1)">-</button>
                            <input type="number" id="alishopProductQty" value="1" min="1" max="99" readonly>
                            <button type="button" class="qty-btn plus" onclick="alishopUpdateQty(1)">+</button>
                        </div>
                    </div>

                    <!-- Action Buttons Grid (Exact Alishop 3-Row Design) -->
                    <div class="alishop-action-buttons-grid">
                        
                        <!-- Row 1: Add to Cart & Buy Now -->
                        <div class="action-row row-two-cols">
                            <button type="button" class="alishop-btn btn-add-cart-black" onclick="alishopAddToCart('<?php echo esc_js($product_id); ?>', document.getElementById('alishopProductQty') ? document.getElementById('alishopProductQty').value : 1)">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                                ADD TO CART
                            </button>

                            <?php 
                            $checkout_url = class_exists( 'WooCommerce' ) ? wc_get_checkout_url() : home_url( '/checkout/' );
                            $buy_now_url  = add_query_arg( 'add-to-cart', $product_id, $checkout_url );
                            ?>
                            <a href="<?php echo esc_url( $buy_now_url ); ?>" class="alishop-btn alishop-buy-now-btn alishop-pulse-anim" onclick="alishopBuyNow('<?php echo esc_js($product_id); ?>'); return false;">
                                <?php echo esc_html( alishop_get_order_button_text() ); ?>
                            </a>
                        </div>

                        <!-- Row 2: Messenger & Chat Now (WhatsApp) -->
                        <div class="action-row row-two-cols">
                            <?php $messenger_link = get_theme_mod( 'alishop_messenger_link', 'https://www.facebook.com/profile.php?id=61570898863722' ); ?>
                            <a href="<?php echo esc_url( $messenger_link ); ?>" target="_blank" rel="noopener" class="alishop-btn btn-messenger-blue">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.477 2 2 6.145 2 11.258c0 2.909 1.45 5.518 3.725 7.194V22l3.419-1.877a10.42 10.42 0 002.856.393c5.523 0 10-4.145 10-9.258C22 6.145 17.523 2 12 2zm1.002 12.441l-2.548-2.72-4.97 2.72 5.467-5.803 2.611 2.72 4.907-2.72-5.467 5.803z"/></svg>
                                Messenger
                            </a>

                            <?php 
                            $wa_num = get_theme_mod( 'alishop_whatsapp_number', '01617533063' );
                            $clean_wa = preg_replace( '/[^0-9]/', '', $wa_num );
                            if ( substr( $clean_wa, 0, 2 ) !== '88' && strlen( $clean_wa ) === 11 ) {
                                $clean_wa = '88' . $clean_wa;
                            }
                            $wa_msg = rawurlencode( "Hi EasyLifeDaily, I want to buy " . $title . " (Price: ৳" . $current_price . ")" );
                            ?>
                            <a href="https://wa.me/<?php echo esc_attr( $clean_wa ); ?>?text=<?php echo $wa_msg; ?>" target="_blank" rel="noopener" class="alishop-btn btn-whatsapp-green">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                Chat Now
                            </a>
                        </div>

                        <!-- Row 3: Live Call (Full Width Dark Navy) -->
                        <div class="action-row row-full-col">
                            <?php $phone = get_theme_mod( 'alishop_phone_number', '01617533063' ); ?>
                            <a href="tel:<?php echo esc_attr( preg_replace('/[^0-9+]/', '', $phone) ); ?>" class="alishop-btn btn-livecall-navy">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                Live Call
                            </a>
                        </div>
                    </div>
                </div>

            </div><!-- .alishop-single-product-card -->

            <!-- Description Section -->
            <div class="alishop-description-block">
                <div class="description-tab-header">
                    <span class="tab-title active">DESCRIPTION</span>
                </div>
                <div class="description-content-body">
                    <?php 
                    if ( get_the_content() ) {
                        the_content();
                    } else {
                        ?>
                        <h3>Helping Handle – বাথরুমে নিরাপদ সাপোর্ট, আর আত্মবিশ্বাসী চলাফেরা</h3>
                        <h4>পিচ্ছিল বাথরুমে পড়ে যাওয়ার ভয়</h4>
                        <p>বাথরুম বা ভেজা ফ্লোরে একটু অসাবধান হলেই পিছলে যাওয়ার ঝুঁকি থাকে। বিশেষ করে বয়স্ক মানুষ, শিশু, গর্ভবতী নারী, অসুস্থ ব্যক্তি বা সার্জারির পর সেরে ওঠা কারও জন্য এই ঝুঁকি আরও বেশি। শাওয়ার নেওয়া, টয়লেট ব্যবহার করা বা দাঁড়ানো–বসার সময় যদি নির্ভরযোগ্য সাপোর্ট না থাকে, তাহলে ভরসা কমে যায় এবং প্রতিদিনের কাজও অস্বস্তিকর মনে হয়।</p>
                        
                        <br>
                        <h4>Helping Handle – সহজ, নিরাপদ ও ব্যবহারবান্ধব সাপোর্ট</h4>
                        <p>Helping Handle হলো একটি portable suction grab bar, যা বাথরুমে অতিরিক্ত সাপোর্ট দেওয়ার জন্য তৈরি। এটি টাইলস, কাচ, মার্বেল বা অন্য smooth surface-এ সহজে লাগানো যায়। কোনো ড্রিল, স্ক্রু বা দেয়ালে ক্ষতি ছাড়াই এটি ব্যবহার করা সম্ভব। ফলে আপনি দ্রুত এবং damage free bathroom safety handle পেয়ে যান, যা দৈনন্দিন জীবনে নিরাপত্তা ও কমফোর্ট দুটোই বাড়ায়।</p>
                        <?php
                    }
                    ?>
                </div>
            </div><!-- .alishop-description-block -->

            <?php 
            $product_faqs = alishop_get_product_faqs( $product_id );
            if ( ! empty( $product_faqs ) ) : 
            ?>
                <!-- Dynamic FAQ Section -->
                <div class="alishop-faq-block">
                    <h3 class="faq-section-title">(FAQ)</h3>
                    
                    <div class="faq-accordion-list">
                        <?php foreach ( $product_faqs as $faq_item ) : ?>
                            <div class="faq-item">
                                <button type="button" class="faq-question" onclick="alishopToggleFaq(this)">
                                    <span><?php echo esc_html( $faq_item['question'] ); ?></span>
                                    <span class="faq-arrow">▼</span>
                                </button>
                                <div class="faq-answer">
                                    <p><?php echo wp_kses_post( nl2br( $faq_item['answer'] ) ); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div><!-- .alishop-faq-block -->
            <?php endif; ?>

            <!-- Related Products Section (সম্পর্কিত পণ্যসমূহ) -->
            <div class="alishop-related-products-block">
                <div class="section-header-title">
                    <h2>সম্পর্কিত পণ্যসমূহ</h2>
                    <div class="carousel-nav-arrows">
                        <button type="button" class="carousel-arrow prev" onclick="alishopSlideRelated(-1)">‹</button>
                        <button type="button" class="carousel-arrow next" onclick="alishopSlideRelated(1)">›</button>
                    </div>
                </div>

                <div class="related-slider-container">
                    <div class="related-slider-track" id="alishopRelatedTrack">
                        
                        <?php 
                        // Query related WooCommerce products or render demo cards
                        $related_args = array(
                            'post_type'      => 'product',
                            'posts_per_page' => 6,
                            'post__not_in'   => array( $product_id )
                        );
                        $related_query = new WP_Query( $related_args );

                        if ( $related_query->have_posts() && class_exists('WooCommerce') ) :
                            while ( $related_query->have_posts() ) : $related_query->the_post();
                                global $product;
                                $r_price = $product->get_price();
                                $r_reg   = $product->get_regular_price();
                                $r_disc  = 0;
                                if ( $r_reg && $r_price && $r_reg > $r_price ) {
                                    $r_disc = round( ( ($r_reg - $r_price) / $r_reg ) * 100 );
                                }
                                ?>
                                <div class="related-card-item">
                                    <div class="rel-thumb">
                                        <?php if ( $r_disc > 0 ) : ?>
                                            <span class="rel-discount"><?php echo esc_html($r_disc); ?>% OFF</span>
                                        <?php endif; ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium'); ?>
                                        </a>
                                    </div>
                                    <div class="rel-info">
                                        <h4 class="rel-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                        <div class="rel-price-row">
                                            <?php if ( $r_reg > $r_price ) : ?>
                                                <del>৳<?php echo esc_html($r_reg); ?></del>
                                            <?php endif; ?>
                                            <span class="rel-price">৳<?php echo esc_html($r_price); ?></span>
                                        </div>
                                        <?php 
                                        $checkout_page_url = class_exists( 'WooCommerce' ) ? wc_get_checkout_url() : home_url( '/checkout/' );
                                        $buy_now_url       = add_query_arg( 'add-to-cart', get_the_ID(), $checkout_page_url );
                                        ?>
                                        <a href="<?php echo esc_url( $buy_now_url ); ?>" class="alishop-btn alishop-buy-now-btn alishop-pulse-anim rel-buy-btn">
                                            <?php echo esc_html( alishop_get_order_button_text() ); ?>
                                        </a>
                                    </div>
                                </div>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                            // Demo fallback cards matching photos
                            $demo_products = array(
                                array('title' => 'Bed Mosquito Net With Pillow', 'reg' => 4000, 'sale' => 3200, 'off' => 20, 'img' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=500&auto=format&fit=crop'),
                                array('title' => 'Mosquito Net With Pillow', 'reg' => 2500, 'sale' => 2050, 'off' => 18, 'img' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=500&auto=format&fit=crop'),
                                array('title' => 'Multifunctional Emergency Rechargeable Tube Light', 'reg' => 2000, 'sale' => 1400, 'off' => 30, 'img' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=500&auto=format&fit=crop'),
                                array('title' => 'Electric SS Steel Water Bottle', 'reg' => 1000, 'sale' => 590, 'off' => 41, 'img' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=500&auto=format&fit=crop'),
                                array('title' => 'Plastic Bottle Healthy Small', 'reg' => 1000, 'sale' => 590, 'off' => 41, 'img' => 'https://images.unsplash.com/photo-1523362628745-0c100150b504?w=500&auto=format&fit=crop')
                            );
                            $demo_checkout_url = class_exists( 'WooCommerce' ) ? wc_get_checkout_url() : home_url( '/checkout/' );
                            foreach ( $demo_products as $dp ) :
                                ?>
                                <div class="related-card-item">
                                    <div class="rel-thumb">
                                        <span class="rel-discount"><?php echo esc_html($dp['off']); ?>% OFF</span>
                                        <a href="#"><img src="<?php echo esc_url($dp['img']); ?>" alt="<?php echo esc_attr($dp['title']); ?>"></a>
                                    </div>
                                    <div class="rel-info">
                                        <h4 class="rel-title"><a href="#"><?php echo esc_html($dp['title']); ?></a></h4>
                                        <div class="rel-price-row">
                                            <del>৳<?php echo esc_html($dp['reg']); ?></del>
                                            <span class="rel-price">৳<?php echo esc_html($dp['sale']); ?></span>
                                        </div>
                                        <a href="<?php echo esc_url( $demo_checkout_url ); ?>" class="alishop-btn alishop-buy-now-btn alishop-pulse-anim rel-buy-btn">
                                            <?php echo esc_html( alishop_get_order_button_text() ); ?>
                                        </a>
                                    </div>
                                </div>
                                <?php
                            endforeach;
                        endif;
                        ?>

                    </div>
                </div>

                <div class="carousel-dots-row related-dots-row" id="relatedDotsRow">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot active-pill-demo" style="display:none;"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </div><!-- .alishop-related-products-block -->

        <?php endwhile; endif; ?>

    </div>

    <!-- Image Zoom & Lightbox Modal -->
    <div id="alishopZoomModal" class="alishop-zoom-modal" aria-hidden="true" role="dialog">
        <div class="zoom-modal-overlay" onclick="alishopCloseImageZoom()"></div>
        <div class="zoom-modal-dialog">
            <button type="button" class="zoom-modal-close" onclick="alishopCloseImageZoom()" aria-label="Close Zoom">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
            <div class="zoom-modal-toolbar">
                <button type="button" class="zoom-tool-btn" id="zoomToggleBtn" onclick="alishopToggleZoomLevel(event)" title="Toggle Zoom In/Out">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="11" y1="8" x2="11" y2="14"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
                    <span id="zoomLevelText">Zoom 2x</span>
                </button>
            </div>
            <div class="zoom-image-container" onclick="alishopToggleZoomLevel(event)">
                <img id="alishopZoomedImage" src="<?php echo esc_url( $main_image_src ); ?>" alt="<?php echo esc_attr( $title ); ?>">
            </div>
            <button type="button" class="zoom-modal-nav prev" onclick="alishopZoomNav(-1)" aria-label="Previous Image">‹</button>
            <button type="button" class="zoom-modal-nav next" onclick="alishopZoomNav(1)" aria-label="Next Image">›</button>
        </div>
    </div>
</main>

<script>
// Image Zoom & Lightbox
var isZoomedIn = false;

function alishopOpenImageZoom() {
    var mainImg = document.getElementById('alishopMainProductImage');
    if (!mainImg) return;
    var zoomImg = document.getElementById('alishopZoomedImage');
    if (zoomImg) {
        zoomImg.src = mainImg.src;
    }
    isZoomedIn = false;
    updateZoomState();
    var modal = document.getElementById('alishopZoomModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function alishopCloseImageZoom() {
    var modal = document.getElementById('alishopZoomModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
    isZoomedIn = false;
    updateZoomState();
}

function alishopToggleZoomLevel(e) {
    if (e && e.stopPropagation) e.stopPropagation();
    isZoomedIn = !isZoomedIn;
    updateZoomState();
}

function updateZoomState() {
    var zoomImg = document.getElementById('alishopZoomedImage');
    var zoomText = document.getElementById('zoomLevelText');
    if (zoomImg) {
        if (isZoomedIn) {
            zoomImg.classList.add('zoomed-in');
        } else {
            zoomImg.classList.remove('zoomed-in');
        }
    }
    if (zoomText) {
        zoomText.innerText = isZoomedIn ? 'Reset Fit' : 'Zoom 2x';
    }
}

function alishopZoomNav(direction) {
    var thumbs = document.querySelectorAll('.gallery-thumbs-vertical .thumb-item img');
    if (!thumbs || thumbs.length <= 1) return;
    
    var currentSrc = document.getElementById('alishopZoomedImage').src;
    var currentIndex = 0;
    thumbs.forEach(function(img, idx) {
        if (img.src === currentSrc) currentIndex = idx;
    });
    
    var newIndex = currentIndex + direction;
    if (newIndex < 0) newIndex = thumbs.length - 1;
    if (newIndex >= thumbs.length) newIndex = 0;
    
    var newSrc = thumbs[newIndex].src;
    document.getElementById('alishopZoomedImage').src = newSrc;
    document.getElementById('alishopMainProductImage').src = newSrc;
    
    var thumbItems = document.querySelectorAll('.gallery-thumbs-vertical .thumb-item');
    thumbItems.forEach(function(item){ item.classList.remove('active'); });
    if (thumbItems[newIndex]) thumbItems[newIndex].classList.add('active');
    
    isZoomedIn = false;
    updateZoomState();
}

document.addEventListener('keydown', function(e) {
    var modal = document.getElementById('alishopZoomModal');
    if (modal && modal.classList.contains('active')) {
        if (e.key === 'Escape') alishopCloseImageZoom();
        if (e.key === 'ArrowLeft') alishopZoomNav(-1);
        if (e.key === 'ArrowRight') alishopZoomNav(1);
    }
});

// Buy Now -> Direct to Checkout with chosen quantity
function alishopBuyNow(productId, qty) {
    if (!qty) {
        var qtyInput = document.getElementById('alishopProductQty');
        qty = qtyInput ? (parseInt(qtyInput.value) || 1) : 1;
    }
    var checkoutUrl = '<?php echo esc_js( class_exists("WooCommerce") ? wc_get_checkout_url() : home_url("/checkout/") ); ?>';
    var sep = checkoutUrl.indexOf('?') !== -1 ? '&' : '?';
    window.location.href = checkoutUrl + sep + 'add-to-cart=' + productId + '&quantity=' + qty;
}

// Image gallery switcher
function alishopSwitchImage(src, element) {
    document.getElementById('alishopMainProductImage').src = src;
    var items = document.querySelectorAll('.thumb-item');
    items.forEach(function(item){ item.classList.remove('active'); });
    if(element) { element.classList.add('active'); }
}

// Quantity stepper
function alishopUpdateQty(delta) {
    var qtyInput = document.getElementById('alishopProductQty');
    var currentVal = parseInt(qtyInput.value) || 1;
    var newVal = currentVal + delta;
    if(newVal < 1) newVal = 1;
    qtyInput.value = newVal;

    var unitPrice = parseFloat(document.getElementById('summaryProdPrice').innerText) || <?php echo esc_js($current_price); ?>;
    document.getElementById('summaryProdQty').innerText = newVal;
    
    var subtotal = unitPrice * newVal;
    document.getElementById('summarySubtotal').innerText = subtotal;
    
    var shipping = parseFloat(document.querySelector('input[name="shipping_area"]:checked').value) || 60;
    var total = subtotal + shipping;
    document.getElementById('summaryTotal').innerText = total;
    document.getElementById('btnTotalText').innerText = total;
}

// Delivery charge calculator
function alishopCalculateTotal(shippingVal) {
    var radioLabels = document.querySelectorAll('.shipping-radio-label');
    radioLabels.forEach(function(lbl){ lbl.classList.remove('active'); });
    event.target.closest('.shipping-radio-label').classList.add('active');

    document.getElementById('summaryShipping').innerText = shippingVal;
    var subtotal = parseFloat(document.getElementById('summarySubtotal').innerText) || <?php echo esc_js($current_price); ?>;
    var total = subtotal + shippingVal;
    document.getElementById('summaryTotal').innerText = total;
    document.getElementById('btnTotalText').innerText = total;
}

// FAQ Accordion toggle
function alishopToggleFaq(btn) {
    var faqItem = btn.closest('.faq-item');
    var isOpen = faqItem.classList.contains('active');
    
    var allItems = document.querySelectorAll('.faq-item');
    allItems.forEach(function(item){ item.classList.remove('active'); });
    
    if(!isOpen) {
        faqItem.classList.add('active');
    }
}

// Related products slider navigation
var currentSlide = 0;
function alishopSlideRelated(direction) {
    var track = document.getElementById('alishopRelatedTrack');
    var cards = document.querySelectorAll('.related-card-item');
    if(cards.length === 0) return;
    
    var cardWidth = cards[0].offsetWidth + 16; // width + gap
    var maxSlides = cards.length - 4;
    if(maxSlides < 0) maxSlides = 0;

    currentSlide += direction;
    if(currentSlide < 0) currentSlide = 0;
    if(currentSlide > maxSlides) currentSlide = maxSlides;

    track.style.transform = 'translateX(-' + (currentSlide * cardWidth) + 'px)';
}

// Mobile scroll listener for related products carousel dots
document.addEventListener('DOMContentLoaded', function() {
    var relTrack = document.getElementById('alishopRelatedTrack');
    if(relTrack) {
        relTrack.addEventListener('scroll', function() {
            if(window.innerWidth <= 768) {
                var scrollLeft = relTrack.scrollLeft;
                var itemWidth = relTrack.firstElementChild ? (relTrack.firstElementChild.offsetWidth + 12) : 170;
                var activeIndex = Math.round(scrollLeft / (itemWidth * 2));
                var dots = document.querySelectorAll('#relatedDotsRow .dot');
                if(dots.length) {
                    dots.forEach(function(dot, idx) {
                        if(idx === activeIndex) {
                            dot.classList.add('active');
                        } else {
                            dot.classList.remove('active');
                        }
                    });
                }
            }
        });
    }
});

// Quick Order submit handler
function alishopSubmitOrder(e) {
    e.preventDefault();
    var name = document.getElementById('cust_name').value;
    var phone = document.getElementById('cust_phone').value;
    var total = document.getElementById('summaryTotal').innerText;

    alert('ধন্যবাদ ' + name + '! আপনার ৳' + total + ' টাকার অর্ডারটি সফলভাবে গ্রহন করা হয়েছে। শীঘ্রই কল করে অর্ডারটি কনফার্ম করা হবে।');
}
</script>

<?php
get_footer();
