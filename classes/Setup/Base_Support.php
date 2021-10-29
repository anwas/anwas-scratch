<?php
/**
 * Anwas_Scratch\Setup\Base_Support klasė.
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
use function \remove_action;
use function \load_theme_textdomain;
use function \add_theme_support;
use function \add_post_type_support;
use function \get_parent_theme_file_path;
use function \is_singular;

/**
 * Klasė, skirta pridėti pagrindinį temų palaikymą, dauguma kurių įprasta įgyvendinti visose temose.
 */
class Base_Support {
	/**
	 * Maksimalus turinio plotis.
	 *
	 * @var integer
	 */
	private int $content_width = 1440;

	/**
	 * Maksimalus įterpinių (embed) plotis.
	 *
	 * @var integer
	 */
	private int $embeds_width = 1440;

	/**
	 * Vykdomi add_action ir add_filter veiksmai.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'after_setup_theme', array( $this, 'action_setup_localization' ) );
		add_action( 'after_setup_theme', array( $this, 'action_add_essential_theme_support' ) );

		// 0 prioritetas, kad jis būtų pasiekiamas mažesnio prioriteto atgaliniams iškvietimams (callbacks).
		add_action( 'after_setup_theme', array( $this, 'action_set_content_width' ), 0 );

		add_filter( 'embed_defaults', array( $this, 'filter_embed_dimensions' ) );
		add_filter( 'body_class', array( $this, 'filter_body_classes_add_hfeed' ) );
		add_filter( 'theme_scandir_exclusions', array( $this, 'filter_scandir_exclusions_for_optional_templates' ) );

		// Išjungiamos emoji.
		add_action( 'init', array( $this, 'action_disable_emojis' ) );

		// Optimizavimai.
		$this->optimizations();
	}


	/**
	 * Aktyvuojamas temos daugiakalbiškumo palaikymas.
	 *
	 * Vertimo failus talpinti į temos languages aplanką.
	 *
	 * @return void
	 */
	public function action_setup_localization(): void {
		load_theme_textdomain( 'anwas-scratch', get_parent_theme_file_path( '/languages' ) );
	}


	/**
	 * Temos funkcionalumų pridėjimas (Temos palaikymo žymos).
	 *
	 * @return void
	 */
	public function action_add_essential_theme_support(): void {

		// Prideda numatytuosias įrašų ir komentarų RSS kanalo nuorodas į HTML head bloką.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Leiskite „WordPress“ tvarkyti dokumento pavadinimą.
		 * Pridėdami temos palaikymą pareiškiame, kad šioje temoje dokumento antraštėje
		 * nenaudojama tiesiogiai įrašyta (hard-coded) žyma <title>, ir tikimės,
		 * kad „WordPress“ ją pateiks.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Perjungiame numatytąjį pagrindinį paieškos formos, komentarų formos, komentarų,
		 * galerijos ir antraščių žymėjimą, kad būtų pateiktas validus HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Pridedamas palaikymas Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Pridedamas palaikymas viso (full) ir plataus (wide) pločio paveikslėlių lygiavimui.
		add_theme_support( 'align-wide' );
		add_theme_support( 'core-block-patterns' );
		add_theme_support( 'custom-line-height' );
		add_theme_support( 'custom-spacing' );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Pridedamas palaikymas prisitaikančių įterpinių (esponsive embedded) turiniui.
		add_theme_support( 'responsive-embeds' );

		/**
		 * Įgalinkite šių įrašų formatų palaikymą:
		 * aside, gallery, quote, image, and video
		 * Jei reikia, atkomentuokite.
		 */
		// phpcs:ignore
		// add_theme_support( 'post-formats', ['aside', 'gallery', 'quote', 'image', 'video'] );

		// Pridedame ištraukos lauką prie puslapio įrašo tipo.
		add_post_type_support( 'page', 'excerpt' );
	}

	/**
	 * Prideda „hfeed“ klasę prie teksto klasių masyvo, skirto puslapiams, kurie yra archyvas ar pan. (non-singular).
	 *
	 * @param array $classes „body“ elemento klasės.
	 * @return array Filtruotos „body“ elemento klasės.
	 */
	public function filter_body_classes_add_hfeed( array $classes ) : array {
		if ( ! is_singular() ) {
			$classes[] = 'hfeed';
		}

		return $classes;
	}

	/**
	 * Nustatomas įterpinio (embed) plotis pikseliais, atsižvelgiant į temos dizainą ir stilius.
	 *
	 * @param array $dimensions Įterpinio (embed) pločio ir aukščio verčių masyvas pikseliais (šia tvarka).
	 * @return array Filtruotų matmenų masyvas.
	 */
	public function filter_embed_dimensions( array $dimensions ) : array {
		$dimensions['width'] = $this->embeds_width;
		return $dimensions;
	}

	/**
	 * Nustato maksimalų turinio plotį, kad WordPress galėtų tinkamai pritaikyti vaizdų dydį.
	 *
	 * @return void
	 */
	public function action_set_content_width(): void {
		$GLOBALS['content_width'] = apply_filters( 'anwas_scratch_content_width', $this->content_width );
	}

	/**
	 * Neįtraukiamas joks katalogas, pavadintas „optional“, kad nebūtų nuskaityti temos šablonų failai.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/theme_scandir_exclusions/
	 *
	 * @param array $exclusions Numatytieji katalogai, kuriuos reikia praleisti.
	 * @return array Filtruoti praleidžiami pavadinimai.
	 */
	public function filter_scandir_exclusions_for_optional_templates( array $exclusions ) : array {
		return array_merge(
			$exclusions,
			array( 'optional' )
		);
	}

	/**
	 * Išjungiami emoji.
	 *
	 * @return void
	 */
	public function action_disable_emojis(): void {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	}

	/**
	 * Optimizavimai.
	 *
	 * @return void
	 */
	public function optimizations(): void {
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'rsd_link' ); // pašalinti tikrai paprasto atradimo (RSD) nuorodą.
		remove_action( 'wp_head', 'wlwmanifest_link' ); // pašalinti wlwmanifest.xml (reikalingas Windows Live rašytojų palaikymui).
		remove_action( 'wp_head', 'feed_links', 2 ); // pašalinti RSS kanalo nuorodas (jei nenaudojate rss).
		remove_action( 'wp_head', 'feed_links_extra', 3 ); // pašalina visas papildomas RSS kanalo nuorodas.
		remove_action( 'wp_head', 'index_rel_link' ); // pašalinti nuorodą į index puslapį.
		remove_action( 'wp_head', 'start_post_rel_link', 10 ); // pašalinti atsitiktinio įrašo nuorodą.
		remove_action( 'wp_head', 'parent_post_rel_link', 10 ); // pašalinti tėvinio įrašo nuorodą.
		remove_action( 'wp_head', 'adjacent_posts_rel_link', 10 ); // pašalinti kito ir ankstesnio įrašo nuorodas.
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 ); // pašalinti trumpąją nuorodą.
	}

}
