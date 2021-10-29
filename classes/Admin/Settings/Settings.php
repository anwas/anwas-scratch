<?php
/**
 * Anwas_Scratch\Admin\Settings\Settings klasė.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch\Admin\Settings;

// Globalios WordPress funkcijos.
use function \add_action;
use function \add_menu_page;
use function \add_submenu_page;
use function \current_user_can;
use function \get_template_part;

/**
 * Temos nustatymų klasė.
 */
class Settings {
	/**
	 * Vykdomi add_action ir add_filter veiksmai.
	 *
	 * @return void
	 */
	public function register(): void {
		// Jei esamas vartotojas turi tinkamas teises, vykdome kodą.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );
	}

	/**
	 * Pridedami temos nustatymų meniu.
	 *
	 * @return void
	 */
	public function add_menu_pages(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		// add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null ) .
		add_menu_page( 'Anwas Scratch Theme Settings', 'Anwas Scratch', 'manage_options', 'anwas_scratch_theme_settings', array( $this, 'display_theme_general_settings_menu_page' ), '', null );

		// add_submenu_page( $parent_slug:string, $page_title:string, $menu_title:string, $capability:string, $menu_slug:string, $function:callable, $position:integer|null ) .
		add_submenu_page( 'anwas_scratch_theme_settings', 'Anwas Scratch Theme Settings', 'General', 'manage_options', 'anwas_scratch_theme_settings', array( $this, 'display_theme_general_settings_menu_page' ), null );

		add_submenu_page( 'anwas_scratch_theme_settings', 'Anwas Scratch Theme Custom CSS Settings', 'Custom CSS', 'manage_options', 'anwas_scratch_theme_custom_css_settings', array( $this, 'display_theme_custom_css_settings_menu_page' ), null );
	}

	/**
	 * Rodomas puslapio turinys aukščiausio lygio
	 * „Anwas Scratch Theme Settings“ meniu punktui.
	 *
	 * Vartotojo teisių tikrinimas atliekamas įterptame faile.
	 *
	 * @return void
	 */
	public function display_theme_general_settings_menu_page(): void {
		get_template_part( 'classes/Admin/Settings/page-templates/general', 'settings', array() );
	}

	/**
	 * Rodomas puslapio turinys „Custom CSS Settings“ submeniu punktui.
	 *
	 * Vartotojo teisių tikrinimas atliekamas įterptame faile.
	 *
	 * @return void
	 */
	public function display_theme_custom_css_settings_menu_page(): void {
		get_template_part( 'classes/Admin/Settings/page-templates/custom-css', 'settings', array() );
	}
}
