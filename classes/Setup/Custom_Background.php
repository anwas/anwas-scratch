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
use function \add_theme_support;
use function \apply_filters;

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
