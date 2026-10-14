<?php
/**
 * EasyLifeDaily functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package EasyLifeDaily
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function alishop_clone_setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'easylifedaily' ),
			'footer' => esc_html__( 'Footer Menu', 'easylifedaily' ),
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for core custom logo.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	// WooCommerce Support
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Elementor Support (Like Hello Elementor)
	add_theme_support( 'elementor' );
}
add_action( 'after_setup_theme', 'alishop_clone_setup' );

/**
 * Enable Elementor & CartFlows compatibility
 */
function alishop_elementor_cartflows_setup() {
	add_post_type_support( 'page', 'elementor' );
	add_post_type_support( 'post', 'elementor' );
	add_post_type_support( 'cartflows_step', 'elementor' );
}
add_action( 'init', 'alishop_elementor_cartflows_setup' );

// CartFlows Step Template Routing to ensure funnel pages load without theme conflicts
add_filter( 'template_include', 'alishop_cartflows_step_template_route', 999 );
function alishop_cartflows_step_template_route( $template ) {
	if ( is_singular( 'cartflows_step' ) ) {
		$step_template = get_template_directory() . '/single-cartflows_step.php';
		if ( file_exists( $step_template ) ) {
			return $step_template;
		}
	}
	return $template;
}

/**
 * Disable WooCommerce Coming Soon / Maintenance Mode to display site homepage
 */
add_action( 'init', 'alishop_disable_woocommerce_coming_soon', 1 );
function alishop_disable_woocommerce_coming_soon() {
	if ( get_option( 'woocommerce_coming_soon' ) !== 'no' ) {
		update_option( 'woocommerce_coming_soon', 'no' );
	}
	if ( get_option( 'woocommerce_store_pages_only' ) !== 'no' ) {
		update_option( 'woocommerce_store_pages_only', 'no' );
	}
}
add_filter( 'option_woocommerce_coming_soon', '__return_false' );
add_filter( 'option_woocommerce_store_pages_only', '__return_false' );

/**
 * Register global-styles dependency if missing to fix WP_Styles::add notice
 */
add_action( 'wp_default_styles', 'alishop_fix_missing_global_styles_dep' );
function alishop_fix_missing_global_styles_dep( $styles ) {
	if ( ! isset( $styles->registered['global-styles'] ) ) {
		$styles->add( 'global-styles', false );
	}
}

/**
 * Update and filter WordPress Site Title (blogname) to EasyLifeDaily for browser tabs
 */
add_action( 'init', 'easylifedaily_update_site_title', 2 );
function easylifedaily_update_site_title() {
	$current_title = get_option( 'blogname' );
	if ( empty( $current_title ) || false !== stripos( $current_title, 'Ali' ) || false !== stripos( $current_title, 'Clone' ) ) {
		update_option( 'blogname', 'EasyLifeDaily' );
	}
}
add_filter( 'option_blogname', 'easylifedaily_override_site_title' );
function easylifedaily_override_site_title( $title ) {
	if ( empty( $title ) || false !== stripos( $title, 'Ali' ) || false !== stripos( $title, 'Clone' ) ) {
		return 'EasyLifeDaily';
	}
	return $title;
}

/**
 * Enqueue scripts and styles.
 */
function alishop_clone_scripts() {
    // Google Fonts - Comprehensive English & Bengali fonts list
    wp_enqueue_style( 'alishop-clone-fonts', 'https://fonts.googleapis.com/css2?family=Atma:wght@400;600;700&family=Bebas+Neue&family=Cinzel:wght@400;600;700&family=Fira+Sans:wght@400;500;600;700&family=Galada&family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=Kanit:wght@400;500;600&family=Lato:wght@400;700&family=Mina:wght@400;700&family=Montserrat:wght@400;500;600;700&family=Mukta:wght@400;500;600;700&family=Noto+Sans+Bengali:wght@400;500;600;700&family=Noto+Serif+Bengali:wght@400;600;700&family=Nunito:wght@400;600;700&family=Open+Sans:wght@400;600;700&family=Oswald:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&family=PT+Sans:wght@400;700&family=Playfair+Display:wght@400;600;700&family=Poppins:wght@400;500;600;700&family=Quicksand:wght@400;600;700&family=Raleway:wght@400;500;600;700&family=Roboto:wght@400;500;700&family=Rubik:wght@400;500;600;700&family=Work+Sans:wght@400;500;600;700&display=swap', array(), null );
    
	wp_enqueue_style( 'alishop-clone-style', get_stylesheet_uri(), array(), _S_VERSION );
	
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_script( 'wc-add-to-cart' );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'alishop_clone_scripts' );

/**
 * Output Dynamic Header Menu Position & Typography Styles from Customizer
 */
add_action( 'wp_head', 'alishop_output_menu_customizer_css', 99 );
function alishop_output_menu_customizer_css() {
	$position       = get_theme_mod( 'alishop_menu_position', 'left' );
	$font_family    = get_theme_mod( 'alishop_menu_font_family', 'Poppins, sans-serif' );
	$font_size      = get_theme_mod( 'alishop_menu_font_size', '14' );
	$font_weight    = get_theme_mod( 'alishop_menu_font_weight', '600' );
	$text_transform = get_theme_mod( 'alishop_menu_text_transform', 'none' );
	$link_color     = get_theme_mod( 'alishop_menu_link_color', '#333333' );
	$hover_color    = get_theme_mod( 'alishop_menu_hover_color', '#ff6600' );

	$justify_map = array(
		'left'   => 'flex-start',
		'center' => 'center',
		'right'  => 'flex-end',
	);
	$justify_val = isset( $justify_map[ $position ] ) ? $justify_map[ $position ] : 'flex-start';

	?>
	<style id="alishop-menu-customizer-css">
		.navigation-inner {
			justify-content: <?php echo esc_html( $justify_val ); ?> !important;
		}
		.nav-menu-list a {
			font-family: <?php echo esc_html( $font_family ); ?> !important;
			font-size: <?php echo esc_html( intval( $font_size ) ); ?>px !important;
			font-weight: <?php echo esc_html( $font_weight ); ?> !important;
			text-transform: <?php echo esc_html( $text_transform ); ?> !important;
			color: <?php echo esc_html( $link_color ); ?> !important;
		}
		.nav-menu-list a:hover,
		.nav-menu-list .current-menu-item > a,
		.nav-menu-list li.active > a {
			color: <?php echo esc_html( $hover_color ); ?> !important;
			border-bottom-color: <?php echo esc_html( $hover_color ); ?> !important;
		}
	</style>
	<?php
}

/**
 * Register widget area.
 */
function alishop_clone_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'alishop-clone' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'alishop-clone' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 1', 'alishop-clone' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'alishop-clone' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'alishop_clone_widgets_init' );

/**
 * Customizer settings for Contact & Store Options
 */
