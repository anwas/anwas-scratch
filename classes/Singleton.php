<?php
/**
 * Singleton klasės naudojimo pavyzdys.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

/**
 * Singleton klasės naudojimo pavyzdys.
 */
final class Singleton {
	/**
	 * Nuoroda į „Singleton“ klasės egzempliorių.
	 *
	 * @var Singleton
	 */
	private static $instance;

	/**
	 * Grąžina šios klasės „Singleton“ egzempliorių.
	 *
	 * @return Singleton „Singleton“ klasės egzempliorius.
	 */
	public static function get_instance(): Singleton {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}

		return static::$instance;
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
