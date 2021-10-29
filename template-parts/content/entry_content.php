<?php
/**
 * Šablono dalis, skirta rodyti įrašo turinio blokui (content).
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

?>
<div class="entry__content">
	<?php
	the_content(
		sprintf(
			wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
				__( 'Skaityti daugiau<span class="screen-reader-text"> „%s“</span>', 'anwas-scratch' ),
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
			wp_kses_post( get_the_title() )
		)
	);

	// Įrašo navigacijos nuorodos (jei įrašas suskirstytas į kelis puslapius).
	wp_link_pages();
	?>
</div> <!-- END .entry__content -->
