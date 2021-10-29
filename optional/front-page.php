<?php
/**
 * Pradinio puslapio (fron-page) temos šablonas.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

use function \Anwas_Scratch\anwas_scratch;

get_header();

anwas_scratch()->print_styles( 'anwas-scratch-front-page' );
?>
<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();

				get_template_part( '/template-parts/content/entry' );
			} // END while loop.

			/* Puslapiavimas čia (jei reikia, galima naudoti 'template-parts/content/pagination' šablono dalies failą). */
		} else {
			/* Nieko nerastas */
			get_template_part( '/template-parts/content/entry', 'none' );
		} // END if have posts.
		?>
	</main>
</div> <!-- END #primary .content-area -->
<?php
get_sidebar();
get_footer();
