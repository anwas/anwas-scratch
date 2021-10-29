<?php
/**
 * Anwas_Scratch\Helpers klasė.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

use \Anwas_Scratch\Setup\Styles as Anwas_Scratch_Styles;

use function \wp_get_theme;
use function \get_template;
use function \get_parent_theme_file_path;
use function \wp_print_styles;
use function \_doing_it_wrong;
use function \esc_html;

/**
 * Temos pagalbinių metodų klasė.
 */
final class Helpers {
	/**
	 * Nuoroda į „Helpers“ klasės egzempliorių.
	 *
	 * @var Helpers
	 */
	private static $instance;

	/**
	 * Grąžina šios klasės „Helpers“ egzempliorių.
	 *
	 * @return Helpers „Helpers“ klasės egzempliorius.
	 */
	public static function get_instance(): Helpers {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}


	/**
	 * Tiesiogiai spausdina stilių nuorodų („<link>“) žymas.
	 *
	 * Tai turėtų būti naudojama stilių lentelėms, kurios nėra visuotinės,
	 * todėl turėtų būti įkeliamos tik tuo atveju, jei iš tikrųjų yra HTML žymėjimas,
	 * už kurį jie yra atsakingi. Šablono dalys turėtų naudoti šį metodą,
	 * kai susijusiam žymėjimui reikia įkelti konkretų stiliaus lapą.
	 * Jei iš anksto įkeltos stiliaus lentelės išjungtos, šis metodas nieko nedarys.
	 *
	 * Jei nurodyto stilių žyma „<link>“ jau buvo išspausdinta, ji bus praleista.
	 *
	 * @param string ...$handles Viena ar daugiau stilių pavadinimų (handle).
	 */
	public function print_styles( string ...$handles ) {

		static $styles_obj = null;

		if ( null === $styles_obj ) {
			$styles_obj = new Anwas_Scratch_Styles();
		}

		// Jei išankstinio įkėlimo stiliai išjungti (taigi jie jau buvo įrašyti į eilę), stabdome metodo vykdymą.
		if ( ! $styles_obj->preloading_styles_enabled() ) {
			return;
		}

		$css_files = $styles_obj->get_css_files();
		$handles   = array_filter(
			$handles,
			function( $handle ) use ( $css_files ) {
				$is_valid = isset( $css_files[ $handle ] ) && ! $css_files[ $handle ]['global'];
				if ( ! $is_valid ) {
					/* translators: %s: stylesheet handle */
					_doing_it_wrong( __CLASS__ . '::print_styles()', esc_html( sprintf( __( 'Invalid theme stylesheet handle: %s', 'anwas-scratch' ), $handle ) ), 'Anwas Scratch 1.0.0' );
				}
				return $is_valid;
			}
		);

		if ( empty( $handles ) ) {
			return;
		}

		wp_print_styles( $handles );
	}

	/**
	 * Grąžina temos versijos numerį.
	 *
	 * @return string Temos versijos numeris.
	 */
	public function get_version() : string {
		static $theme_version = null;

		if ( null === $theme_version ) {
			$theme_version = wp_get_theme( get_template() )->get( 'Version' );
		}

		return (string) $theme_version;
	}

	/**
	 * Versijos eilutė priklausomai nuo to ar WP_DEBUG konstanta yra true.
	 *
	 * @param string $filepath Failo kelias reliatyvus temos root keliui.
	 *
	 * @return string|null Jei failas neegzistuoja – null,
	 *                      jei WP_DEBUG yra false – temos versijos numeris,
	 *                      kitu atveju failo keitimo laiko eilutė.
	 */
	public function get_asset_version( string $filepath ): ?string {

		if ( ! file_exists( get_parent_theme_file_path( $filepath ) ) ) {
			return null;
		}

		if ( \WP_DEBUG ) {
			return (string) filemtime( get_parent_theme_file_path( $filepath ) );
		}

		return $this->get_version();
	}


	/**
	 * Apsaugotas konstruktorius blokuoja naujų klasės egzempliorių kūrimą
	 * per new raktažodį už šios klasės ribų.
	 */
	private function __construct() {}

	/**
	 * Privatus _clone metodas blokuoja klasės egzempliorių klonavimą.
	 *
	 * @return void
	 */
	private function __clone(): void {}

	/**
	 * Privatus __wakeup metodas blokuoja klasės egzempliorių išserializavimą.
	 * Nuo PHP 8.0 šis metodas turi būti public.
	 *
	 * @return void
	 */
	public function __wakeup(): void {}
}