function alishop_clone_customize_register( $wp_customize ) {
	// =========================================================================
	// Header Menu Position & Typography Options Section
	// =========================================================================
	$wp_customize->add_section( 'alishop_header_menu_options', array(
		'title'    => __( 'EasyLifeDaily Header Menu & Typography', 'easylifedaily' ),
		'priority' => 28,
	) );

	// 1. Menu Alignment Position
	$wp_customize->add_setting( 'alishop_menu_position', array(
		'default'           => 'left',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_menu_position', array(
		'label'    => __( 'Header Menu Alignment / Position (পজিশন)', 'alishop-clone' ),
		'section'  => 'alishop_header_menu_options',
		'type'     => 'select',
		'choices'  => array(
			'left'   => __( 'Left (বাম)', 'alishop-clone' ),
			'center' => __( 'Center (মাঝখানে)', 'alishop-clone' ),
			'right'  => __( 'Right (ডান)', 'alishop-clone' ),
		),
	) );

	// 2. Menu Font Family
	$wp_customize->add_setting( 'alishop_menu_font_family', array(
		'default'           => 'Poppins, sans-serif',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_menu_font_family', array(
		'label'    => __( 'Menu Font Family (ফন্ট টাইপ)', 'alishop-clone' ),
		'section'  => 'alishop_header_menu_options',
		'type'     => 'select',
		'choices'  => array(
			// Popular Sans-Serif Fonts
			'Poppins, sans-serif'                     => 'Poppins (Popular)',
			'Roboto, sans-serif'                      => 'Roboto',
			'Inter, sans-serif'                       => 'Inter',
			'Outfit, sans-serif'                      => 'Outfit',
			"'Open Sans', sans-serif"                 => 'Open Sans',
			'Lato, sans-serif'                        => 'Lato',
			'Montserrat, sans-serif'                  => 'Montserrat',
			'Oswald, sans-serif'                      => 'Oswald',
			'Raleway, sans-serif'                     => 'Raleway',
			'Nunito, sans-serif'                      => 'Nunito',
			'Rubik, sans-serif'                       => 'Rubik',
			'Mukta, sans-serif'                       => 'Mukta',
			"'Work Sans', sans-serif"                 => 'Work Sans',
			'Kanit, sans-serif'                       => 'Kanit',
			'Fira Sans, sans-serif'                   => 'Fira Sans',
			'Quicksand, sans-serif'                   => 'Quicksand',
			'PT Sans, sans-serif'                     => 'PT Sans',
			'Bebas Neue, sans-serif'                  => 'Bebas Neue',

			// Serif & Display Fonts
			"'Playfair Display', serif"               => 'Playfair Display (Serif)',
			'Cinzel, serif'                           => 'Cinzel (Serif)',
			'Georgia, serif'                          => 'Georgia (Serif)',
			"'Times New Roman', Times, serif"         => 'Times New Roman (Serif)',

			// Popular Bengali Fonts (বাংলা ফন্ট)
			"'Hind Siliguri', sans-serif"             => 'Hind Siliguri (বাংলা - Hind Siliguri)',
			"'Noto Sans Bengali', sans-serif"         => 'Noto Sans Bengali (বাংলা - Noto Sans)',
			"'Noto Serif Bengali', serif"             => 'Noto Serif Bengali (বাংলা - Noto Serif)',
			"'Galada', cursive"                       => 'Galada (বাংলা - Galada)',
			"'Mina', sans-serif"                      => 'Mina (বাংলা - Mina)',
			"'Atma', cursive"                         => 'Atma (বাংলা - Atma)',
			"'Kalpurush', 'SolaimanLipi', sans-serif" => 'Kalpurush / SolaimanLipi (বাংলা)',

			// System Web-Safe Fonts
			'Arial, Helvetica, sans-serif'            => 'Arial',
			"'Helvetica Neue', Helvetica, sans-serif" => 'Helvetica Neue',
			"'Segoe UI', Tahoma, Geneva, sans-serif"  => 'Segoe UI',
			'Tahoma, Geneva, sans-serif'              => 'Tahoma',
			'Verdana, Geneva, sans-serif'             => 'Verdana',
			"'Trebuchet MS', sans-serif"              => 'Trebuchet MS',
			"'Courier New', Courier, monospace"       => 'Courier New (Monospace)',
			'inherit'                                 => 'Default / Theme Inherit',
		),
	) );

	// 3. Menu Font Size (px)
	$wp_customize->add_setting( 'alishop_menu_font_size', array(
		'default'           => '14',
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'alishop_menu_font_size', array(
		'label'       => __( 'Menu Font Size in px (ফন্ট সাইজ)', 'alishop-clone' ),
		'section'     => 'alishop_header_menu_options',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 10,
			'max'  => 30,
			'step' => 1,
		),
	) );

	// 4. Menu Font Weight
	$wp_customize->add_setting( 'alishop_menu_font_weight', array(
		'default'           => '600',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_menu_font_weight', array(
		'label'    => __( 'Menu Font Weight (ফন্ট ওয়েট)', 'alishop-clone' ),
		'section'  => 'alishop_header_menu_options',
		'type'     => 'select',
		'choices'  => array(
			'400' => 'Normal (400)',
			'500' => 'Medium (500)',
			'600' => 'Semi-Bold (600)',
			'700' => 'Bold (700)',
		),
	) );

	// 5. Menu Text Transform
	$wp_customize->add_setting( 'alishop_menu_text_transform', array(
		'default'           => 'none',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_menu_text_transform', array(
		'label'    => __( 'Menu Text Case / Transform', 'alishop-clone' ),
		'section'  => 'alishop_header_menu_options',
		'type'     => 'select',
		'choices'  => array(
			'none'       => 'Default (As typed)',
			'uppercase'  => 'UPPERCASE',
			'capitalize' => 'Capitalize Each Word',
			'lowercase'  => 'lowercase',
		),
	) );

	// 6. Menu Text Color
	$wp_customize->add_setting( 'alishop_menu_link_color', array(
		'default'           => '#333333',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'alishop_menu_link_color', array(
		'label'   => __( 'Menu Link Color (টেক্সট কালার)', 'alishop-clone' ),
		'section' => 'alishop_header_menu_options',
	) ) );

	// 7. Menu Hover Link Color
	$wp_customize->add_setting( 'alishop_menu_hover_color', array(
		'default'           => '#ff6600',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'alishop_menu_hover_color', array(
		'label'   => __( 'Menu Hover & Active Color (হোভার কালার)', 'alishop-clone' ),
		'section' => 'alishop_header_menu_options',
	) ) );

	// =========================================================================
	// Hero Banner Slider Options Section
	// =========================================================================
	$wp_customize->add_section( 'alishop_hero_slider_options', array(
		'title'    => __( 'EasyLifeDaily Hero Banner & Slider', 'easylifedaily' ),
		'priority' => 29,
	) );

	// 1. Banner Custom Container Width
	$wp_customize->add_setting( 'alishop_hero_width', array(
		'default'           => '100%',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_hero_width', array(
		'label'       => __( 'Banner Max Width (e.g. 100%, 1400px, 1200px)', 'alishop-clone' ),
		'section'     => 'alishop_hero_slider_options',
		'type'        => 'text',
	) );

	// 2. Banner Height (Desktop)
	$wp_customize->add_setting( 'alishop_hero_height', array(
		'default'           => '360px',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_hero_height', array(
		'label'       => __( 'Banner Desktop Height (e.g. 360px, 400px, 450px)', 'alishop-clone' ),
		'section'     => 'alishop_hero_slider_options',
		'type'        => 'text',
	) );

	// 3. Banner Height (Mobile & Tablet)
	$wp_customize->add_setting( 'alishop_hero_height_mobile', array(
		'default'           => 'auto',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_hero_height_mobile', array(
		'label'       => __( 'Banner Mobile & Tablet Height (e.g. auto, 220px - "auto" keeps full banner without crop)', 'alishop-clone' ),
		'section'     => 'alishop_hero_slider_options',
		'type'        => 'text',
	) );

	// 4. Hero Arrow Icon Style Selection
	$wp_customize->add_setting( 'alishop_hero_arrow_style', array(
		'default'           => 'standard',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_hero_arrow_style', array(
		'label'    => __( 'Hero Arrow Icon Style', 'alishop-clone' ),
		'section'  => 'alishop_hero_slider_options',
		'type'     => 'select',
		'choices'  => array(
			'standard' => __( 'Standard Angle (‹ / ›)', 'alishop-clone' ),
			'heavy'    => __( 'Bold Chevron (❮ / ❯)', 'alishop-clone' ),
			'arrow'    => __( 'Long Arrow (← / →)', 'alishop-clone' ),
			'triangle' => __( 'Solid Triangle (◀ / ▶)', 'alishop-clone' ),
			'custom'   => __( 'Custom Characters / Text', 'alishop-clone' ),
		),
	) );

	// 5. Custom Prev Arrow Character
	$wp_customize->add_setting( 'alishop_hero_arrow_prev_custom', array(
		'default'           => '‹',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_hero_arrow_prev_custom', array(
		'label'       => __( 'Custom Prev Arrow Icon/Text (Used if "Custom" selected)', 'alishop-clone' ),
		'section'     => 'alishop_hero_slider_options',
		'type'        => 'text',
	) );

	// 6. Custom Next Arrow Character
	$wp_customize->add_setting( 'alishop_hero_arrow_next_custom', array(
		'default'           => '›',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_hero_arrow_next_custom', array(
		'label'       => __( 'Custom Next Arrow Icon/Text (Used if "Custom" selected)', 'alishop-clone' ),
		'section'     => 'alishop_hero_slider_options',
		'type'        => 'text',
	) );

	// 7. Arrow Icon Color
	$wp_customize->add_setting( 'alishop_hero_arrow_color', array(
		'default'           => '#FFFFFF',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'alishop_hero_arrow_color', array(
		'label'    => __( 'Arrow Icon Color', 'alishop-clone' ),
		'section'  => 'alishop_hero_slider_options',
	) ) );

	// 8. Arrow Background Color
	$wp_customize->add_setting( 'alishop_hero_arrow_bg_color', array(
		'default'           => '#F97316',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'alishop_hero_arrow_bg_color', array(
		'label'    => __( 'Arrow Button Background Color', 'alishop-clone' ),
		'section'  => 'alishop_hero_slider_options',
	) ) );

	// 9. Arrow Hover Background Color
	$wp_customize->add_setting( 'alishop_hero_arrow_hover_bg_color', array(
		'default'           => '#EA580C',
		'sanitize_callback' => 'sanitize_hex_color',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'alishop_hero_arrow_hover_bg_color', array(
		'label'    => __( 'Arrow Button Hover Background Color', 'alishop-clone' ),
		'section'  => 'alishop_hero_slider_options',
	) ) );

	// 10. Enable Autoplay
	$wp_customize->add_setting( 'alishop_hero_autoplay_enable', array(
		'default'           => true,
		'sanitize_callback' => 'alishop_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'alishop_hero_autoplay_enable', array(
		'label'    => __( 'Enable Auto-sliding Banner', 'alishop-clone' ),
		'section'  => 'alishop_hero_slider_options',
		'type'     => 'checkbox',
	) );

	// 11. Autoplay Delay Interval (seconds)
	$wp_customize->add_setting( 'alishop_hero_slide_interval', array(
		'default'           => 6,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'alishop_hero_slide_interval', array(
		'label'       => __( 'Auto Slide Display Time (Seconds)', 'alishop-clone' ),
		'description' => __( 'How many seconds each slide stays on screen (e.g. 4, 6, 8)', 'alishop-clone' ),
		'section'     => 'alishop_hero_slider_options',
		'type'        => 'number',
		'input_attrs' => array( 'min' => 1, 'max' => 30, 'step' => 1 ),
	) );

	// 12. Transition Speed (seconds)
	$wp_customize->add_setting( 'alishop_hero_transition_speed', array(
		'default'           => '0.6',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_hero_transition_speed', array(
		'label'       => __( 'Slide Smooth Transition Speed (in seconds, e.g. 0.4, 0.6, 1.0)', 'alishop-clone' ),
		'section'     => 'alishop_hero_slider_options',
		'type'        => 'text',
	) );

	// 13. Dynamic Slide Controls (Up to 10 Slides)
	for ( $i = 1; $i <= 10; $i++ ) {
		// Enable Slide i
		$wp_customize->add_setting( "alishop_hero_slide_{$i}_enable", array(
			'default'           => true,
			'sanitize_callback' => 'alishop_sanitize_checkbox',
		) );
		$wp_customize->add_control( "alishop_hero_slide_{$i}_enable", array(
			'label'    => sprintf( __( 'Enable Banner Slide #%d', 'alishop-clone' ), $i ),
			'section'  => 'alishop_hero_slider_options',
			'type'     => 'checkbox',
		) );

		// Slide i Image
		$wp_customize->add_setting( "alishop_hero_slide_{$i}_img", array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "alishop_hero_slide_{$i}_img", array(
			'label'    => sprintf( __( 'Slide #%d Image', 'alishop-clone' ), $i ),
			'section'  => 'alishop_hero_slider_options',
		) ) );

		// Slide i Title
		$wp_customize->add_setting( "alishop_hero_slide_{$i}_title", array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( "alishop_hero_slide_{$i}_title", array(
			'label'    => sprintf( __( 'Slide #%d Title', 'alishop-clone' ), $i ),
			'section'  => 'alishop_hero_slider_options',
			'type'     => 'text',
		) );

		// Slide i Subtitle
		$wp_customize->add_setting( "alishop_hero_slide_{$i}_subtitle", array(
			'default'           => '',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( "alishop_hero_slide_{$i}_subtitle", array(
			'label'    => sprintf( __( 'Slide #%d Subtitle', 'alishop-clone' ), $i ),
			'section'  => 'alishop_hero_slider_options',
			'type'     => 'text',
		) );

		// Slide i Redirect Link
		$wp_customize->add_setting( "alishop_hero_slide_{$i}_link", array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( "alishop_hero_slide_{$i}_link", array(
			'label'    => sprintf( __( 'Slide #%d Target Link URL', 'alishop-clone' ), $i ),
			'section'  => 'alishop_hero_slider_options',
			'type'     => 'url',
		) );

		// Slide i Button Text
		$wp_customize->add_setting( "alishop_hero_slide_{$i}_btn_text", array(
			'default'           => 'Shop Now →',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( "alishop_hero_slide_{$i}_btn_text", array(
			'label'    => sprintf( __( 'Slide #%d Button Text', 'alishop-clone' ), $i ),
			'section'  => 'alishop_hero_slider_options',
			'type'     => 'text',
		) );
	}

	$wp_customize->add_section( 'alishop_contact_options', array(
		'title'    => __( 'EasyLifeDaily Store Options', 'easylifedaily' ),
		'priority' => 30,
	) );

	// WhatsApp Number
	$wp_customize->add_setting( 'alishop_whatsapp_number', array(
		'default'           => '01617533063',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_whatsapp_number', array(
		'label'    => __( 'WhatsApp Number', 'alishop-clone' ),
		'section'  => 'alishop_contact_options',
		'type'     => 'text',
	) );

	// Messenger Link
	$wp_customize->add_setting( 'alishop_messenger_link', array(
		'default'           => 'https://www.facebook.com/profile.php?id=61570898863722',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'alishop_messenger_link', array(
		'label'    => __( 'Facebook Messenger Link', 'alishop-clone' ),
		'section'  => 'alishop_contact_options',
		'type'     => 'url',
	) );

	// Phone Number
	$wp_customize->add_setting( 'alishop_phone_number', array(
		'default'           => '01617533063',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_phone_number', array(
		'label'    => __( 'Hotline / Call Number', 'alishop-clone' ),
		'section'  => 'alishop_contact_options',
		'type'     => 'text',
	) );

	// Inside Dhaka Delivery Charge
	$wp_customize->add_setting( 'alishop_delivery_inside', array(
		'default'           => '৳ 60 - 70 (1 - 2 Days)',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_delivery_inside', array(
		'label'    => __( 'Inside Dhaka Delivery Charge Text', 'alishop-clone' ),
		'section'  => 'alishop_contact_options',
		'type'     => 'text',
	) );

	// Outside Dhaka Delivery Charge
	$wp_customize->add_setting( 'alishop_delivery_outside', array(
		'default'           => '৳ 120 - 130 (2 - 4 Days)',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_delivery_outside', array(
		'label'    => __( 'Outside Dhaka Delivery Charge Text', 'alishop-clone' ),
		'section'  => 'alishop_contact_options',
		'type'     => 'text',
	) );

	// Top Bar Announcement Text
	$wp_customize->add_setting( 'alishop_top_announcement', array(
		'default'           => '🔥 Welcome to EasyLifeDaily online eCommerce store | Cash on Delivery across Bangladesh!',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_top_announcement', array(
		'label'    => __( 'Top Announcement Bar Text', 'easylifedaily' ),
		'section'  => 'alishop_contact_options',
		'type'     => 'text',
	) );

	// Order Now Button Text (অর্ডার করুন বাটনের লেখা)
	$wp_customize->add_setting( 'alishop_order_now_btn_text', array(
		'default'           => 'অর্ডার করুন',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_order_now_btn_text', array(
		'label'       => __( 'Order Now Button Text (অর্ডার করুন বাটনের লেখা)', 'easylifedaily' ),
		'description' => __( 'প্রোডাক্ট কার্ড এবং প্রোডাক্ট পেজের অর্ডার বাটনের লেখা পরিবর্তন করুন।', 'easylifedaily' ),
		'section'     => 'alishop_contact_options',
		'type'        => 'text',
	) );

	// Homepage Sections Settings Panel
	$wp_customize->add_section( 'alishop_homepage_options', array(
		'title'    => __( 'EasyLifeDaily Homepage Sections', 'easylifedaily' ),
		'priority' => 31,
	) );

	// Best Selling Section Order
	$wp_customize->add_setting( 'alishop_bestselling_order', array(
		'default'           => 1,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'alishop_bestselling_order', array(
		'label'    => __( 'Best Selling Section Display Order', 'alishop-clone' ),
		'section'  => 'alishop_homepage_options',
		'type'     => 'number',
	) );

	// Best Selling Section Title
	$wp_customize->add_setting( 'alishop_bestselling_title', array(
		'default'           => 'Best Selling Products',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_bestselling_title', array(
		'label'    => __( 'Best Selling Section Title', 'alishop-clone' ),
		'section'  => 'alishop_homepage_options',
		'type'     => 'text',
	) );

	// Best Selling Subtitle
	$wp_customize->add_setting( 'alishop_bestselling_subtitle', array(
		'default'           => 'Best selling products of the week',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_bestselling_subtitle', array(
		'label'    => __( 'Best Selling Section Subtitle', 'alishop-clone' ),
		'section'  => 'alishop_homepage_options',
		'type'     => 'text',
	) );

	// Best Selling Category Selector
	$wp_customize->add_setting( 'alishop_bestselling_category', array(
		'default'           => array(),
		'sanitize_callback' => 'alishop_sanitize_multi_select',
	) );
	$wp_customize->add_control( new AliShop_Customize_Category_Checkboxes_Control( $wp_customize, 'alishop_bestselling_category', array(
		'label'       => __( 'Best Selling Product Categories', 'alishop-clone' ),
		'description' => __( 'Select one or multiple categories to display in Best Selling section', 'alishop-clone' ),
		'section'     => 'alishop_homepage_options',
	) ) );

	// Featured Collection Section Order
	$wp_customize->add_setting( 'alishop_featured_order', array(
		'default'           => 2,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'alishop_featured_order', array(
		'label'    => __( 'Featured Collection Display Order', 'alishop-clone' ),
		'section'  => 'alishop_homepage_options',
		'type'     => 'number',
	) );

	// Featured Collection Title
	$wp_customize->add_setting( 'alishop_featured_title', array(
		'default'           => 'Featured Collection',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_featured_title', array(
		'label'    => __( 'Featured Collection Title', 'alishop-clone' ),
		'section'  => 'alishop_homepage_options',
		'type'     => 'text',
	) );

	// Featured Collection Subtitle
	$wp_customize->add_setting( 'alishop_featured_subtitle', array(
		'default'           => 'Featured products of the week',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_featured_subtitle', array(
		'label'    => __( 'Featured Collection Subtitle', 'alishop-clone' ),
		'section'  => 'alishop_homepage_options',
		'type'     => 'text',
	) );

	// Featured Collection Category Selector
	$wp_customize->add_setting( 'alishop_featured_category', array(
		'default'           => array(),
		'sanitize_callback' => 'alishop_sanitize_multi_select',
	) );
	$wp_customize->add_control( new AliShop_Customize_Category_Checkboxes_Control( $wp_customize, 'alishop_featured_category', array(
		'label'       => __( 'Featured Collection Categories', 'alishop-clone' ),
		'description' => __( 'Select one or multiple categories to display in Featured Collection', 'alishop-clone' ),
		'section'     => 'alishop_homepage_options',
	) ) );

	// Custom Category Section 1 Enable Toggle
	$wp_customize->add_setting( 'alishop_custom1_enable', array(
		'default'           => false,
		'sanitize_callback' => 'alishop_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'alishop_custom1_enable', array(
		'label'    => __( 'Enable Extra Custom Category Section 1', 'alishop-clone' ),
		'section'  => 'alishop_homepage_options',
		'type'     => 'checkbox',
	) );

	// Custom Category Section 1 Order
	$wp_customize->add_setting( 'alishop_custom1_order', array(
		'default'           => 3,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'alishop_custom1_order', array(
		'label'    => __( 'Custom Section 1 Display Order', 'alishop-clone' ),
		'section'  => 'alishop_homepage_options',
		'type'     => 'number',
	) );

	// Custom Category Section 1 Title
	$wp_customize->add_setting( 'alishop_custom1_title', array(
		'default'           => 'Special Category Deals',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_custom1_title', array(
		'label'    => __( 'Custom Section 1 Title', 'alishop-clone' ),
		'section'  => 'alishop_homepage_options',
		'type'     => 'text',
	) );

	// Custom Category Section 1 Subtitle
	$wp_customize->add_setting( 'alishop_custom1_subtitle', array(
		'default'           => 'Top selected items for you',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_custom1_subtitle', array(
		'label'    => __( 'Custom Section 1 Subtitle', 'alishop-clone' ),
		'section'  => 'alishop_homepage_options',
		'type'     => 'text',
	) );

	// Custom Category Section 1 Category Selector
	$wp_customize->add_setting( 'alishop_custom1_category', array(
		'default'           => array(),
		'sanitize_callback' => 'alishop_sanitize_multi_select',
	) );
	$wp_customize->add_control( new AliShop_Customize_Category_Checkboxes_Control( $wp_customize, 'alishop_custom1_category', array(
		'label'       => __( 'Custom Section 1 Categories', 'alishop-clone' ),
		'description' => __( 'Select one or multiple categories to display in Custom Section 1', 'alishop-clone' ),
		'section'     => 'alishop_homepage_options',
	) ) );

	// Custom Category Section 2 Enable Toggle
	$wp_customize->add_setting( 'alishop_custom2_enable', array(
		'default'           => false,
		'sanitize_callback' => 'alishop_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'alishop_custom2_enable', array(
		'label'    => __( 'Enable Extra Custom Category Section 2', 'alishop-clone' ),
		'section'  => 'alishop_homepage_options',
		'type'     => 'checkbox',
	) );

	// Custom Category Section 2 Order
	$wp_customize->add_setting( 'alishop_custom2_order', array(
		'default'           => 4,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'alishop_custom2_order', array(
		'label'    => __( 'Custom Section 2 Display Order', 'alishop-clone' ),
		'section'  => 'alishop_homepage_options',
		'type'     => 'number',
	) );

	// Custom Category Section 2 Title
	$wp_customize->add_setting( 'alishop_custom2_title', array(
		'default'           => 'Trending Gadgets & Appliances',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_custom2_title', array(
		'label'    => __( 'Custom Section 2 Title', 'alishop-clone' ),
		'section'  => 'alishop_homepage_options',
		'type'     => 'text',
	) );

	// Custom Category Section 2 Subtitle
	$wp_customize->add_setting( 'alishop_custom2_subtitle', array(
		'default'           => 'Trending products of the month',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_custom2_subtitle', array(
		'label'    => __( 'Custom Section 2 Subtitle', 'alishop-clone' ),
		'section'  => 'alishop_homepage_options',
		'type'     => 'text',
	) );

	// Custom Category Section 2 Category Selector
	$wp_customize->add_setting( 'alishop_custom2_category', array(
		'default'           => array(),
		'sanitize_callback' => 'alishop_sanitize_multi_select',
	) );
	$wp_customize->add_control( new AliShop_Customize_Category_Checkboxes_Control( $wp_customize, 'alishop_custom2_category', array(
		'label'       => __( 'Custom Section 2 Categories', 'alishop-clone' ),
		'description' => __( 'Select one or multiple categories to display in Custom Section 2', 'alishop-clone' ),
		'section'     => 'alishop_homepage_options',
	) ) );

	// All Products Section Order
	$wp_customize->add_setting( 'alishop_allproducts_order', array(
		'default'           => 5,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'alishop_allproducts_order', array(
		'label'    => __( 'All Products Display Order', 'alishop-clone' ),
		'section'  => 'alishop_homepage_options',
		'type'     => 'number',
	) );

	// All Products Section Title
	$wp_customize->add_setting( 'alishop_allproducts_title', array(
		'default'           => 'All Products',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_allproducts_title', array(
		'label'    => __( 'All Products Section Title', 'alishop-clone' ),
		'section'  => 'alishop_homepage_options',
		'type'     => 'text',
	) );

	// All Products Subtitle
	$wp_customize->add_setting( 'alishop_allproducts_subtitle', array(
		'default'           => 'All products of the week',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_allproducts_subtitle', array(
		'label'    => __( 'All Products Section Subtitle', 'alishop-clone' ),
		'section'  => 'alishop_homepage_options',
		'type'     => 'text',
	) );

	// All Products Section Enable Toggle
	$wp_customize->add_setting( 'alishop_allproducts_enable', array(
		'default'           => true,
		'sanitize_callback' => 'alishop_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'alishop_allproducts_enable', array(
		'label'    => __( 'Enable All Products Section (সেকশন চালু রাখুন)', 'alishop-clone' ),
		'section'  => 'alishop_homepage_options',
		'type'     => 'checkbox',
	) );

	// All Products - Show All Products Toggle
	$wp_customize->add_setting( 'alishop_allproducts_show_all', array(
		'default'           => false,
		'sanitize_callback' => 'alishop_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'alishop_allproducts_show_all', array(
		'label'       => __( 'Show All Products (সকল প্রোডাক্ট একসাথে দেখান)', 'alishop-clone' ),
		'description' => __( 'টিক দিলে পেজিনেশন বা লিমিট ছাড়াই স্টোরের সকল প্রোডাক্ট একসাথে এই সেকশনে দেখাবে।', 'alishop-clone' ),
		'section'     => 'alishop_homepage_options',
		'type'        => 'checkbox',
	) );

	// All Products - Enable Pagination
	$wp_customize->add_setting( 'alishop_allproducts_pagination', array(
		'default'           => true,
		'sanitize_callback' => 'alishop_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'alishop_allproducts_pagination', array(
		'label'       => __( 'Enable Pagination (পেজিনেশন চালু রাখুন)', 'alishop-clone' ),
		'description' => __( 'পরবর্তী পেজে যাওয়ার জন্য পেজিনেশন বাটন (১, ২, Next, Prev) দেখাবে। ("Show All Products" বন্ধ থাকলে এটি কাজ করবে)', 'alishop-clone' ),
		'section'     => 'alishop_homepage_options',
		'type'        => 'checkbox',
	) );

	// All Products - Products Per Page (for Pagination)
	$wp_customize->add_setting( 'alishop_allproducts_per_page', array(
		'default'           => 12,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'alishop_allproducts_per_page', array(
		'label'       => __( 'Products Per Page (প্রতি পেজে পণ্যের সংখ্যা)', 'alishop-clone' ),
		'description' => __( 'পেজিনেশন চালু থাকলে প্রতি পেজে কতটি প্রোডাক্ট শো করবে (ডিফল্ট: ১২)', 'alishop-clone' ),
		'section'     => 'alishop_homepage_options',
		'type'        => 'number',
	) );

	// All Products - Total Limit (When pagination is disabled)
	$wp_customize->add_setting( 'alishop_allproducts_limit', array(
		'default'           => 12,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'alishop_allproducts_limit', array(
		'label'       => __( 'Total Product Limit (মোট পণ্যের সংখ্যা - পেজিনেশন বন্ধ থাকলে)', 'alishop-clone' ),
		'description' => __( 'পেজিনেশন বন্ধ রাখলে সর্বোচ্চ কতটি পণ্য এই সেকশনে দেখাবে (ডিফল্ট: ১২)', 'alishop-clone' ),
		'section'     => 'alishop_homepage_options',
		'type'        => 'number',
	) );

	// All Products - Order By Setting
	$wp_customize->add_setting( 'alishop_allproducts_orderby', array(
		'default'           => 'date',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alishop_allproducts_orderby', array(
		'label'       => __( 'Product Order By (পণ্য সাজানোর ক্রম)', 'alishop-clone' ),
		'description' => __( 'All Products সেকশনে প্রোডাক্টগুলো কোন ক্রমানুসারে সাজানো হবে সিলেক্ট করুন।', 'alishop-clone' ),
		'section'     => 'alishop_homepage_options',
		'type'        => 'select',
		'choices'     => array(
			'date'        => __( 'Newest First (নতুন প্রোডাক্ট আগে)', 'alishop-clone' ),
			'date_asc'    => __( 'Oldest First (পুরাতন প্রোডাক্ট আগে)', 'alishop-clone' ),
			'price'       => __( 'Price: Low to High (কম দাম থেকে বেশি)', 'alishop-clone' ),
			'price_desc'  => __( 'Price: High to Low (বেশি দাম থেকে কম)', 'alishop-clone' ),
			'popularity'  => __( 'Best Selling / Popularity (সর্বাধিক বিক্রিত)', 'alishop-clone' ),
			'title'       => __( 'Product Name: A to Z (নাম অনুযায়ী)', 'alishop-clone' ),
			'title_desc'  => __( 'Product Name: Z to A (নাম অনুযায়ী উল্টো)', 'alishop-clone' ),
			'rand'        => __( 'Random (এলোমেলো)', 'alishop-clone' ),
			'menu_order'  => __( 'Menu / Custom Order (কাস্টম অর্ডার)', 'alishop-clone' ),
		),
	) );

	// ----------------------------------------------------
	// AliShop Product FAQs Section in Customizer
	// ----------------------------------------------------
	$wp_customize->add_section( 'alishop_faq_options', array(
		'title'    => __( 'EasyLifeDaily Product FAQs (পণ্য এফএকিউ)', 'easylifedaily' ),
		'priority' => 35,
	) );

	$wp_customize->add_setting( 'alishop_product_faqs_json', array(
		'default'           => '[]',
		'type'              => 'option',
		'sanitize_callback' => 'alishop_sanitize_faq_json',
	) );

	if ( class_exists( 'AliShop_FAQ_Manager_Control' ) ) {
		$wp_customize->add_control( new AliShop_FAQ_Manager_Control( $wp_customize, 'alishop_product_faqs_json', array(
			'label'       => __( 'Product FAQs Manager', 'alishop-clone' ),
			'description' => __( 'প্রোডাক্ট সিলেক্ট করে FAQ যোগ, এডিট ও আপডেট করুন।', 'alishop-clone' ),
			'section'     => 'alishop_faq_options',
		) ) );
	}
}
add_action( 'customize_register', 'alishop_clone_customize_register' );

/**
 * Get Dynamic "Order Now" Button Text from Customizer
 */
function alishop_get_order_button_text() {
	$btn_text = get_theme_mod( 'alishop_order_now_btn_text', 'অর্ডার করুন' );
	return ! empty( $btn_text ) ? $btn_text : 'অর্ডার করুন';
}

/**
 * Get Dynamic Hero Banner Slides Array with Fallbacks
 */
function alishop_get_hero_slides() {
	$slides = array();
	$default_shop_url = class_exists( 'WooCommerce' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : '#';

	$defaults = array(
		1 => array(
			'img'      => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=1400&auto=format&fit=crop',
			'title'    => 'প্রতিটি কোণায় বিলাসিতার ছোঁয়া',
			'subtitle' => '',
			'link'     => $default_shop_url,
			'btn_text' => 'Shop Now →',
			'enable'   => true,
		),
		2 => array(
			'img'      => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=1400&auto=format&fit=crop',
			'title'    => 'দেশজুড়ে ক্যাশ অন ডেলিভারি',
			'subtitle' => '১০০% প্রামাণিক গ্যাজেট ও হোম অ্যাপ্লায়েন্স হোম ডেলিভারিতে পান!',
			'link'     => $default_shop_url,
			'btn_text' => 'Explore Collection →',
			'enable'   => true,
		),
	);

	for ( $i = 1; $i <= 10; $i++ ) {
		$img      = get_theme_mod( "alishop_hero_slide_{$i}_img", isset( $defaults[ $i ] ) ? $defaults[ $i ]['img'] : '' );
		$title    = get_theme_mod( "alishop_hero_slide_{$i}_title", isset( $defaults[ $i ] ) ? $defaults[ $i ]['title'] : '' );
		$subtitle = get_theme_mod( "alishop_hero_slide_{$i}_subtitle", isset( $defaults[ $i ] ) ? $defaults[ $i ]['subtitle'] : '' );
		$link     = get_theme_mod( "alishop_hero_slide_{$i}_link", isset( $defaults[ $i ] ) ? $defaults[ $i ]['link'] : '' );
		$btn_text = get_theme_mod( "alishop_hero_slide_{$i}_btn_text", isset( $defaults[ $i ] ) ? $defaults[ $i ]['btn_text'] : 'Shop Now →' );
		
		$enabled_mod = get_theme_mod( "alishop_hero_slide_{$i}_enable", null );

		// Auto-enable if user uploaded a custom image or title for this slide
		if ( ! empty( $img ) && ( ! isset( $defaults[ $i ] ) || $img !== $defaults[ $i ]['img'] ) ) {
			$enabled = true;
		} elseif ( ! empty( $title ) && ( ! isset( $defaults[ $i ] ) || $title !== $defaults[ $i ]['title'] ) ) {
			$enabled = true;
		} elseif ( null !== $enabled_mod ) {
			$enabled = (bool) $enabled_mod;
		} else {
			$enabled = isset( $defaults[ $i ] ) ? $defaults[ $i ]['enable'] : false;
		}

		if ( $enabled ) {
			$final_img   = ! empty( $img ) ? $img : ( isset( $defaults[ $i ] ) ? $defaults[ $i ]['img'] : '' );
			$final_title = ! empty( $title ) ? $title : ( isset( $defaults[ $i ] ) ? $defaults[ $i ]['title'] : '' );
			
			if ( ! empty( $final_img ) || ! empty( $final_title ) ) {
				$slides[] = array(
					'img'      => $final_img,
					'title'    => $final_title,
					'subtitle' => $subtitle,
					'link'     => ! empty( $link ) ? $link : $default_shop_url,
					'btn_text' => ! empty( $btn_text ) ? $btn_text : 'Shop Now →',
				);
			}
		}
	}

	// Ultimate fallback if no slides enabled or valid
	if ( empty( $slides ) ) {
		foreach ( $defaults as $def ) {
			$slides[] = $def;
		}
	}

	return $slides;
}

/**
 * Get Dynamic Hero Arrow Icons based on Admin Customizer selection
 */
function alishop_get_hero_arrow_icons() {
	$style       = get_theme_mod( 'alishop_hero_arrow_style', 'standard' );
	$custom_prev = get_theme_mod( 'alishop_hero_arrow_prev_custom', '‹' );
	$custom_next = get_theme_mod( 'alishop_hero_arrow_next_custom', '›' );

	switch ( $style ) {
		case 'heavy':
			return array( 'prev' => '❮', 'next' => '❯' );
		case 'arrow':
			return array( 'prev' => '←', 'next' => '→' );
		case 'triangle':
			return array( 'prev' => '◀', 'next' => '▶' );
		case 'custom':
			return array(
				'prev' => ! empty( $custom_prev ) ? $custom_prev : '‹',
				'next' => ! empty( $custom_next ) ? $custom_next : '›',
			);
		case 'standard':
		default:
			return array( 'prev' => '‹', 'next' => '›' );
	}
}

/**
 * Custom Category Checkboxes Control for WordPress Customizer
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	class AliShop_Customize_Category_Checkboxes_Control extends WP_Customize_Control {
		public $type = 'category-checkboxes';

		public function render_content() {
			$categories = get_terms( array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
			) );

			if ( empty( $categories ) || is_wp_error( $categories ) ) {
				echo '<p>' . esc_html__( 'No categories found.', 'alishop-clone' ) . '</p>';
				return;
			}

			$raw_value = $this->value();
			$values    = array();
			if ( is_array( $raw_value ) ) {
				$values = $raw_value;
			} elseif ( is_string( $raw_value ) && ! empty( $raw_value ) ) {
				$values = array_filter( array_map( 'trim', explode( ',', $raw_value ) ) );
			}

			if ( ! empty( $this->label ) ) {
				echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
			}
			if ( ! empty( $this->description ) ) {
				echo '<span class="description customize-control-description">' . esc_html( $this->description ) . '</span>';
			}
			?>
			<div class="alishop-cat-checkbox-group" style="max-height: 160px; overflow-y: auto; background: #ffffff; border: 1px solid #dcdcde; border-radius: 6px; padding: 8px 10px; margin-top: 6px; box-shadow: inset 0 1px 2px rgba(0,0,0,0.07);">
				<?php foreach ( $categories as $cat ) : 
					$checked = in_array( $cat->slug, $values, true );
				?>
					<label style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; font-size: 13px; cursor: pointer; color: #1d2327; font-weight: 500;">
						<input type="checkbox" class="alishop-cat-cb-item" value="<?php echo esc_attr( $cat->slug ); ?>" <?php checked( $checked ); ?> style="accent-color: #F97316; width: 16px; height: 16px; cursor: pointer; margin: 0;" />
						<span><?php echo esc_html( $cat->name ); ?> <?php echo $checked ? '<strong style="color:#16A34A; margin-left:2px;">✓</strong>' : ''; ?></span>
					</label>
				<?php endforeach; ?>
			</div>
			<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( is_array( $this->value() ) ? implode( ',', $this->value() ) : $this->value() ); ?>" class="alishop-cat-cb-hidden" />
			
			<script>
			if ( typeof jQuery !== 'undefined' ) {
				jQuery(document).ready(function($){
					$('.alishop-cat-checkbox-group').on('change', '.alishop-cat-cb-item', function(){
						var $control = $(this).closest('.customize-control');
						var selected = [];
						$control.find('.alishop-cat-cb-item:checked').each(function(){
							selected.push($(this).val());
						});
						var $hidden = $control.find('.alishop-cat-cb-hidden');
						$hidden.val(selected.join(',')).trigger('change');
					});
				});
			}
			</script>
			<?php
		}
	}
}

/**
 * Checkbox sanitization helper
 */
function alishop_sanitize_checkbox( $checked ) {
	return ( isset( $checked ) && true === (bool) $checked ) ? true : false;
}

/**
 * Multi-select sanitization helper
 */
function alishop_sanitize_multi_select( $input ) {
	if ( is_array( $input ) ) {
		return array_map( 'sanitize_text_field', $input );
	}
	return sanitize_text_field( $input );
}

/**
 * Helper to get WooCommerce product categories for Customizer select control
 */
function alishop_get_product_categories_options() {
	$options = array( '' => __( 'All Categories / Default', 'alishop-clone' ) );
	if ( taxonomy_exists( 'product_cat' ) ) {
		$categories = get_terms( array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
		) );
		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			foreach ( $categories as $cat ) {
				$options[ $cat->slug ] = $cat->name;
			}
		}
	}
	return $options;
}

/**
 * Render Product Card Items for Homepage Sections (Dynamic WooCommerce Query with Fallback)
 */
function alishop_render_homepage_products( $cat_slug = '', $limit = 10, $demo_fallback = array() ) {
	if ( class_exists( 'WooCommerce' ) ) {
		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => $limit,
			'post_status'    => 'publish',
		);
		if ( ! empty( $cat_slug ) ) {
			if ( is_array( $cat_slug ) ) {
				$slugs = array_filter( array_map( 'sanitize_text_field', $cat_slug ) );
			} else {
				$slugs = array_filter( array_map( 'trim', explode( ',', (string) $cat_slug ) ) );
			}
			if ( ! empty( $slugs ) ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'slug',
						'terms'    => $slugs,
						'operator' => 'IN',
					),
				);
			}
		}
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
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
			wp_reset_postdata();
			return;
		}
	}

	// Fallback Demo Products if WooCommerce is inactive or no products found in category
	if ( ! empty( $demo_fallback ) ) {
		foreach ( $demo_fallback as $item ) {
			?>
			<div class="alishop-product-card-item">
				<div class="card-thumb-box">
					<span class="card-discount-tag"><?php echo esc_html( $item['off'] ); ?>% OFF</span>
					<a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url('/product-details/') ); ?>">
						<img src="<?php echo esc_url( $item['img'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>">
					</a>
				</div>
				<div class="card-body-box">
					<h3 class="card-product-title">
						<a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url('/product-details/') ); ?>"><?php echo esc_html( $item['title'] ); ?></a>
					</h3>
					<div class="card-price-box">
						<del>৳<?php echo esc_html( $item['reg'] ); ?></del>
						<span class="card-current-price">৳<?php echo esc_html( $item['sale'] ); ?></span>
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
}

/**
 * Render All Products Grid Section on Homepage with Customizer controls:
 * Show All option, per page control, total limit, orderby, and numbered pagination.
 */
function alishop_render_homepage_all_products( $demo_fallback = array() ) {
	$show_all          = (bool) get_theme_mod( 'alishop_allproducts_show_all', false );
	$enable_pagination = (bool) get_theme_mod( 'alishop_allproducts_pagination', true );
	$per_page          = (int) get_theme_mod( 'alishop_allproducts_per_page', 12 );
	$total_limit       = (int) get_theme_mod( 'alishop_allproducts_limit', 12 );
	$orderby_setting   = get_theme_mod( 'alishop_allproducts_orderby', 'date' );

	if ( $per_page <= 0 ) {
		$per_page = 12;
	}
	if ( $total_limit <= 0 ) {
		$total_limit = 12;
	}

	// Determine current page for pagination (supports ?product_page=X, ?paged=X, and pretty permalinks)
	$current_page = 1;
	if ( isset( $_GET['product_page'] ) ) {
		$current_page = max( 1, absint( $_GET['product_page'] ) );
	} elseif ( get_query_var( 'paged' ) ) {
		$current_page = max( 1, absint( get_query_var( 'paged' ) ) );
	} elseif ( get_query_var( 'page' ) ) {
		$current_page = max( 1, absint( get_query_var( 'page' ) ) );
	}

	$has_products = false;

	if ( class_exists( 'WooCommerce' ) ) {
		$args = array(
			'post_type'   => 'product',
			'post_status' => 'publish',
		);

		// Number of products to query
		if ( $show_all ) {
			$args['posts_per_page'] = -1;
		} elseif ( $enable_pagination ) {
			$args['posts_per_page'] = $per_page;
			$args['paged']          = $current_page;
		} else {
			$args['posts_per_page'] = $total_limit;
		}

		// Order by handling
		switch ( $orderby_setting ) {
			case 'date_asc':
				$args['orderby'] = 'date';
				$args['order']   = 'ASC';
				break;
			case 'price':
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = '_price';
				$args['order']    = 'ASC';
				break;
			case 'price_desc':
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = '_price';
				$args['order']    = 'DESC';
				break;
			case 'popularity':
				$args['orderby']  = 'meta_value_num';
				$args['meta_key'] = 'total_sales';
				$args['order']    = 'DESC';
				break;
			case 'title':
				$args['orderby'] = 'title';
				$args['order']   = 'ASC';
				break;
			case 'title_desc':
				$args['orderby'] = 'title';
				$args['order']   = 'DESC';
				break;
			case 'rand':
				$args['orderby'] = 'rand';
				break;
			case 'menu_order':
				$args['orderby'] = 'menu_order title';
				$args['order']   = 'ASC';
				break;
			case 'date':
			default:
				$args['orderby'] = 'date';
				$args['order']   = 'DESC';
				break;
		}

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			$has_products = true;
			?>
			<div class="alishop-all-products-grid">
				<?php
				while ( $query->have_posts() ) {
					$query->the_post();
					global $product;
					if ( ! $product ) continue;

					$product_id       = $product->get_id();
					$title            = get_the_title();
					$link             = get_permalink();
					$reg_price        = $product->get_regular_price();
					$sale_price       = $product->get_price();
					$discount         = 0;
					if ( $reg_price && $sale_price && $reg_price > $sale_price ) {
						$discount = round( ( ( $reg_price - $sale_price ) / $reg_price ) * 100 );
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
				?>
			</div>
			<?php
			// Render Pagination if enabled and there are multiple pages (and not showing all)
			if ( ! $show_all && $enable_pagination && $query->max_num_pages > 1 ) {
				$pagination_html = paginate_links( array(
					'base'         => add_query_arg( 'product_page', '%#%' ),
					'format'       => '',
					'current'      => $current_page,
					'total'        => $query->max_num_pages,
					'prev_text'    => '&larr; পূর্ববর্তী',
					'next_text'    => 'পরবর্তী &rarr;',
					'type'         => 'plain',
					'add_fragment' => '#all-products',
				) );

				if ( $pagination_html ) {
					echo '<div class="alishop-pagination-wrapper">' . $pagination_html . '</div>';
				}
			}

			wp_reset_postdata();
		}
	}

	// Demo fallback if WooCommerce inactive or no products exist
	if ( ! $has_products && ! empty( $demo_fallback ) ) {
		?>
		<div class="alishop-all-products-grid">
			<?php
			foreach ( $demo_fallback as $item ) {
				?>
				<div class="alishop-product-card-item">
					<div class="card-thumb-box">
						<span class="card-discount-tag"><?php echo esc_html( $item['off'] ); ?>% OFF</span>
						<a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url('/product-details/') ); ?>">
							<img src="<?php echo esc_url( $item['img'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>">
						</a>
					</div>
					<div class="card-body-box">
						<h3 class="card-product-title">
							<a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url('/product-details/') ); ?>"><?php echo esc_html( $item['title'] ); ?></a>
						</h3>
						<div class="card-price-box">
							<del>৳<?php echo esc_html( $item['reg'] ); ?></del>
							<span class="card-current-price">৳<?php echo esc_html( $item['sale'] ); ?></span>
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
			?>
		</div>
		<?php
	}
}

/**
 * Render Header Cart Component
 */
function alishop_render_header_cart() {
	if ( ! class_exists( 'WooCommerce' ) ) return;
	$cart_count = ( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
	$cart_total = ( WC()->cart ) ? WC()->cart->get_cart_subtotal() : '৳ 0';
	?>
	<div class="alishop-header-cart-wrapper">
		<a href="javascript:void(0)" onclick="alishopOpenCartDrawer()" class="header-action-item">
			<div class="icon-wrapper">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
				<span class="badge"><?php echo esc_html( $cart_count ); ?></span>
			</div>
			<div class="header-action-text">
				<span class="label">My Cart</span>
				<span class="val"><?php echo $cart_total; ?></span>
			</div>
		</a>
	</div>
	<?php
}

/**
 * Render Floating Cart Widget Component
 */
function alishop_render_floating_cart() {
	if ( ! class_exists( 'WooCommerce' ) ) return;
	$cart_count = ( WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
	$cart_subtotal = ( WC()->cart ) ? WC()->cart->get_cart_subtotal() : '৳ 0';
	?>
	<div class="alishop-floating-cart-wrapper">
		<a href="javascript:void(0)" onclick="alishopOpenCartDrawer()" class="alishop-floating-cart-widget">
			<div class="cart-widget-icon">
				🛒
				<span class="widget-count-badge"><?php echo esc_html( $cart_count ); ?></span>
			</div>
			<span class="widget-price-text"><?php echo $cart_subtotal; ?></span>
		</a>
	</div>
	<?php
}

/**
 * Render Side Cart Popup Drawer Component (Matching Screenshot Specifications)
 */
function alishop_render_side_cart_drawer() {
	if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) return;

	$cart_items = WC()->cart->get_cart();
	$cart_count = WC()->cart->get_cart_contents_count();
	$subtotal   = WC()->cart->get_cart_subtotal();
	?>
	<div class="alishop-mini-cart-drawer-wrapper">
		<div class="cart-drawer-header">
			<h3>Shopping Cart (<?php echo esc_html( $cart_count ); ?>)</h3>
			<button type="button" class="close-drawer-btn" onclick="alishopCloseCartDrawer()" aria-label="Close Cart">✕</button>
		</div>

		<div class="cart-drawer-body">
			<?php if ( ! empty( $cart_items ) ) : ?>
				<div class="cart-drawer-items-list">
					<?php
					foreach ( $cart_items as $cart_item_key => $cart_item ) {
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
							$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'thumbnail' ), $cart_item, $cart_item_key );
							$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
							$quantity          = $cart_item['quantity'];
							?>
							<div class="drawer-cart-item" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">
								<div class="item-thumb">
									<?php if ( $product_permalink ) : ?>
										<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo $thumbnail; ?></a>
									<?php else : ?>
										<?php echo $thumbnail; ?>
									<?php endif; ?>
								</div>
								<div class="item-details">
									<div class="item-title-row">
										<h4 class="item-name">
											<?php if ( $product_permalink ) : ?>
												<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo esc_html( $product_name ); ?></a>
											<?php else : ?>
												<?php echo esc_html( $product_name ); ?>
											<?php endif; ?>
										</h4>
										<button type="button" class="remove-item-btn" onclick="alishopRemoveCartItem('<?php echo esc_js( $cart_item_key ); ?>')" title="Remove item">🗑</button>
									</div>
									<?php
									// Variation meta data formatting
									echo wc_get_formatted_cart_item_data( $cart_item );
									?>
									<div class="item-price-qty-row">
										<div class="drawer-qty-stepper">
											<button type="button" class="d-qty-btn minus" onclick="alishopUpdateCartItemQty('<?php echo esc_js( $cart_item_key ); ?>', <?php echo (int) $quantity - 1; ?>)">-</button>
											<span class="d-qty-num"><?php echo esc_html( $quantity ); ?></span>
											<button type="button" class="d-qty-btn plus" onclick="alishopUpdateCartItemQty('<?php echo esc_js( $cart_item_key ); ?>', <?php echo (int) $quantity + 1; ?>)">+</button>
										</div>
										<div class="item-total-price">
											<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
										</div>
									</div>
								</div>
							</div>
							<?php
						}
					}
					?>
				</div>
			<?php else : ?>
				<div class="cart-drawer-empty">
					<div class="empty-icon">🛒</div>
					<p>Your shopping cart is empty!</p>
					<a href="<?php echo esc_url( class_exists('WooCommerce') ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url('/') ); ?>" class="start-shopping-btn" onclick="alishopCloseCartDrawer()">Start Shopping Now</a>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $cart_items ) ) : ?>
			<div class="cart-drawer-footer">
				<div class="drawer-subtotal-row">
					<span class="label">Subtotal:</span>
					<span class="amount"><?php echo $subtotal; ?></span>
				</div>
				<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="drawer-checkout-btn">
					Proceed to Checkout
				</a>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * WooCommerce AJAX Cart Fragments Filter
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'alishop_add_to_cart_fragments' );
function alishop_add_to_cart_fragments( $fragments ) {
	if ( class_exists( 'WooCommerce' ) ) {
		ob_start();
		alishop_render_header_cart();
		$fragments['.alishop-header-cart-wrapper'] = ob_get_clean();

		ob_start();
		alishop_render_floating_cart();
		$fragments['.alishop-floating-cart-wrapper'] = ob_get_clean();

		ob_start();
		alishop_render_side_cart_drawer();
		$fragments['.alishop-mini-cart-drawer-wrapper'] = ob_get_clean();
	}
	return $fragments;
}

/**
 * AJAX Handler to Update Cart Item Quantity from Side Cart Drawer
 */
add_action( 'wp_ajax_alishop_update_cart_qty', 'alishop_update_cart_qty_handler' );
add_action( 'wp_ajax_nopriv_alishop_update_cart_qty', 'alishop_update_cart_qty_handler' );
function alishop_update_cart_qty_handler() {
	if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
		wp_send_json_error();
	}

	$cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( $_POST['cart_item_key'] ) : '';
	$new_qty       = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 0;

	if ( ! empty( $cart_item_key ) ) {
		if ( $new_qty > 0 ) {
			WC()->cart->set_quantity( $cart_item_key, $new_qty, true );
		} else {
			WC()->cart->remove_cart_item( $cart_item_key );
		}
		WC()->cart->calculate_totals();

		ob_start();
		alishop_render_header_cart();
		$fragments['.alishop-header-cart-wrapper'] = ob_get_clean();

		ob_start();
		alishop_render_floating_cart();
		$fragments['.alishop-floating-cart-wrapper'] = ob_get_clean();

		ob_start();
		alishop_render_side_cart_drawer();
		$fragments['.alishop-mini-cart-drawer-wrapper'] = ob_get_clean();

		wp_send_json_success( array( 'fragments' => $fragments ) );
	}

	wp_send_json_error();
}

/**
 * AJAX Handler to Remove Cart Item from Side Cart Drawer
 */
add_action( 'wp_ajax_alishop_remove_cart_item', 'alishop_remove_cart_item_handler' );
add_action( 'wp_ajax_nopriv_alishop_remove_cart_item', 'alishop_remove_cart_item_handler' );
function alishop_remove_cart_item_handler() {
	if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) {
		wp_send_json_error();
	}

	$cart_item_key = isset( $_POST['cart_item_key'] ) ? sanitize_text_field( $_POST['cart_item_key'] ) : '';

	if ( ! empty( $cart_item_key ) ) {
		WC()->cart->remove_cart_item( $cart_item_key );
		WC()->cart->calculate_totals();

		ob_start();
		alishop_render_header_cart();
		$fragments['.alishop-header-cart-wrapper'] = ob_get_clean();

		ob_start();
		alishop_render_floating_cart();
		$fragments['.alishop-floating-cart-wrapper'] = ob_get_clean();

		ob_start();
		alishop_render_side_cart_drawer();
		$fragments['.alishop-mini-cart-drawer-wrapper'] = ob_get_clean();

		wp_send_json_success( array( 'fragments' => $fragments ) );
	}

	wp_send_json_error();
}

/**
 * WooCommerce Enhancements (Alishop BD Specifications)
 */
if ( class_exists( 'WooCommerce' ) ) {
    
    // Change Currency Symbol to BDT symbol ৳
    add_filter('woocommerce_currency_symbol', 'alishop_clone_bdt_currency_symbol', 10, 2);
    function alishop_clone_bdt_currency_symbol( $currency_symbol, $currency ) {
        if ( $currency === 'BDT' ) {
            return '৳ ';
        }
        return $currency_symbol;
    }

    // Add Direct Order Now / Buy Now & Dynamic Contact Buttons on Single Product Page
    function alishop_clone_custom_product_buttons() {
        global $product;
        if ( ! $product ) return;
        
        $checkout_url = wc_get_checkout_url();
        $product_id   = $product->get_id();
        $buy_now_url  = add_query_arg( 'add-to-cart', $product_id, $checkout_url );
        $product_title= get_the_title( $product_id );
        $product_price= $product->get_price();
        $product_link = get_permalink( $product_id );
        
        // Direct Order Now Button
        echo '<a href="' . esc_url( $buy_now_url ) . '" class="button alt buy-now-btn-single">⚡ Order Now (Cash on Delivery)</a>';
        
        // Theme mod contact options with defaults
        $whatsapp_num = get_theme_mod( 'alishop_whatsapp_number', '01617533063' );
        $messenger_url= get_theme_mod( 'alishop_messenger_link', 'https://www.facebook.com/profile.php?id=61570898863722' );
        $phone_num    = get_theme_mod( 'alishop_phone_number', '01617533063' );
        $delivery_in  = get_theme_mod( 'alishop_delivery_inside', '৳ 60 - 70 (1 - 2 Days)' );
        $delivery_out = get_theme_mod( 'alishop_delivery_outside', '৳ 120 - 130 (2 - 4 Days)' );
        
        // Clean phone number for wa.me / tel
        $clean_wa_num = preg_replace( '/[^0-9]/', '', $whatsapp_num );
        if ( substr( $clean_wa_num, 0, 2 ) !== '88' && strlen( $clean_wa_num ) === 11 ) {
            $clean_wa_num = '88' . $clean_wa_num;
        }

        // Dynamic WhatsApp Order Message
        $wa_msg = sprintf(
            "Hello EasyLifeDaily, I want to order this product:\n📌 Product: %s\n💰 Price: ৳%s\n🔗 Link: %s",
            $product_title,
            $product_price,
            $product_link
        );
        $dynamic_wa_link = 'https://wa.me/' . $clean_wa_num . '?text=' . rawurlencode( $wa_msg );

        echo '<div class="product-contact-actions">';
        
        // Dynamic WhatsApp Button
        if ( ! empty( $whatsapp_num ) ) {
            echo '<a href="' . esc_url( $dynamic_wa_link ) . '" target="_blank" rel="noopener" class="contact-btn-lg whatsapp-bg">';
            echo '💬 Order on WhatsApp';
            echo '</a>';
        }
        
        // Dynamic Messenger Button
        if ( ! empty( $messenger_url ) ) {
            echo '<a href="' . esc_url( $messenger_url ) . '" target="_blank" rel="noopener" class="contact-btn-lg messenger-bg">';
            echo '⚡ Order on Messenger';
            echo '</a>';
        }

        // Dynamic Live Call Button
        if ( ! empty( $phone_num ) ) {
            echo '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone_num ) ) . '" class="contact-btn-lg phone-bg">';
            echo '📞 Call to Order (' . esc_html( $phone_num ) . ')';
            echo '</a>';
        }
        
        echo '</div>';

        // Delivery Charge Banner Info
        echo '<div class="delivery-info-box">';
        echo '<h4>🚚 Home Delivery & Shipping Info:</h4>';
        echo '<ul>';
        echo '<li>Inside Dhaka City Delivery Charge: <strong>' . esc_html( $delivery_in ) . '</strong></li>';
        echo '<li>Outside Dhaka Delivery Charge: <strong>' . esc_html( $delivery_out ) . '</strong></li>';
        echo '<li>100% Cash on Delivery available nationwide!</li>';
        echo '</ul>';
        echo '</div>';
    }
    add_action( 'woocommerce_after_add_to_cart_button', 'alishop_clone_custom_product_buttons' );

    // Ensure Custom Templates are used
    add_filter( 'template_include', 'alishop_clone_template_include', 99 );
    function alishop_clone_template_include( $template ) {
        if ( is_singular( 'product' ) ) {
            $custom_single = get_stylesheet_directory() . '/single-product.php';
            if ( file_exists( $custom_single ) ) {
                return $custom_single;
            }
        }
        if ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) {
            $custom_archive = get_stylesheet_directory() . '/archive-product.php';
            if ( file_exists( $custom_archive ) ) {
                return $custom_archive;
            }
        }
        if ( is_checkout() ) {
            $custom_checkout = get_stylesheet_directory() . '/page-checkout.php';
            if ( file_exists( $custom_checkout ) ) {
                return $custom_checkout;
            }
        }
        return $template;
    }

    // Custom WooCommerce Product Search Form
    add_filter( 'get_product_search_form', 'alishop_clone_product_search_form' );
    function alishop_clone_product_search_form( $form ) {
        $form = '<form role="search" method="get" class="search-form woocommerce-product-search" action="' . esc_url( home_url( '/' ) ) . '">
            <input type="search" class="search-field" placeholder="' . esc_attr__( 'Search products...', 'alishop-clone' ) . '" value="' . get_search_query() . '" name="s" />
            <button type="submit" class="search-submit">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </button>
            <input type="hidden" name="post_type" value="product" />
        </form>';
        return $form;
    }
}

/**
 * AJAX Handler for Direct Alishop Custom Checkout Order Creation
 */
add_action( 'wp_ajax_alishop_process_custom_checkout', 'alishop_process_custom_checkout_handler' );
add_action( 'wp_ajax_nopriv_alishop_process_custom_checkout', 'alishop_process_custom_checkout_handler' );
function alishop_process_custom_checkout_handler() {
	if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart || WC()->cart->is_empty() ) {
		wp_send_json_error( array( 'message' => 'আপনার কার্ট খালি। অনুগ্রহ করে পণ্য যুক্ত করুন।' ) );
	}

	$name     = isset( $_POST['cust_name'] ) ? sanitize_text_field( $_POST['cust_name'] ) : '';
	$phone    = isset( $_POST['cust_phone'] ) ? sanitize_text_field( $_POST['cust_phone'] ) : '';
	$address  = isset( $_POST['cust_address'] ) ? sanitize_textarea_field( $_POST['cust_address'] ) : '';
	$notes    = isset( $_POST['cust_notes'] ) ? sanitize_textarea_field( $_POST['cust_notes'] ) : '';
	$shipping = isset( $_POST['delivery_zone'] ) && 'inside' === $_POST['delivery_zone'] ? 60 : 100;

	if ( empty( $name ) || empty( $phone ) || empty( $address ) ) {
		wp_send_json_error( array( 'message' => 'অনুগ্রহ করে নাম, মোবাইল নম্বর এবং সম্পূর্ণ ঠিকানা সঠিকভাবে লিখুন।' ) );
	}

	try {
		$order = wc_create_order();

		// Add products from cart
		foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
			$product_id   = $values['product_id'];
			$quantity     = $values['quantity'];
			$variation_id = isset( $values['variation_id'] ) ? $values['variation_id'] : 0;
			$target_id    = $variation_id ? $variation_id : $product_id;
			$product      = wc_get_product( $target_id );
			if ( $product ) {
				$order->add_product( $product, $quantity, array( 'variation' => isset( $values['variation'] ) ? $values['variation'] : array() ) );
			}
		}

		// Set Address Details
		$address_data = array(
			'first_name' => $name,
			'last_name'  => '',
			'company'    => '',
			'email'      => $phone . '@easylifedaily.com',
			'phone'      => $phone,
			'address_1'  => $address,
			'city'       => 'Dhaka',
			'country'    => 'BD',
		);
		$order->set_address( $address_data, 'billing' );
		$order->set_address( $address_data, 'shipping' );

		// Add Shipping Item
		$item_shipping = new WC_Order_Item_Shipping();
		$item_shipping->set_method_title( 60 === $shipping ? 'ঢাকা শহরের ভেতরে ডেলিভারি' : 'ঢাকা শহরের বাইরে ডেলিভারি' );
		$item_shipping->set_method_id( 'flat_rate' );
		$item_shipping->set_total( $shipping );
		$order->add_item( $item_shipping );

		// Customer Notes
		if ( ! empty( $notes ) ) {
			$order->set_customer_note( $notes );
		}

		// Set Payment Method as COD
		$order->set_payment_method( 'cod' );
		$order->set_payment_method_title( 'ক্যাশ অন ডেলিভারি (Cash on Delivery)' );

		// Calculate totals and set status to processing
		$order->calculate_totals();
		$order->update_status( 'processing', __( 'Direct Alishop Checkout Order Created', 'alishop-clone' ) );

		// Empty Cart
		WC()->cart->empty_cart();

		$redirect_url = $order->get_checkout_order_received_url();
		wp_send_json_success( array( 'redirect' => $redirect_url ) );

	} catch ( Exception $e ) {
		wp_send_json_error( array( 'message' => 'অর্ডার প্রসেস করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।' ) );
	}
}

