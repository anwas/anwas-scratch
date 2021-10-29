<?php
/**
 * Anwas_Scratch\Setup\Custom_Header klasė.
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
use function \has_header_image;
use function \the_header_image_tag;
use function \get_theme_support;
use function \display_header_text;
use function \esc_attr;

/**
 * Klasė, skirta Custom Header palaikymui.
 */
class Custom_Header {
	/**
	 * Vykdomi add_action ir add_filter veiksmai.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'after_setup_theme', array( $this, 'action_add_custom_header_support' ) );
	}


	/**
	 * Temos funkcionalumų pridėjimas (Temos palaikymo žymos).
	 *
	 * @return void
	 */
	public function action_add_custom_header_support(): void {

		/**
		 * Prideda tinkintos antraštės palaikymą.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
		 */
		add_theme_support(
			'custom-header',
			apply_filters(
				'anwas_scratch_custom_header_args',
				array(
					'default-image'      => '',
					'default-text-color' => '000000',
					'width'              => 1600,
					'height'             => 250,
					'flex-width'         => true,
					'flex-height'        => true,
					'wp-head-callback'   => array( $this, 'wp_head_callback' ),
				)
			)
		);
	}

	/**
	 * Jei reikia, išveda papildomus tinkintos antraštės stilius.
	 */
	public function wp_head_callback() {
		$header_text_color = get_header_textcolor();

		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		if ( ! display_header_text() ) {
			echo '<style type="text/css">.site-title, .site-description { position: absolute; clip: rect(1px, 1px, 1px, 1px); }</style>';
			return;
		}

		echo '<style type="text/css">.site-title a, .site-description { color: #' . esc_attr( $header_text_color ) . '; }</style>';
	}

	/**
	 * Įterpia Custom Header html.
	 *
	 * @return void
	 */
	public static function display_custom_header_image(): void {

		if ( has_header_image() ) {
			?>
			<figure class="header-image">
				<?php the_header_image_tag(); ?>
			</figure><!-- .header-image -->
			<?php
		}
	}

}
