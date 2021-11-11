<?php
/**
 * Šablono dalis, skirta rodyti įrašo pavadinimui.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

if ( is_singular() ) {
	the_title( '<h1 class="entry-title entry__title entry__title--singular">', '</h1>', true );
} else {
	the_title( '<h2 class="entry-title entry__title"><a href="' . esc_url( get_permalink() ) . '" class="entry__title-link" rel="bookmark">', '</a></h2>' );
}
