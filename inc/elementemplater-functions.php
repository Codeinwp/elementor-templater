<?php
/**
 * Side cases template functions.
 */

$theme = get_option( 'template' );
if ( 'GeneratePress' == $theme || 'generatepress' == $theme ) {
	if ( ! function_exists( 'elementor_generate_title' ) ) {

		function elementor_generate_title() {
			if ( generate_show_title() ) : ?>
				<header class="entry-header">
					<div class="grid-container">
						<?php the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' ); ?>
					</div>
				</header><!-- .entry-header -->
				<?php
			endif;
		}
	}
	add_action( 'elementor_page_elements', 'elementor_generate_title', 10 );
}
