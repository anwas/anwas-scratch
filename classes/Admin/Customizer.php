<?php
/**
 * Anwas_Scratch\Admin\Customizer klasė.
 *
 * Skaityti daugiau:
 *
 * @link https://developer.wordpress.org/themes/customize-api/customizer-objects/
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch\Admin;

use \WP_Customize_Manager;
use \WP_Customize_Color_Control;

// Globalios WordPress funkcijos.
use function \add_action;
use function \current_user_can;
use function \get_template_part;
use function \get_parent_theme_file_uri;
use function \get_parent_theme_file_path;
use function \wp_enqueue_script;

/**
 * Temos individualizavimo (tinkinimo) klasė.
 */
class Customizer {

	/**
	 * Vykdomi add_action ir add_filter veiksmai.
	 *
	 * @return void
	 */
	public function register(): void {
		// Jei esamas vartotojas turi tinkamas teises, vykdome kodą.
		if ( current_user_can( 'edit_theme_options' ) ) {
			add_action( 'customize_register', array( $this, 'action_customize_register' ) );
			add_action( 'customize_register', array( $this, 'selective_refresh' ) );
			add_action( 'customize_preview_init', array( $this, 'customize_preview_js' ) );
		}
	}

	/**
	 * Temos tinkintojo sekcijų ir valdiklių registravimas.
	 *
	 * @param WP_Customize_Manager $wp_customize Temos Customizer objektas.
	 *
	 * @return void
	 */
	public function action_customize_register( WP_Customize_Manager $wp_customize ): void {
		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
		$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

		$wp_customize->add_section(
			'anwas_scratch_theme_customize_section',
			array(
				'title'          => __( 'Anwas Scratch', 'anwas-scratch' ),
				'description'    => __( 'Keiskite temos nustatymus čia.', 'anwas-scratch' ),
				'priority'       => 160,
				'capability'     => 'edit_theme_options',
			)
		);

		$this->customize_footer_site_info_text( $wp_customize );
		$this->customize_site_header_background_color( $wp_customize );
	}

	/**
	 * Svetainės antraštės fono spalva.
	 *
	 * @param WP_Customize_Manager $wp_customize Temos Customizer objektas.
	 *
	 * @return void
	 */
	public function customize_site_header_background_color( WP_Customize_Manager $wp_customize ): void {

		$wp_customize->add_setting(
			'anwas_scratch_branding_bg',
			array(
				'type'                 => 'theme_mod', // 'theme_mod' arba 'option'.
				'capability'           => 'edit_theme_options',
				'default'              => '#2c86ba',
				'transport'            => 'refresh', // 'refresh' arba 'postMessage'.
				'sanitize_callback'    => '\sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'branding_bg_color',
				array(
					'section'    => 'colors',
					'settings'   => 'anwas_scratch_branding_bg',
					'label'      => __( 'Svetainės antraštės fono spalva', 'anwas-scratch' ),
				)
			)
		);
	}

	/**
	 * Temos informacijos teksto tinkinimas svetainės informacijos bloke.
	 *
	 * @param WP_Customize_Manager $wp_customize Temos Customizer objektas.
	 *
	 * @return void
	 */
	public function customize_footer_site_info_text( WP_Customize_Manager $wp_customize ): void {

		$wp_customize->add_setting(
			'anwas_scratch_site_info_text',
			array(
				'type'                 => 'theme_mod', // 'theme_mod' arba 'option'.
				'capability'           => 'edit_theme_options',
				'default'              => '',
				'transport'            => 'postMessage', // 'refresh' arba 'postMessage'.
				'sanitize_callback'    => '\wp_kses_post',
			)
		);

		$wp_customize->add_control(
			'anwas_scratch_site_info_text',
			array(
				'type'            => 'textarea',
				'priority'        => 10, // Sekcijos ribose.
				'section'         => 'anwas_scratch_theme_customize_section', // Privalomas, 'core' arba 'custom'.
				'label'           => __( 'Svetainės info tekstas', 'anwas-scratch' ),
				'description'     => __( 'Tekstas rodomas svetainės poraštės srityje.', 'anwas-scratch' ),
			)
		);
	}

	/**
	 * Prideda postMessage palaikymą pasirinktiems nustatymams.
	 *
	 * @param WP_Customize_Manager $wp_customize Temos Customizer objektas.
	 *
	 * @return void
	 */
	public function selective_refresh( WP_Customize_Manager $wp_customize ): void {

		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial(
				'blogname',
				array(
					'selector'        => '.site-title a',
					'render_callback' => function() {
						bloginfo( 'name' );
					},
				)
			);
			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				array(
					'selector'        => '.site-description',
					'render_callback' => function() {
						bloginfo( 'description' );
					},
				)
			);

			$wp_customize->selective_refresh->add_partial(
				'anwas_scratch_site_footer',
				array(
					'settings'            => array( 'anwas_scratch_site_info_text' ),
					'selector'            => '.site-info',
					'container_inclusive' => true, // Ar konteinerio elementas įtrauktas į dalinį elementą, ar pateikiamas tik turinys.
					'render_callback'     => function() {
						get_template_part( '/template-parts/footer/site-info' );
					},
				)
			);
		}
	}

	/**
	 * Prijungia JS, kad temos tinkinimo priemonės peržiūroje iš naujo įkeltų pakeitimus asinchroniškai.
	 */
	public function customize_preview_js() {
		wp_enqueue_script(
			'anwas-scratch-customizer',
			get_parent_theme_file_uri( '/assets/js/customizer.js' ),
			array( 'customize-preview' ),
			filemtime( get_parent_theme_file_path( '/assets/js/customizer.js' ) ),
			true
		);
	}

}
