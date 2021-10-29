<?php
/**
 * Standartinis temos funkcijų failas.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

// Globalios PHP funkcijos.
use function file_exists;
use function class_exists;

// Globalios WordPress funkcijos.
use function get_parent_theme_file_path; // Sugrąžina failo kelią tėvinėje temoje.

/*
 * Užkrauname Composer autoload failą.
 * Visi temos klasės failai yra su vardų sritimi (namespace),
 * todėl čia ir galime naudoti composer PSR-4 auto užkrovimą.
 */
if ( file_exists( get_parent_theme_file_path( '/vendor/autoload.php' ) ) ) {
	require_once get_parent_theme_file_path( '/vendor/autoload.php' );
}

if ( class_exists( '\Anwas_Scratch\Helpers' ) ) {
	/**
	 * Temos pagalbinių metodų rinkinys.
	 *
	 * @return \Anwas_Scratch\Helpers
	 */
	function anwas_scratch(): \Anwas_Scratch\Helpers {
		return \Anwas_Scratch\Helpers::get_instance();
	}
}

// Registruojamos ir inicijuojamos visos klasės.
if ( class_exists( '\Anwas_Scratch\Init' ) ) {
	\Anwas_Scratch\Init::register_services();
}
