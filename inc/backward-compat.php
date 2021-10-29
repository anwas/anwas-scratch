<?php
/**
 * Atgalinio suderinamumo funkcijos.
 *
 * Vardų sritis (namespace) specialiai paliekama globali.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

if ( ! function_exists( 'wp_body_open' ) ) {

	/**
	 * „Wp_body_open“ pritaikymas, užtikrinantis atgalinį suderinamumą
	 * su senesnėmis nei 5.2 „WordPress“ versijomis.
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

if ( ! function_exists( 'wp_timezone_string' ) ) {

	/**
	 * „wp_timezone_string“ pritaikymas, užtikrinantis atgalinį suderinamumą
	 * su senesnėmis nei 5.3 „WordPress“ versijomis.
	 */
	function wp_timezone_string() {
		$timezone_string = get_option( 'timezone_string' );

		if ( $timezone_string ) {
			return $timezone_string;
		}

		$offset  = (float) get_option( 'gmt_offset' );
		$hours   = (int) $offset;
		$minutes = ( $offset - $hours );

		$sign      = ( $offset < 0 ) ? '-' : '+';
		$abs_hour  = abs( $hours );
		$abs_mins  = abs( $minutes * 60 );
		$tz_offset = sprintf( '%s%02d:%02d', $sign, $abs_hour, $abs_mins );

		return $tz_offset;
	}
}


if ( ! function_exists( 'wp_timezone' ) ) {

	/**
	 * „wp_timezone“ pritaikymas, užtikrinantis atgalinį suderinamumą
	 * su senesnėmis nei 5.3 „WordPress“ versijomis.
	 */
	function wp_timezone() {
		return new \DateTimeZone( wp_timezone_string() );
	}
}
