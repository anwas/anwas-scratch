<?php
/**
 * Šablono dalis, skirta rodyti įrašo antraštės blokui.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

?>
<header class="entry-header entry__header">
	<?php
	get_template_part( '/template-parts/content/entry_title', get_post_type() );

	get_template_part( 'template-parts/content/entry_meta', get_post_type() );

	get_template_part( '/template-parts/content/entry_thumbnail', get_post_type() );
	?>
</header> <!-- END .entry__header -->
