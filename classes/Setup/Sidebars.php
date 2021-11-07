<?php
/**
 * Anwas_Scratch\Setup\Sidebars klasė.
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
use function \apply_filters;
use function \register_sidebar;
use function \is_active_sidebar;
use function \dynamic_sidebar;

/**
 * Klasė, skirta Sidebars palaikymui.
 *
 * Registruoja valdiklių sritis, filtruoja „body“ elemento klases.
 */
class Sidebars {
	const PRIMARY_SIDEBAR_SLUG   = 'primary';
	const SECONDARY_SIDEBAR_SLUG = 'secondary';
	const FOOTER_SIDEBAR_SLUG    = 'footer';

	/**
	 * Vykdomi add_action ir add_filter veiksmai.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'widgets_init', array( $this, 'action_register_sidebars' ) );
		add_filter( 'body_class', array( $this, 'filter_body_classes' ) );
	}


	/**
	 * Temos funkcionalumų pridėjimas (Temos palaikymo žymos).
	 *
	 * @return void
	 */
	public function action_register_sidebars(): void {
		/* Užregistruojame „primary“ (pagrindinė) valdiklių sritis. */
		register_sidebar(
			array(
				'id'            => static::PRIMARY_SIDEBAR_SLUG,
				'name'          => __( 'Primary Sidebar', 'anwas-scratch' ),
				'description'   => __( 'Primary widgets area.', 'anwas-scratch' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
		/* Užregistruojame „secondary“ (papildoma) valdiklių sritis. */
		register_sidebar(
			array(
				'id'            => static::SECONDARY_SIDEBAR_SLUG,
				'name'          => __( 'Secondary Sidebar', 'anwas-scratch' ),
				'description'   => __( 'Secondary widgets area.', 'anwas-scratch' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
		/* Užregistruojame „footer“ (svetainės poraštės) valdiklių sritis. */
		register_sidebar(
			array(
				'id'            => static::FOOTER_SIDEBAR_SLUG,
				'name'          => __( 'Footer Sidebar', 'anwas-scratch' ),
				'description'   => __( 'Site footer widgets area.', 'anwas-scratch' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}

	/**
	 * Filtruoja dabartinio įrašo ar puslapio „body“ elemento CSS klasių pavadinimų masyvą.
	 *
	 * Prideda CSS klases atsižvelgiant į tai, kokios valdiklių sritys aktyvios ar neaktyvios.
	 *
	 * @param array $classes „body“ elemento CSS klasių pavadinimų masyvas.
	 *
	 * @return array
	 */
	public function filter_body_classes( array $classes ): array {
		/**
		 * Valdiklių CSS klasių masyvas.
		 *
		 * @var array $sidebars_classes
		 */
		$sidebars_classes = array();

		// Aktyvi tik „footer“ (svetainės poraštės) valdiklių sritis.
		if ( self::is_footer_sidebar_active() ) {
			$sidebars_classes[] = 'has-footer-sidebar';
		}

		// Jei „primary“ (pagrindinė) ir „secondary“ (papildoma) valdyklių sritys neaktyvios,
		// pridedame klasę, pritaikome filtrą ir iš karto baigiame filtravimą.
		if ( ! self::is_primary_sidebar_active() && ! self::is_secondary_sidebar_active() ) {
			$sidebars_classes[] = 'no-sidebars';

			/** Šis filtras dokumentuotas faile classes/Setup/Sidebars.php */
			$sidebars_classes = apply_filters( 'anwas_scratch_sidebars_classes', $sidebars_classes );

			$classes = array_merge( $classes, $sidebars_classes );

			return $classes;
		}

		// Šioje vietoje jau aišku, kad aktyvi bent viena valdiklių sritis.
		$sidebars_classes[] = 'has-sidebars';

		// Aktyvi tik „primary“ (pagrindinė) valdiklių sritis.
		if ( self::is_primary_sidebar_active() && ! self::is_secondary_sidebar_active() ) {
			$sidebars_classes[] = 'has-sidebars--one-sidebar';
			$sidebars_classes[] = 'has-sidebars--' . static::PRIMARY_SIDEBAR_SLUG . '-sidebar';
		}

		// Aktyvi tik „secondary“ (papildoma) valdiklių sritis.
		if ( ! self::is_primary_sidebar_active() && self::is_secondary_sidebar_active() ) {
			$sidebars_classes[] = 'has-sidebars--one-sidebar';
			$sidebars_classes[] = 'has-sidebars--' . static::SECONDARY_SIDEBAR_SLUG . '-sidebar';
		}

		// Aktyvios abi „primary“ (pagrindinė) ir „secondary“ (papildoma) valdiklių sritys.
		if ( self::is_primary_sidebar_active() && self::is_secondary_sidebar_active() ) {
			$sidebars_classes[] = 'has-sidebars--two-sidebars';
			$sidebars_classes[] = 'has-sidebars--' . static::PRIMARY_SIDEBAR_SLUG . '-sidebar';
			$sidebars_classes[] = 'has-sidebars--' . static::SECONDARY_SIDEBAR_SLUG . '-sidebar';
		}

		/**
		 * Filtras valdiklių CSS klasių masyvui.
		 *
		 * @param array $sidebars_classes Valdiklių CSS klasių masyvas.
		 *
		 * return array
		 */
		$sidebars_classes = apply_filters( 'anwas_scratch_sidebars_classes', $sidebars_classes );

		$classes = array_merge( $classes, $sidebars_classes );

		return $classes;
	}

	/**
	 * Tikrina, ar „primary“ (pagrindinė) valdiklių sritis yra aktyvi.
	 *
	 * @return bool True, jei „primary“ valdiklių sritis aktyvi, false kitu atveju.
	 */
	public static function is_primary_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::PRIMARY_SIDEBAR_SLUG );
	}

	/**
	 * Rodoma „primary“ (pagrindinė) valdiklių sritis.
	 */
	public static function display_primary_sidebar() {
		dynamic_sidebar( static::PRIMARY_SIDEBAR_SLUG );
	}

	/**
	 * Tikrina, ar „secondary“ (papildoma) valdiklių sritis yra aktyvi.
	 *
	 * @return bool True, jei „secondary“ valdiklių sritis aktyvi, false kitu atveju.
	 */
	public static function is_secondary_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::SECONDARY_SIDEBAR_SLUG );
	}

	/**
	 * Rodoma „secondary“ (papildoma) valdiklių sritis.
	 */
	public static function display_secondary_sidebar() {
		dynamic_sidebar( static::SECONDARY_SIDEBAR_SLUG );
	}

	/**
	 * Tikrina, ar „footer“ (svetainės poraštės) valdiklių sritis yra aktyvi.
	 *
	 * @return bool True, jei „footer“ valdiklių sritis aktyvi, false kitu atveju.
	 */
	public static function is_footer_sidebar_active() : bool {
		return (bool) is_active_sidebar( static::FOOTER_SIDEBAR_SLUG );
	}

	/**
	 * Rodoma „footer“ (svetainės poraštės) valdiklių sritis.
	 */
	public static function display_footer_sidebar() {
		dynamic_sidebar( static::FOOTER_SIDEBAR_SLUG );
	}

}
