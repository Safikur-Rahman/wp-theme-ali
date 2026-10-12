<?php
/**
 * The template for displaying all pages
 *
 * @package EasyLifeDaily
 */

get_header();

$is_elementor = false;
if ( class_exists( '\Elementor\Plugin' ) ) {
	$document = \Elementor\Plugin::$instance->documents->get( get_the_ID() );
	if ( $document && $document->is_built_with_elementor() ) {
		$is_elementor = true;
	}
}
?>

<main id="primary" class="site-main alishop-page-main">
	<div class="<?php echo $is_elementor ? 'elementor-page-container' : 'alishop-container alishop-default-page-padding'; ?>">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php if ( ! $is_elementor ) : ?>
					<header class="entry-header" style="margin-bottom: 24px;">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header>
				<?php endif; ?>

				<div class="entry-content">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'easylifedaily' ),
							'after'  => '</div>',
						)
					);
					?>
				</div>
			</article>
			<?php
		endwhile;
		?>
	</div>
</main>

<?php
get_footer();
