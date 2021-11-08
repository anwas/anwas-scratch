<?php
/**
 * Anwas_Scratch\Setup\Custom_Background klasė.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch\Setup;

// Globalios WordPress funkcijos.
use function \add_action;
use function \add_filter;
use function \add_theme_support;
use function \apply_filters;
use function \get_background_image;

/**
 * Klasė, skirta Custom Background palaikymui.
 */
class Custom_Background {
	/**
	 * Vykdomi add_action ir add_filter veiksmai.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'after_setup_theme', array( $this, 'action_add_custom_background_support' ) );
		add_filter( 'body_class', array( $this, 'filter_body_class' ) );
	}

	/**
	 * Filtruoja dabartinio įrašo ar puslapio „body“ elemento CSS klasių pavadinimų masyvą.
	 *
	 * Prideda CSS klases atsižvelgiant į tai, ar yra nustatytas fono paveikslėlis.
	 *
	 * @param array $classes „body“ elemento CSS klasių pavadinimų masyvas.
	 *
	 * @return array
	 */
	public function filter_body_class( array $classes ): array {
		$bg_image = get_background_image();

		if ( ! empty( $bg_image ) ) {
			return array_merge( $classes, array( 'custom-background--img' ) );
		}

		return $classes;
	}


	/**
	 * Temos funkcionalumų pridėjimas (Temos palaikymo žymos).
	 *
	 * @return void
	 */
	public function action_add_custom_background_support(): void {

		/**
		 * Pridedamas palaikymas tinkintam svetainės fono paveikslėliui.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/custom-backgrounds/
		 * @link https://codex.wordpress.org/Custom_Backgrounds
		 */
		add_theme_support(
			'custom-background',
			apply_filters(
				'anwas_scratch_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);
	}

}
