<?php
/**
 * Šablono dalis, skirta rodyti įrašo santrauką paieškos rezultatuose.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
	<?php
	get_template_part( '/template-parts/content/entry_header', get_post_type() );

	get_template_part( '/template-parts/content/entry_summary', get_post_type() );
	?>
</article>