/**
 * Sanitize FAQ JSON string in Customizer
 */
function alishop_sanitize_faq_json( $input ) {
	if ( empty( $input ) ) {
		return '[]';
	}
	if ( is_array( $input ) ) {
		return wp_json_encode( $input );
	}
	$decoded = json_decode( $input, true );
	if ( is_array( $decoded ) ) {
		return wp_json_encode( $decoded );
	}
	return '[]';
}

/**
 * Customizer FAQ Manager Control Class
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	class AliShop_FAQ_Manager_Control extends WP_Customize_Control {
		public $type = 'alishop_faq_manager';

		public function render_content() {
			$products = get_posts( array(
				'post_type'      => 'product',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => 'title',
				'order'          => 'ASC',
			) );

			$faqs_json = $this->value();
			if ( empty( $faqs_json ) ) {
				$faqs_json = '[]';
			}
			?>
			<label class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>

			<div class="alishop-faq-customizer-wrapper" id="alishopFaqCustomizer" style="margin-top:8px;">
				<textarea id="alishop_faq_json_input" <?php $this->link(); ?> style="display:none;"><?php echo esc_textarea( $faqs_json ); ?></textarea>

				<!-- Product Filter Dropdown -->
				<div style="margin-bottom:12px; background:#f0f6fc; border:1px solid #bae6fd; padding:10px; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
					<strong style="display:block; margin-bottom:4px; color:#0369a1; font-size:12px;">🎯 নির্দিষ্ট প্রোডাক্টের FAQ ফিল্টার করুন:</strong>
					<select id="faq_filter_product_select" onchange="alishopFaqFilterChanged(this)" style="width:100%; border:1px solid #0284c7; border-radius:4px; padding:6px; font-size:11px; background:#fff; font-weight:600; color:#0f172a;">
						<option value="all">-- সব প্রোডাক্টের FAQ একসাথে দেখুন --</option>
						<?php foreach ( $products as $prod ) : ?>
							<option value="<?php echo esc_attr( $prod->ID ); ?>"><?php echo esc_html( $prod->post_title ); ?> (#<?php echo esc_attr( $prod->ID ); ?>)</option>
						<?php endforeach; ?>
					</select>
				</div>

				<!-- Product Selection Checkboxes for Adding/Editing -->
				<div style="margin-bottom:12px; background:#fff; padding:10px; border:1px solid #ccd0d4; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
					<strong style="display:block; margin-bottom:6px; color:#1d2327; font-size:12px;">১. এই FAQ-টি কোন প্রোডাক্টের জন্য? <span style="color:#d63638;">*</span></strong>
					<div style="max-height:140px; overflow-y:auto; border:1px solid #ddd; padding:6px; background:#f6f7f7; border-radius:4px;" id="faq_product_checkboxes">
						<?php if ( ! empty( $products ) ) : ?>
							<?php foreach ( $products as $prod ) : ?>
								<label style="display:block; margin-bottom:4px; font-size:11px; cursor:pointer; color:#2c3338;">
									<input type="checkbox" class="faq-prod-chk" value="<?php echo esc_attr( $prod->ID ); ?>" style="margin-right:4px;">
									<?php echo esc_html( $prod->post_title ); ?> (#<?php echo esc_attr( $prod->ID ); ?>)
								</label>
							<?php endforeach; ?>
						<?php else : ?>
							<em style="font-size:11px; color:#646970;">কোনো প্রোডাক্ট পাওয়া যায়নি।</em>
						<?php endif; ?>
					</div>
					<small style="color:#646970; font-size:10px; display:block; margin-top:4px;">* এখানে সিলেক্ট করা প্রোডাক্টেই এই FAQ শো করবে (একাদিক সিলেক্ট করতে পারেন)।</small>
				</div>

				<!-- Question & Answer Form Inputs (Multi-row Repeater) -->
				<div style="margin-bottom:12px; background:#fff; padding:10px; border:1px solid #ccd0d4; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
					<input type="hidden" id="faq_editing_id" value="">
					<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
						<strong style="font-size:12px; color:#1d2327;">২. প্রশ্ন ও উত্তর (FAQ List): <span style="color:#d63638;">*</span></strong>
						<button type="button" class="button button-secondary" onclick="alishopAddFaqRow()" style="font-size:10px; height:24px; line-height:22px;">➕ আরও FAQ যোগ করুন</button>
					</div>

					<!-- Repeater Rows Container -->
					<div id="faq_repeater_rows_container">
						<!-- Dynamic rows via JS -->
					</div>

					<div style="margin-top:10px; display:flex; gap:6px; align-items:center; border-top:1px solid #f0f0f1; padding-top:8px;">
						<button type="button" class="button button-primary" id="btn_save_faq" onclick="alishopSaveFaqItem()" style="font-size:11px; height:28px; line-height:26px;">💾 FAQ সেভ / আপডেট করুন</button>
						<button type="button" class="button button-secondary" id="btn_reset_faq" onclick="alishopResetFaqForm()" style="font-size:11px; height:28px; line-height:26px;">রিসেট</button>
					</div>
					<div id="faq_form_msg" style="margin-top:6px; font-size:11px; font-weight:600; color:#d63638;"></div>
				</div>

				<!-- FAQ List Box filtered by selected product -->
				<div style="background:#fff; padding:10px; border:1px solid #ccd0d4; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
					<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
						<strong style="font-size:11px; color:#1d2327;" id="faq_list_title">সংযুক্ত FAQ তালিকা:</strong>
						<span id="faq_count_badge" style="background:#2271b1; color:#fff; padding:1px 6px; border-radius:10px; font-size:10px; font-weight:bold;">0</span>
					</div>
					<div id="alishop_faq_items_container" style="max-height:240px; overflow-y:auto; border:1px solid #f0f0f1; padding:6px; background:#f6f7f7; border-radius:4px;">
					</div>
				</div>
			</div>

			<script>
			(function(){
				window.alishopFaqData = [];
				window.currentSelectedFilterProdId = 'all';

				var textarea = document.getElementById('alishop_faq_json_input');
				if (textarea && textarea.value) {
					try {
						window.alishopFaqData = JSON.parse(textarea.value);
					} catch(e) {
						window.alishopFaqData = [];
					}
				}

				// Helper to add a repeater row
				window.alishopAddFaqRow = function(qVal, aVal) {
					qVal = qVal || '';
					aVal = aVal || '';
					var container = document.getElementById('faq_repeater_rows_container');
					if (!container) return;

					var rowCount = container.children.length + 1;
					var rowDiv = document.createElement('div');
					rowDiv.className = 'faq-repeater-row';
					rowDiv.style.cssText = 'background:#f8fafc; border:1px solid #cbd5e1; padding:8px; margin-bottom:8px; border-radius:5px;';

					rowDiv.innerHTML = '<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">' +
						'<strong style="font-size:10px; color:#334155;">FAQ #' + rowCount + '</strong>' +
						'<button type="button" class="button button-small button-link-delete" onclick="alishopRemoveFaqRow(this)" style="font-size:9.5px; color:#d63638; height:20px; line-height:18px; padding:0 4px;">❌ রিমুভ</button>' +
						'</div>' +
						'<div style="margin-bottom:6px;">' +
						'<input type="text" class="faq-repeater-q" placeholder="প্রশ্ন লিখুন..." value="' + escHtmlAttr(qVal) + '" style="width:100%; border:1px solid #8c8f94; border-radius:4px; padding:4px 6px; font-size:11px;">' +
						'</div>' +
						'<div>' +
						'<textarea class="faq-repeater-a" rows="2" placeholder="বিস্তারিত উত্তর লিখুন..." style="width:100%; border:1px solid #8c8f94; border-radius:4px; padding:4px 6px; font-size:11px;">' + escHtmlAttr(aVal) + '</textarea>' +
						'</div>';

					container.appendChild(rowDiv);
					alishopUpdateRowNumbers();
				};

				window.alishopRemoveFaqRow = function(btn) {
					var container = document.getElementById('faq_repeater_rows_container');
					if (container.children.length <= 1) {
						alert('কমপক্ষে ১টি FAQ ফিল্ড থাকতে হবে!');
						return;
					}
					var row = btn.closest('.faq-repeater-row');
					if (row) {
						row.remove();
						alishopUpdateRowNumbers();
					}
				};

				function alishopUpdateRowNumbers() {
					var rows = document.querySelectorAll('#faq_repeater_rows_container .faq-repeater-row');
					rows.forEach(function(r, idx){
						var title = r.querySelector('strong');
						if (title) title.innerText = 'FAQ #' + (idx + 1);
					});
				}

				function escHtmlAttr(str) {
					return (str || '').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
				}

				window.alishopFaqFilterChanged = function(sel) {
					window.currentSelectedFilterProdId = sel ? sel.value : 'all';
					var listTitle = document.getElementById('faq_list_title');

					if (window.currentSelectedFilterProdId !== 'all') {
						var selectedText = sel.options[sel.selectedIndex].text;
						if (listTitle) listTitle.innerHTML = '📋 ' + escHtml(selectedText) + ' - এর FAQ তালিকা:';
						
						var targetVal = parseInt(window.currentSelectedFilterProdId, 10);
						var chks = document.querySelectorAll('#faq_product_checkboxes input.faq-prod-chk');
						chks.forEach(function(chk){
							if (parseInt(chk.value, 10) === targetVal) {
								chk.checked = true;
							}
						});
					} else {
						if (listTitle) listTitle.innerHTML = 'সংযুক্ত FAQ তালিকা:';
					}

					alishopRenderFaqList();
				};

				window.alishopRenderFaqList = function() {
					var container = document.getElementById('alishop_faq_items_container');
					var badge = document.getElementById('faq_count_badge');
					if (!container) return;

					if (!Array.isArray(window.alishopFaqData)) window.alishopFaqData = [];

					var filterId = window.currentSelectedFilterProdId;
					var filteredList = [];

					window.alishopFaqData.forEach(function(item, originalIdx) {
						var itemObj = Object.assign({}, item, { _origIndex: originalIdx });
						if (filterId === 'all') {
							filteredList.push(itemObj);
						} else {
							var targetVal = parseInt(filterId, 10);
							if (item.products && Array.isArray(item.products) && item.products.indexOf(targetVal) !== -1) {
								filteredList.push(itemObj);
							}
						}
					});

					if (badge) badge.innerText = filteredList.length;

					if (filteredList.length === 0) {
						var emptyMsg = (filterId === 'all') ? 
							'কোনো FAQ যোগ করা হয়নি।' : 
							'এই প্রোডাক্টের জন্য কোনো FAQ যোগ করা হয়নি। উপরে প্রশ্ন ও উত্তর লিখে সেভ করুন।';
						container.innerHTML = '<div style="font-size:11px; color:#646970; text-align:center; padding:12px; background:#fff; border:1px dashed #ccc; border-radius:4px;">' + emptyMsg + '</div>';
						return;
					}

					var html = '';
					filteredList.forEach(function(item) {
						var idx = item._origIndex;
						var prodsCount = item.products ? item.products.length : 0;
						html += '<div style="border:1px solid #c3c4c7; border-radius:4px; padding:8px; margin-bottom:6px; background:#fff; font-size:11px;">';
						html += '<div style="font-weight:600; color:#1d2327; margin-bottom:4px;">❓ ' + escHtml(item.question || '') + '</div>';
						html += '<div style="color:#50575e; margin-bottom:6px; white-space:pre-wrap; font-size:10.5px; background:#fafafa; padding:4px 6px; border-radius:3px;">💬 ' + escHtml(item.answer || '') + '</div>';
						html += '<div style="display:flex; justify-content:space-between; align-items:center; font-size:10px; border-top:1px solid #f0f0f1; padding-top:4px;">';
						html += '<span style="background:#f0f6fc; color:#0969da; padding:1px 5px; border-radius:3px; font-size:9px; font-weight:600;">' + prodsCount + 'টি প্রোডাক্টে যুক্ত</span>';
						html += '<div>';
						html += '<button type="button" class="button button-small" onclick="alishopEditFaqItem(' + idx + ')" style="font-size:9.5px; height:20px; line-height:18px; padding:0 6px; margin-right:4px;">এডিট</button>';
						html += '<button type="button" class="button button-small button-link-delete" onclick="alishopDeleteFaqItem(' + idx + ')" style="font-size:9.5px; color:#d63638; height:20px; line-height:18px; padding:0 4px;">মুছুন</button>';
						html += '</div></div></div>';
					});
					container.innerHTML = html;
				};

				function escHtml(str) {
					var div = document.createElement('div');
					div.appendChild(document.createTextNode(str));
					return div.innerHTML;
				}

				window.alishopSaveFaqItem = function() {
					var msg = document.getElementById('faq_form_msg');
					if (msg) msg.innerText = '';

					var chks = document.querySelectorAll('#faq_product_checkboxes input.faq-prod-chk:checked');
					var prodIds = [];
					chks.forEach(function(chk){
						prodIds.push(parseInt(chk.value, 10));
					});

					if (prodIds.length === 0 && window.currentSelectedFilterProdId !== 'all') {
						prodIds.push(parseInt(window.currentSelectedFilterProdId, 10));
					}

					if (prodIds.length === 0) {
						if (msg) msg.innerText = '❌ অন্তত ১টি প্রোডাক্ট সিলেক্ট করতে হবে!';
						return;
					}

					var rows = document.querySelectorAll('#faq_repeater_rows_container .faq-repeater-row');
					var validPairs = [];

					rows.forEach(function(row){
						var qEl = row.querySelector('.faq-repeater-q');
						var aEl = row.querySelector('.faq-repeater-a');
						var qVal = qEl ? qEl.value.trim() : '';
						var aVal = aEl ? aEl.value.trim() : '';
						if (qVal && aVal) {
							validPairs.push({ question: qVal, answer: aVal });
						}
					});

					if (validPairs.length === 0) {
						if (msg) msg.innerText = '❌ অন্তত ১টি প্রশ্ন এবং উত্তর উভয় ফিল্ড সঠিকভাবে পূরণ করতে হবে!';
						return;
					}

					var editId = document.getElementById('faq_editing_id').value;

					if (editId !== '' && editId !== null && window.alishopFaqData[editId]) {
						// Editing existing item
						window.alishopFaqData[editId] = {
							products: prodIds,
							question: validPairs[0].question,
							answer: validPairs[0].answer
						};
						// If user added extra rows while editing, push them as new items
						for (var i = 1; i < validPairs.length; i++) {
							window.alishopFaqData.push({
								products: prodIds,
								question: validPairs[i].question,
								answer: validPairs[i].answer
							});
						}
					} else {
						// Adding new items
						validPairs.forEach(function(pair){
							window.alishopFaqData.push({
								products: prodIds,
								question: pair.question,
								answer: pair.answer
							});
						});
					}

					alishopUpdateCustomizerFaqField();
					alishopResetFaqForm();
					alishopRenderFaqList();
				};

				window.alishopEditFaqItem = function(idx) {
					var item = window.alishopFaqData[idx];
					if (!item) return;

					document.getElementById('faq_editing_id').value = idx;
					var container = document.getElementById('faq_repeater_rows_container');
					if (container) container.innerHTML = '';

					alishopAddFaqRow(item.question || '', item.answer || '');

					var chks = document.querySelectorAll('#faq_product_checkboxes input.faq-prod-chk');
					chks.forEach(function(chk){
						var val = parseInt(chk.value, 10);
						chk.checked = (item.products && item.products.indexOf(val) !== -1);
					});

					var btn = document.getElementById('btn_save_faq');
					if (btn) btn.innerHTML = '✏️ FAQ আপডেট করুন';
				};

				window.alishopDeleteFaqItem = function(idx) {
					if (confirm('আপনি কি এই FAQ টি মুছে ফেলতে চান?')) {
						window.alishopFaqData.splice(idx, 1);
						alishopUpdateCustomizerFaqField();
						alishopResetFaqForm();
						alishopRenderFaqList();
					}
				};

				window.alishopResetFaqForm = function() {
					document.getElementById('faq_editing_id').value = '';
					var container = document.getElementById('faq_repeater_rows_container');
					if (container) {
						container.innerHTML = '';
						alishopAddFaqRow('', '');
					}
					
					var chks = document.querySelectorAll('#faq_product_checkboxes input.faq-prod-chk');
					chks.forEach(function(chk){
						chk.checked = (window.currentSelectedFilterProdId !== 'all' && parseInt(chk.value, 10) === parseInt(window.currentSelectedFilterProdId, 10));
					});

					var btn = document.getElementById('btn_save_faq');
					if (btn) btn.innerHTML = '💾 FAQ সেভ / আপডেট করুন';
					var msg = document.getElementById('faq_form_msg');
					if (msg) msg.innerText = '';
				};

				function alishopUpdateCustomizerFaqField() {
					var textarea = document.getElementById('alishop_faq_json_input');
					if (textarea) {
						var jsonStr = JSON.stringify(window.alishopFaqData);
						textarea.value = jsonStr;
						var evt = document.createEvent('HTMLEvents');
						evt.initEvent('change', true, true);
						textarea.dispatchEvent(evt);
					}
				}

				setTimeout(function(){
					alishopResetFaqForm();
					alishopRenderFaqList();
				}, 400);
			})();
			</script>
			<?php
		}
	}
}

/**
 * Add Product FAQ Meta Box on WooCommerce Product Edit Screen in Admin
 */
