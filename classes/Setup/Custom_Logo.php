<?php
/**
 * Anwas_Scratch\Setup\Custom_Logo klasė.
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
 * Klasė, skirta Custom Logo palaikymui.
 */
class Custom_Logo {
	/**
	 * Vykdomi add_action ir add_filter veiksmai.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'after_setup_theme', array( $this, 'action_add_custom_logo_support' ) );
	}


	/**
	 * Temos funkcionalumų pridėjimas (Temos palaikymo žymos).
	 *
	 * @return void
	 */
	public function action_add_custom_logo_support(): void {

		/**
		 * Prdedamas palaikymas tinkintam logtipui (Custom Logo).
		 *
		 * @link https://developer.wordpress.org/themes/functionality/custom-logo/
		 */
		add_theme_support(
			'custom-logo',
			apply_filters(
				'anwas_scratch_custom_logo_args',
				array(
					'width'       => 250,
					'height'      => 250,
					'flex-width'  => true,
					'flex-height' => true,
				)
			)
		);
	}

	/**
	 * Įterpia tinkinto logotipo html.
	 *
	 * @return void
	 */
	public static function display_custom_logo(): void {

		if ( ! has_custom_logo() ) {
			return;
		}

		$aria_current = ( is_front_page() && ! is_paged() ) ? ' aria-current="page"' : '';

		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$logo_attr      = array(
			'class' => 'skip-lazy',
		);
		$logo           = wp_get_attachment_image( $custom_logo_id, 'full', false, $logo_attr );

		printf(
			'<a href="%1$s" class="custom-logo-link" rel="home"%2$s>%3$s</a>',
			esc_url( home_url( '/' ) ),
			$aria_current, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$logo // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}

}
