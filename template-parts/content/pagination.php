<?php
/**
 * Šablono dalis, skirta rodyti puslapiavimui (įrašų, puslapių ar CPT sąrašų puslapiavimas).
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

the_posts_pagination(
	array(
		'mid_size'           => 2,
		'prev_text'          => _x( 'Previous', 'previous set of search results', 'anwas-scratch' ),
		'next_text'          => _x( 'Next', 'next set of search results', 'anwas-scratch' ),
		'screen_reader_text' => __( 'Page navigation', 'anwas-scratch' ),
	)
);
