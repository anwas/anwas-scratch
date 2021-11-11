<?php
/**
 * Šablono dalis, skirta rodyti įrašo poraštės blokui.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

?>
<footer class="entry-footer entry__footer">
	<?php
	get_template_part( 'template-parts/content/entry_taxonomies', get_post_type() );

	get_template_part( '/template-parts/content/entry_actions', get_post_type() );
	?>
</footer> <!-- END .entry__footer -->
