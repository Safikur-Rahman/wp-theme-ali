<?php
/**
 * Template Name: CartFlows Step
 *
 * The template for displaying CartFlows Steps
 *
 * @package EasyLifeDaily
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$step_id  = get_the_ID();
$template = get_post_meta( $step_id, '_wp_page_template', true );

// If the step is set to CartFlows Canvas, Elementor Canvas, or default funnel page
// (CartFlows funnel steps are designed as full-width distraction-free canvas pages without store header/footer)
if ( 'cartflows-boxed' !== $template && 'default-with-header' !== $template ) :
	?>
	<!doctype html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class( 'cartflows-canvas-template' ); ?>>
	<?php wp_body_open(); ?>

	<div id="page" class="site cartflows-step-content-wrapper">
		<main id="primary" class="site-main">
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
			?>
		</main>
	</div>

	<?php wp_footer(); ?>
	</body>
	</html>
	<?php
	return;
endif;

// Otherwise, if boxed / default with theme header is explicitly selected:
get_header();
?>

<main id="primary" class="site-main cartflows-step-default">
	<div class="cartflows-container">
		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