add_action( 'add_meta_boxes', 'alishop_add_product_faq_meta_box' );
function alishop_add_product_faq_meta_box() {
	add_meta_box(
		'alishop_product_faq_mb',
		__( 'EasyLifeDaily Product FAQs (এফএকিউ)', 'easylifedaily' ),
		'alishop_render_product_faq_meta_box',
		'product',
		'normal',
		'high'
	);
}

function alishop_render_product_faq_meta_box( $post ) {
	wp_nonce_field( 'alishop_save_faq_meta', 'alishop_faq_meta_nonce' );
	$faqs = get_post_meta( $post->ID, '_alishop_product_faqs', true );
	if ( ! is_array( $faqs ) ) {
		$faqs = array();
	}
	?>
	<div id="alishop_meta_faq_wrapper">
		<p style="font-size:12px; color:#646970; margin-bottom:10px;">
			এই প্রোডাক্টের জন্য প্রশ্ন ও উত্তর (FAQ) যুক্ত করুন। ফাঁকা রাখলে সাইটে FAQ সেকশন হাইড থাকবে।
		</p>
		<div id="alishop_meta_faq_list">
			<?php foreach ( $faqs as $index => $faq ) : ?>
				<div class="alishop-meta-faq-row" style="background:#f6f7f7; border:1px solid #ccd0d4; padding:10px; margin-bottom:10px; border-radius:4px;">
					<p style="margin:0 0 6px 0;">
						<strong>প্রশ্ন:</strong><br>
						<input type="text" name="alishop_faq_questions[]" value="<?php echo esc_attr( isset( $faq['question'] ) ? $faq['question'] : '' ); ?>" style="width:100%;">
					</p>
					<p style="margin:0 0 6px 0;">
						<strong>উত্তর:</strong><br>
						<textarea name="alishop_faq_answers[]" rows="2" style="width:100%;"><?php echo esc_textarea( isset( $faq['answer'] ) ? $faq['answer'] : '' ); ?></textarea>
					</p>
					<button type="button" class="button button-secondary" onclick="this.parentNode.remove()">মুছুন</button>
				</div>
			<?php endforeach; ?>
		</div>
		<button type="button" class="button button-primary" onclick="alishopAddMetaFaqRow()">+ নতুন FAQ যোগ করুন</button>
	</div>
	<script>
	function alishopAddMetaFaqRow() {
		var container = document.getElementById('alishop_meta_faq_list');
		var div = document.createElement('div');
		div.className = 'alishop-meta-faq-row';
		div.style.cssText = 'background:#f6f7f7; border:1px solid #ccd0d4; padding:10px; margin-bottom:10px; border-radius:4px;';
		div.innerHTML = '<p style="margin:0 0 6px 0;"><strong>প্রশ্ন:</strong><br><input type="text" name="alishop_faq_questions[]" placeholder="প্রশ্ন লিখুন..." style="width:100%;"></p>' +
			'<p style="margin:0 0 6px 0;"><strong>উত্তর:</strong><br><textarea name="alishop_faq_answers[]" rows="2" placeholder="উত্তর লিখুন..." style="width:100%;"></textarea></p>' +
			'<button type="button" class="button button-secondary" onclick="this.parentNode.remove()">মুছুন</button>';
		container.appendChild(div);
	}
	</script>
	<?php
}

