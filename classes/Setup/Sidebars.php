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
use function \register_sidebar;
use function \is_active_sidebar;
use function \dynamic_sidebar;

/**
 * Klasė, skirta Sidebars palaikymui.
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
