<?php
/**
 * Šablonas, skirtas rodyti 404 puslapius (nerasta – not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
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
		<?php get_template_part( 'template-parts/content/error', '404' ); ?>
	</main>
</div> <!-- END #primary .content-area -->
<?php
get_footer();
