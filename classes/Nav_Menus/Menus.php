<?php
/**
 * Anwas_Scratch\Nav_Menus\Menus klasė.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch\Nav_Menus;

use \Wp_Post;

// Globalios WordPress funkcijos.
use function \add_action;

/**
 * Temos meniu palaikymo klasė.
 */
class Menus {
	const PRIMARY_NAV_MENU_SLUG = 'primary';

	/**
	 * Vykdomi add_action ir add_filter veiksmai.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'after_setup_theme', array( $this, 'action_register_nav_menus' ) );

		// Panašu, kad šie du filtrai veikia tik su default Walker_Nav_Menu.
		// TODO: išsiaiškinti ar tikrai šie du filtrai veikia tik su default Walker_Nav_Menu.
		add_filter( 'nav_menu_link_attributes', array( $this, 'filter_nav_menu_link_attributes_aria_current' ), 10, 2 );
		add_filter( 'page_menu_link_attributes', array( $this, 'filter_nav_menu_link_attributes_aria_current' ), 10, 2 );
	}

	/**
	 * Registruoja Navigacijos meniu.
	 */
	public function action_register_nav_menus(): void {
		register_nav_menus(
			array(
				static::PRIMARY_NAV_MENU_SLUG => esc_html__( 'Primary menu', 'anwas-scratch' ),
			)
		);
	}

	/**
	 * Patikrina, ar aktyvus pagrindinis navigacijos meniu.
	 *
	 * @return bool True, jei aktyvus pagrindinis navigacijos meniu, kitu atveju false.
	 */
	public static function is_primary_nav_menu_active() : bool {
		return (bool) has_nav_menu( static::PRIMARY_NAV_MENU_SLUG );
	}

	/**
	 * Rodo pagrindinį navigacijos meniu.
	 *
	 * @param array $args Neprivaloma. Argumentų rinkinys. Palaikomų argumentų sąrašą
	 *                    rasite `wp_nav_menu()` dokumentacijoje.
	 */
	public static function display_primary_nav_menu( array $args = array() ): void {

		if ( ! isset( $args['container'] ) ) {
			$args['container'] = '';
		}

		$args['theme_location'] = static::PRIMARY_NAV_MENU_SLUG;

		wp_nav_menu( $args );
	}

	/**
	 * Filtruoja HTML atributus, taikomus meniu elemento prieraišo (anchor) elementui.
	 *
	 * Patikrina, ar meniu elementas yra dabartinis meniu elementas, ir prideda aria atributą "current".
	 * Panašu, kad veikia tik su default Walker_Nav_Menu.
	 * TODO: išsiaiškinti ar tikrai veikia tik su default Walker_Nav_Menu.
	 *
	 * @param array   $atts Meniu elemento elementui „<a>“ taikomi HTML atributai.
	 * @param WP_Post $item Dabartinis meniu elementas.
	 * @return array Modifikuoti HTML atributai.
	 */
	public function filter_nav_menu_link_attributes_aria_current( array $atts, WP_Post $item ) : array {
		if ( isset( $item->current ) ) {
			if ( $item->current ) {
				$atts['aria-current'] = 'page';
			}
		} elseif ( ! empty( $item->ID ) ) {
			global $post;

			if ( ! empty( $post->ID ) && (int) $post->ID === (int) $item->ID ) {
				$atts['aria-current'] = 'page';
			}
		}

		return $atts;
	}
}
