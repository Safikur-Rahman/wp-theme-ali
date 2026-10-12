<?php
/**
 * The main template file for EasyLifeDaily BD
 * Homepage template for EasyLifeDaily.com
 *
 * @package EasyLifeDaily
 */

get_header();
?>

<main id="primary" class="site-main alishop-home-main">
	<div class="alishop-container">

		<?php if ( is_front_page() || is_home() ) : ?>

			<!-- 1. Hero Banner Slider Section (Fully Dynamic) -->
			<?php
			$hero_slides      = alishop_get_hero_slides();
			$hero_arrows      = alishop_get_hero_arrow_icons();
			$hero_width       = get_theme_mod( 'alishop_hero_width', '100%' );
			$hero_height      = get_theme_mod( 'alishop_hero_height', '360px' );
			$hero_height_mob  = get_theme_mod( 'alishop_hero_height_mobile', '220px' );
			$arrow_color      = get_theme_mod( 'alishop_hero_arrow_color', '#FFFFFF' );
			$arrow_bg         = get_theme_mod( 'alishop_hero_arrow_bg_color', '#F97316' );
			$arrow_hover_bg   = get_theme_mod( 'alishop_hero_arrow_hover_bg_color', '#EA580C' );
			$autoplay_enable  = get_theme_mod( 'alishop_hero_autoplay_enable', true );
			$slide_interval   = (int) get_theme_mod( 'alishop_hero_slide_interval', 6 ) * 1000;
			$transition_speed = get_theme_mod( 'alishop_hero_transition_speed', '0.6' );
			?>
			<style>
				.alishop-hero-slider-section {
					max-width: <?php echo esc_attr( $hero_width ); ?>;
					margin-left: auto;
					margin-right: auto;
					--hero-trans-speed: <?php echo esc_attr( $transition_speed ); ?>s;
					--hero-arrow-color: <?php echo esc_attr( $arrow_color ); ?>;
					--hero-arrow-bg: <?php echo esc_attr( $arrow_bg ); ?>;
					--hero-arrow-hover-bg: <?php echo esc_attr( $arrow_hover_bg ); ?>;
				}
				.hero-slide-item {
					height: <?php echo esc_attr( $hero_height ); ?>;
				}
				@media (max-width: 768px) {
					.hero-slide-item {
						height: <?php echo esc_attr( $hero_height_mob ); ?>;
					}
				}
			</style>

			<section class="alishop-hero-slider-section">
				<div class="hero-slider-container">
					<?php if ( count( $hero_slides ) > 1 ) : ?>
						<button type="button" class="hero-arrow hero-prev" onclick="alishopHeroSlide(-1)" aria-label="Previous Banner"><?php echo esc_html( $hero_arrows['prev'] ); ?></button>
					<?php endif; ?>
					<div class="hero-slider-track" id="alishopHeroTrack">
						<?php foreach ( $hero_slides as $index => $slide ) : ?>
							<div class="hero-slide-item <?php echo 0 === $index ? 'active' : ''; ?>">
								<a href="<?php echo esc_url( $slide['link'] ); ?>">
									<img src="<?php echo esc_url( $slide['img'] ); ?>" alt="<?php echo esc_attr( $slide['title'] ); ?>">
									<?php if ( ! empty( $slide['title'] ) || ! empty( $slide['subtitle'] ) ) : ?>
										<div class="hero-slide-overlay">
											<?php if ( ! empty( $slide['title'] ) ) : ?>
												<h2><?php echo esc_html( $slide['title'] ); ?></h2>
											<?php endif; ?>
											<?php if ( ! empty( $slide['subtitle'] ) ) : ?>
												<p><?php echo esc_html( $slide['subtitle'] ); ?></p>
											<?php endif; ?>
											<?php if ( ! empty( $slide['btn_text'] ) ) : ?>
												<span class="hero-shop-btn"><?php echo esc_html( $slide['btn_text'] ); ?></span>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
					<?php if ( count( $hero_slides ) > 1 ) : ?>
						<button type="button" class="hero-arrow hero-next" onclick="alishopHeroSlide(1)" aria-label="Next Banner"><?php echo esc_html( $hero_arrows['next'] ); ?></button>
						<div class="hero-carousel-dots-row">
							<?php foreach ( $hero_slides as $index => $slide ) : ?>
								<span class="hero-carousel-dot <?php echo 0 === $index ? 'active' : ''; ?>" onclick="alishopHeroSlide(<?php echo (int) $index; ?>)"></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</section>

			<?php
			$best_selling_demo = array(
				array('title' => 'Air Bean Bag with hand pumper', 'reg' => 4600, 'sale' => 2450, 'off' => 47, 'img' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=500&auto=format&fit=crop'),
				array('title' => 'Smart Water Bottle | Hip Flask 300ml Stainless Ste...', 'reg' => 1500, 'sale' => 650, 'off' => 57, 'img' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=500&auto=format&fit=crop'),
				array('title' => 'Smart Water Bottle | Hip Flask 250ml Stainless Ste...', 'reg' => 1100, 'sale' => 590, 'off' => 46, 'img' => 'https://images.unsplash.com/photo-1523362628745-0c100150b504?w=500&auto=format&fit=crop'),
				array('title' => 'Portable Electric Shower', 'reg' => 2800, 'sale' => 2670, 'off' => 5, 'img' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=500&auto=format&fit=crop'),
				array('title' => 'Helping Handle', 'reg' => 800, 'sale' => 490, 'off' => 45, 'img' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&auto=format&fit=crop'),
				array('title' => 'Butter Maker | 1.5L Manual Butter Churner', 'reg' => 6000, 'sale' => 3770, 'off' => 37, 'img' => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=500&auto=format&fit=crop')
			);

			$featured_demo = array(
				array('title' => 'Standing Spoon', 'reg' => 400, 'sale' => 250, 'off' => 38, 'img' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=500&auto=format&fit=crop'),
				array('title' => 'Mini Football Table Game | Tabletop Football Board...', 'reg' => 1550, 'sale' => 1450, 'off' => 6, 'img' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=500&auto=format&fit=crop'),
				array('title' => 'Mushroom Lamp | USB Rechargeable LED Touch...', 'reg' => 2500, 'sale' => 1590, 'off' => 36, 'img' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&auto=format&fit=crop'),
				array('title' => 'Mesh Nebulizer | Portable nebulizer Model JSL-W302', 'reg' => 900, 'sale' => 490, 'off' => 46, 'img' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=500&auto=format&fit=crop'),
				array('title' => 'Weight Scale | Digital Hanging Luggage & Hook Weig...', 'reg' => 1000, 'sale' => 890, 'off' => 11, 'img' => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=500&auto=format&fit=crop'),
				array('title' => 'Umrah Kaaba Saving Bank', 'reg' => 1490, 'sale' => 690, 'off' => 54, 'img' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&auto=format&fit=crop')
			);

			$all_demo_products = array(
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
				array('title' => 'Tire Repair kit', 'reg' => 500, 'sale' => 250, 'off' => 50, 'img' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&auto=format&fit=crop')
			);

			// Define homepage sections with custom order and enable status
			$homepage_sections = array(
				'bestselling' => array(
					'id'       => 'bestselling',
					'order'    => (int) get_theme_mod( 'alishop_bestselling_order', 1 ),
					'enabled'  => true,
					'title'    => get_theme_mod( 'alishop_bestselling_title', 'Best Selling Products' ),
					'subtitle' => get_theme_mod( 'alishop_bestselling_subtitle', 'Best selling products of the week' ),
					'category' => get_theme_mod( 'alishop_bestselling_category', '' ),
					'type'     => 'carousel',
					'track_id' => 'bestSellingTrack',
					'demo'     => $best_selling_demo,
				),
				'featured' => array(
					'id'       => 'featured',
					'order'    => (int) get_theme_mod( 'alishop_featured_order', 2 ),
					'enabled'  => true,
					'title'    => get_theme_mod( 'alishop_featured_title', 'Featured Collection' ),
					'subtitle' => get_theme_mod( 'alishop_featured_subtitle', 'Featured products of the week' ),
					'category' => get_theme_mod( 'alishop_featured_category', '' ),
					'type'     => 'carousel',
					'track_id' => 'featuredTrack',
					'demo'     => $featured_demo,
				),
				'custom1' => array(
					'id'       => 'custom1',
					'order'    => (int) get_theme_mod( 'alishop_custom1_order', 3 ),
					'enabled'  => (bool) get_theme_mod( 'alishop_custom1_enable', false ),
					'title'    => get_theme_mod( 'alishop_custom1_title', 'Special Category Deals' ),
					'subtitle' => get_theme_mod( 'alishop_custom1_subtitle', 'Top selected items for you' ),
					'category' => get_theme_mod( 'alishop_custom1_category', '' ),
					'type'     => 'carousel',
					'track_id' => 'custom1Track',
					'demo'     => $featured_demo,
				),
				'custom2' => array(
					'id'       => 'custom2',
					'order'    => (int) get_theme_mod( 'alishop_custom2_order', 4 ),
					'enabled'  => (bool) get_theme_mod( 'alishop_custom2_enable', false ),
					'title'    => get_theme_mod( 'alishop_custom2_title', 'Trending Gadgets & Appliances' ),
					'subtitle' => get_theme_mod( 'alishop_custom2_subtitle', 'Trending products of the month' ),
					'category' => get_theme_mod( 'alishop_custom2_category', '' ),
					'type'     => 'carousel',
					'track_id' => 'custom2Track',
					'demo'     => $best_selling_demo,
				),
				'allproducts' => array(
					'id'       => 'allproducts',
					'order'    => (int) get_theme_mod( 'alishop_allproducts_order', 5 ),
					'enabled'  => true,
					'title'    => get_theme_mod( 'alishop_allproducts_title', 'All Products' ),
					'subtitle' => get_theme_mod( 'alishop_allproducts_subtitle', 'All products of the week' ),
					'category' => '',
					'type'     => 'grid',
					'demo'     => $all_demo_products,
				),
			);

			// Sort sections by user-defined Display Order
			usort( $homepage_sections, function( $a, $b ) {
				return $a['order'] - $b['order'];
			} );

			// Render enabled sections in order
			foreach ( $homepage_sections as $sec ) {
				if ( ! $sec['enabled'] ) continue;

				if ( 'carousel' === $sec['type'] ) {
					?>
					<section class="alishop-home-section">
						<div class="section-title-row">
							<div class="title-with-subtitle">
								<h2 class="home-sec-title">
									<span class="flame-icon">🔥</span> <?php echo esc_html( $sec['title'] ); ?>
								</h2>
								<span class="home-sec-subtitle"><?php echo esc_html( $sec['subtitle'] ); ?></span>
							</div>
							<?php if ( class_exists( 'WooCommerce' ) ) : ?>
								<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="view-all-link">View All →</a>
							<?php else : ?>
								<a href="#all-products" class="view-all-link">View All →</a>
							<?php endif; ?>
						</div>

						<div class="slider-wrapper-relative">
							<button type="button" class="slider-nav-btn nav-left" onclick="alishopScrollSlider('<?php echo esc_js( $sec['track_id'] ); ?>', -1)">‹</button>
							<div class="products-carousel-track" id="<?php echo esc_attr( $sec['track_id'] ); ?>">
								<?php alishop_render_homepage_products( $sec['category'], 10, $sec['demo'] ); ?>
							</div>
							<button type="button" class="slider-nav-btn nav-right" onclick="alishopScrollSlider('<?php echo esc_js( $sec['track_id'] ); ?>', 1)">›</button>
						</div>
						<div class="carousel-dots-row">
							<span class="dot active"></span>
							<span class="dot"></span>
							<span class="dot"></span>
						</div>
					</section>
					<?php
				} elseif ( 'grid' === $sec['type'] ) {
					?>
					<section id="all-products" class="alishop-home-section">
						<div class="section-title-row">
							<div class="title-with-subtitle">
								<h2 class="home-sec-title">
									<span class="flame-icon">🔥</span> <?php echo esc_html( $sec['title'] ); ?>
								</h2>
								<span class="home-sec-subtitle"><?php echo esc_html( $sec['subtitle'] ); ?></span>
							</div>
						</div>

						<div class="alishop-all-products-grid">
							<?php alishop_render_homepage_products( '', 12, $sec['demo'] ); ?>
						</div>
					</section>
					<?php
				}
			}
			?>

			<!-- 5. Trust Features Bar (Matching Photo) -->
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

		<?php endif; ?>

		<!-- Standard Single Posts Grid if not front page -->
		<?php if ( have_posts() && ! is_front_page() ) : ?>
			<div id="recent-posts">
				<?php
				while ( have_posts() ) : the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="background: #fff; padding: 20px; margin-bottom: 20px; border-radius: 8px;">
						<header class="entry-header">
							<?php
							if ( is_singular() ) :
								the_title( '<h1 class="entry-title">', '</h1>' );
							else :
								the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
							endif;
							?>
						</header>
						<div class="entry-content">
							<?php if ( is_singular() ) { the_content(); } else { the_excerpt(); } ?>
						</div>
					</article>
					<?php
				endwhile;
				the_posts_navigation();
				?>
			</div>
		<?php endif; ?>

	</div>
</main>



<script>
// Hero Slider Dynamic Horizontal Carousel JavaScript
var currentHeroSlide = 0;
var heroIntervalTimer = null;
var heroAutoplayEnabled = <?php echo get_theme_mod( 'alishop_hero_autoplay_enable', true ) ? 'true' : 'false'; ?>;
var heroIntervalTime = <?php echo (int) get_theme_mod( 'alishop_hero_slide_interval', 6 ) * 1000; ?>;

function alishopHeroSlide(target) {
	var track = document.getElementById('alishopHeroTrack');
	var slides = document.querySelectorAll('.hero-slide-item');
	var dots = document.querySelectorAll('.hero-carousel-dot');
	if (!track || slides.length <= 1) return;

	if (target === 1) {
		currentHeroSlide = (currentHeroSlide + 1) % slides.length;
	} else if (target === -1) {
		currentHeroSlide = (currentHeroSlide - 1 + slides.length) % slides.length;
	} else if (typeof target === 'number') {
		currentHeroSlide = target;
	}

	track.style.transform = 'translateX(-' + (currentHeroSlide * 100) + '%)';

	slides.forEach(function(slide, idx){
		if (idx === currentHeroSlide) {
			slide.classList.add('active');
		} else {
			slide.classList.remove('active');
		}
	});

	dots.forEach(function(dot, idx){
		if (idx === currentHeroSlide) {
			dot.classList.add('active');
		} else {
			dot.classList.remove('active');
		}
	});
}

function startHeroAutoplay() {
	if (heroAutoplayEnabled && heroIntervalTime > 0) {
		stopHeroAutoplay();
		heroIntervalTimer = setInterval(function(){
			alishopHeroSlide(1);
		}, heroIntervalTime);
	}
}

function stopHeroAutoplay() {
	if (heroIntervalTimer) {
		clearInterval(heroIntervalTimer);
		heroIntervalTimer = null;
	}
}

document.addEventListener('DOMContentLoaded', function(){
	startHeroAutoplay();
	var heroContainer = document.querySelector('.alishop-hero-slider-section');
	if (heroContainer) {
		heroContainer.addEventListener('mouseenter', stopHeroAutoplay);
		heroContainer.addEventListener('mouseleave', startHeroAutoplay);

		// Touch swipe support on mobile devices
		var touchStartX = 0;
		var touchEndX = 0;
		heroContainer.addEventListener('touchstart', function(e){
			touchStartX = e.changedTouches[0].screenX;
		}, {passive: true});
		heroContainer.addEventListener('touchend', function(e){
			touchEndX = e.changedTouches[0].screenX;
			if (touchStartX - touchEndX > 50) {
				alishopHeroSlide(1);
			} else if (touchEndX - touchStartX > 50) {
				alishopHeroSlide(-1);
			}
		}, {passive: true});
	}
});

// Carousel Horizontal Scroll JavaScript
function alishopScrollSlider(trackId, direction) {
	var track = document.getElementById(trackId);
	if(!track) return;
	var scrollAmount = 240 * 2 * direction;
	track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
}
</script>

<?php
get_footer();
