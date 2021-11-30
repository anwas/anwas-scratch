<?php
/**
 * Anwas_Scratch\Init klasė.
 *
 * Ši tema naudoja PSR-4 ir OOP logiką, o ne procedūrinį programavimą.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

/**
 * Init klasė registruoja visus servisus (klases).
 */
final class Init {
	/**
	 * Nuoroda į „Init“ klasės egzempliorių.
	 *
	 * @var Init
	 */
	private static $instance;

	/**
	 * Grąžina šios klasės „Init“ egzempliorių.
	 *
	 * @return Init „Init“ klasės egzempliorius.
	 */
	public static function get_instance(): Init {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Išsaugo visas klases į masyvą.
	 *
	 * @return array Pilnas klasių masyvas.
	 */
	public static function get_services() {
		// Naudokite ClassName::class, kad gautumėte visiškai kvalifikuotą klasės ClassName pavadinimą.
		// https://web.archive.org/web/20201205192011/https://www.php.net/manual/en/migration55.new-features.php .
		// https://stackoverflow.com/questions/30770148/what-is-class-in-php .
		// Speciali ::class konstanta leidžia visiškai kvalifikuotą klasės pavadinimo skyrimą kompiliavimo metu, tai naudinga klasėms su vardų erdve.
		// https://www.php.net/manual/en/language.oop5.constants.php .
		return array(
			Setup\Base_Support::class,
			Setup\Post_Thumbnails::class,
			Setup\Custom_Logo::class,
			Setup\Custom_Header::class,
			Setup\Custom_Background::class,
			Setup\Sidebars::class,
			Setup\Styles::class,
			Setup\Scripts::class,
			Nav_Menus\Menus::class,
			Plugins\Jetpack::class,
			Plugins\Woocommerce::class,
			Admin\Settings\Settings::class,
			Admin\Customizer::class,
		);
	}

	/**
	 * Vykdomas ciklas per klasių masyvą, inicijuoja jas ir iškviečia register() metodą, jei jis egzistuoja.
	 *
	 * @return void
	 */
	public static function register_services(): void {
		$services = self::get_services();

		foreach ( $services as $class ) {
			$service = self::instantiate( $class );

			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
		}
	}

	/**
	 * Inicijuoja klasę.
	 *
	 * @param  string $class Klasė iš servisų masyvo.
	 *
	 * @return object Nauja klasės instancija.
	 */
	private static function instantiate( string $class ): object {
		return new $class();
	}

	/**
	 * Apsaugotas konstruktorius blokuoja naujų klasės egzempliorių kūrimą
	 * per new raktažodį už šios klasės ribų.
	 */
	private function __construct() {}

	/**
	 * Privatus _clone metodas blokuoja klasės egzempliorių klonavimą.
	 *
	 * Pastaba: šis metodas negali deklaruoti grąžinimo tipo (bent jau PHP 7.4 wp-cli gaunama klaida).
	 */
	private function __clone() {}

	/**
	 * Privatus __wakeup metodas blokuoja klasės egzempliorių išserializavimą.
	 *
	 * Pastaba: nuo PHP 8.0 šis metodas privalo būti public.
	 *
	 * @return void
	 */
	public function __wakeup(): void {}
}
