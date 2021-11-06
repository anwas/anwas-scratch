<?php
/**
 * Šablono dalis, skirta rodyti puslapiavimui (įrašo ar puslapi skaidymas į puslapius).
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

// Įrašo navigacijos nuorodos (jei įrašas suskirstytas į kelis puslapius).
wp_link_pages(
	array(
		'before'         => '<nav class="navigation link-pages-navigation" role="navigation" aria-label="' . esc_attr__( 'Content pages navigation', 'anwas-scratch' ) . '"><div class="post-nav-links nav-links"><span class="post-nav-links__title">' . esc_html__( 'Pages:', 'anwas-scratch' ) . '</span>',
		'after'          => '</div></nav>',
		'aria_current'   => 'page',
		'next_or_number' => 'number',
		'separator'      => '',
		'link_before'    => '',
		'link_after'     => '',
		'pagelink'       => ' % ',
		'echo '          => 1,
	)
);
