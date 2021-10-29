<?php
/**
 * Puslapių neprisijungus šablono failas.
 *
 * @link https://github.com/GoogleChromeLabs/pwa-wp/wiki/Service-Worker#offline--500-error-handling
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
		<?php get_template_part( 'template-parts/content/error', 'offline' ); ?>
	</main>
</div> <!-- END #primary .content-area -->
<?php
get_footer();
