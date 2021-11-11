<?php
/**
 * Šablono dalis, skirta rodyti įrašo santraukai.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

?>
<div class="entry-content entry__content entry__content--summary">
	<?php
	the_excerpt();
	?>
</div> <!-- END .entry__content.entry__content--summary -->