add_action( 'save_post_product', 'alishop_save_product_faq_meta_data' );
function alishop_save_product_faq_meta_data( $post_id ) {
	if ( ! isset( $_POST['alishop_faq_meta_nonce'] ) || ! wp_verify_nonce( $_POST['alishop_faq_meta_nonce'], 'alishop_save_faq_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$questions = isset( $_POST['alishop_faq_questions'] ) ? (array) $_POST['alishop_faq_questions'] : array();
	$answers   = isset( $_POST['alishop_faq_answers'] ) ? (array) $_POST['alishop_faq_answers'] : array();

	$faqs = array();
	for ( $i = 0; $i < count( $questions ); $i++ ) {
		$q = sanitize_text_field( $questions[ $i ] );
		$a = sanitize_textarea_field( isset( $answers[ $i ] ) ? $answers[ $i ] : '' );
		if ( ! empty( $q ) && ! empty( $a ) ) {
			$faqs[] = array(
				'question' => $q,
				'answer'   => $a,
			);
		}
	}

	if ( ! empty( $faqs ) ) {
		update_post_meta( $post_id, '_alishop_product_faqs', $faqs );
	} else {
		delete_post_meta( $post_id, '_alishop_product_faqs' );
	}
}

/**
 * Helper function to retrieve FAQs for a specific product ID
 * Combines Customizer FAQs and Product Post Meta FAQs
 */
function alishop_get_product_faqs( $product_id ) {
	$faqs = array();
	
	// 1. Fetch Customizer FAQs
	$customizer_faqs_json = get_option( 'alishop_product_faqs_json', '' );
	if ( ! empty( $customizer_faqs_json ) ) {
		$all_customizer_faqs = json_decode( $customizer_faqs_json, true );
		if ( is_array( $all_customizer_faqs ) ) {
			foreach ( $all_customizer_faqs as $faq ) {
				if ( isset( $faq['products'] ) && is_array( $faq['products'] ) && in_array( intval( $product_id ), array_map( 'intval', $faq['products'] ), true ) ) {
					$faqs[] = array(
						'question' => isset( $faq['question'] ) ? $faq['question'] : '',
						'answer'   => isset( $faq['answer'] ) ? $faq['answer'] : '',
					);
				}
			}
		}
	}

	// 2. Fetch Product Meta FAQs
	$meta_faqs = get_post_meta( $product_id, '_alishop_product_faqs', true );
	if ( ! empty( $meta_faqs ) && is_array( $meta_faqs ) ) {
		foreach ( $meta_faqs as $m_faq ) {
			if ( ! empty( $m_faq['question'] ) && ! empty( $m_faq['answer'] ) ) {
				$faqs[] = array(
					'question' => $m_faq['question'],
					'answer'   => $m_faq['answer'],
				);
			}
		}
	}

	return $faqs;
}





