<?php
/**
 * Pagrindinis temos šablono failas.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

get_header();
?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();

				if ( is_search() ) {
					get_template_part( '/template-parts/content/entry', 'search' );
				} else {
					get_template_part( '/template-parts/content/entry', get_post_type() );
				}
			} // END while ciklas.

			/* Puslapiavimas čia. */
			if ( ! is_singular() ) {
				get_template_part( 'template-parts/content/pagination' );
			}
		} else {
			/* Nieko nerasta. */
			get_template_part( '/template-parts/content/entry', 'none' );
		} // END if have posts.
		?>
	</main>
</div> <!-- END #primary .content-area -->
<?php
get_sidebar();
get_footer();
