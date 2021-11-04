<?php
/**
 * Anwas_Scratch\Setup\Styles klasė.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch\Setup;

// Globalios WordPress funkcijos.
use function \add_action;

// Temos pagalbinių metodų rinkinys.
use function \Anwas_Scratch\anwas_scratch;

/**
 * Klasė, skirta įtraukti stilius.
 */
class Styles {

	/**
	 * Asociatyvus CSS failų masyvas, kaip $handle => $data poros.
	 * $data turi būti masyvas su raktais „file“ (failo kelias, reliatyvus su temos šakniniui katalogui)
	 * arba „url“ (visas URL į failą) ir pasirinktinai „global“ (ar failas turi būti nedelsiant
	 * įtrauktas į eilę, o ne tik registruojamas) ir „enqueue_callback“ (iškvietimo (callback) funkcija,
	 * nustatanti, ar failas turi būti įtrauktas į dabartinės užklausos eilę).
	 *
	 * Tiesiogiai nenaudokite šio kintamojo, naudokite metodą „get_css_files()“.
	 *
	 * @var array
	 */
	private $css_files;

	/**
	 * Asociatyvus įkeliamų „Google“ šriftų masyvas, kaip $font_name => $font_variants poros.
	 *
	 * Tiesiogiai nenaudokite šio kintamojo, naudokite metodą „get_google_fonts()“.
	 *
	 * @var array
	 */
	private $google_fonts;


	/**
	 * Klasės konstruktorius.
	 */
	public function __construct() {}


	/**
	 * Vykdomi add_action ir add_filter veiksmai.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_styles' ) );
		add_action( 'wp_head', array( $this, 'action_preload_styles' ) );
		add_action( 'after_setup_theme', array( $this, 'action_add_editor_styles' ) );
		add_filter( 'wp_resource_hints', array( $this, 'filter_resource_hints' ), 10, 2 );

		add_filter( 'style_loader_tag', array( $this, 'filter_add_subresource_integrity' ), 10, 4 );

		$anwas_scratch = \Anwas_Scratch\Helpers::get_instance();
	}


	/**
	 * Grąžina visus temos naudojamus CSS failus
	 *
	 * @return array Asociatyvus masyvas, kaip $handle => $data poros.
	 */
	public function get_css_files() : array {
		if ( is_array( $this->css_files ) ) {
			return $this->css_files;
		}

		$css_files = array(
			'anwas-scratch-global'     => array(
				'file'   => '/assets/css/global.css',
				'global' => true,
			),
			'anwas-scratch-front-page' => array(
				'file'             => '/assets/css/front-page.css',
				'preload_callback' => function() {
					global $template;
					return ( isset( $template ) ) ? 'front-page.php' === basename( $template ) : false;
				},
			),
		);

		/**
		 * Filtruoja numatytuosius CSS failus.
		 *
		 * @param array $css_files Asociatyvus CSS failų masyvas, kaip $handle => $data poros.
		 *                        $data turi būti masyvas su raktais „file“ (failo kelias, reliatyvus
		 *                        su temos šakniniui katalogui) arba „url“ (visas URL į failą) ir
		 *                        pasirinktinai „global“ (ar failas turi būti nedelsiant įtrauktas į eilę,
		 *                        o ne tik registruojamas) ir „enqueue_callback“ (iškvietimo (callback) funkcija
		 *                        nustatanti, ar failas turi būti įtrauktas į dabartinės užklausos eilę).
		 */
		$css_files = apply_filters( 'anwas_scratch_css_files', $css_files );

		$this->css_files = array();
		foreach ( $css_files as $handle => $data ) {

			if ( empty( $data['file'] ) && empty( $data['url'] ) ) {
				continue;
			}

			$this->css_files[ $handle ] = array_merge(
				array(
					'deps'             => array(),
					'global'           => false,
					'preload_callback' => null,
					'media'            => 'all',
				),
				$data
			);
		}

		return $this->css_files;
	}


	/**
	 * Grąžina temoje naudojamus „Google“ šriftus.
	 *
	 * @return array Asociatyvus $font_name => $font_variants porų masyvas.
	 */
	protected function get_google_fonts() : array {
		if ( is_array( $this->google_fonts ) ) {
			return $this->google_fonts;
		}

		$google_fonts = array(
			'Roboto Condensed' => array( '400', '400i', '700', '700i' ),
			'Crimson Text'     => array( '400', '400i', '600', '600i' ),
		);

		/**
		 * Filtruoja numatytuosius „Google“ šriftus.
		 *
		 * @param array $google_fonts Asociatyvus $font_name => $font_variants porų masyvas.
		 */
		$this->google_fonts = (array) apply_filters( 'anwas_scratch_google_fonts', $google_fonts );

		return $this->google_fonts;
	}


	/**
	 * Registruoja arba įkelia į eiles stilius.
	 *
	 * Stiliai, kurie yra globalūs (pažymėti global), yra įtraukti į eilę.
	 * Visi kiti stiliai yra tik registruoti, kad vėliau būtų įrašyti į eilę.
	 */
	public function action_enqueue_styles() {

		// Įtraukti į eilę „Google“ šriftus.
		$google_fonts_url = $this->get_google_fonts_url();
		if ( ! empty( $google_fonts_url ) ) {
			wp_enqueue_style( 'anwas-scratch-fonts', $google_fonts_url, array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		}

		$preloading_styles_enabled = $this->preloading_styles_enabled();

		$css_files = $this->get_css_files();
		foreach ( $css_files as $handle => $data ) {
			$src     = ( isset( $data['file'] ) ) ? get_parent_theme_file_uri( $data['file'] ) : $data['url'];
			$version = ( isset( $data['file'] ) )
						? anwas_scratch()->get_asset_version( $data['file'] )
						: (
							( isset( $data['version'] ) )
							? $data['version']
							: null
						);

			/*
			 * Nedelsiant įtraukia į eilę globalius stilius, o kitus užregistruoja vėlesniam naudojimui
			 * (nebent stilių išankstinis įkėlimas išjungtas, tokiu atveju stilius reikia nedelsiant
			 * įtraukti į eilę, atsižvelgiant į tai, ar jie reikalingi puslapio turiniui).
			 */
			if ( $data['global'] || ! $preloading_styles_enabled && is_callable( $data['preload_callback'] ) && call_user_func( $data['preload_callback'] ) ) {
				wp_enqueue_style( $handle, $src, $data['deps'], $version, $data['media'] );
			} else {
				wp_register_style( $handle, $src, $data['deps'], $version, $data['media'] );
			}

			wp_style_add_data( $handle, 'precache', true );
		}
	}


	/**
	 * Priklausomai nuo to, kokie šablonai naudojami, iš anksto įkeliami stiliai.
	 *
	 * Bus atsižvelgiama tik į tuos stilius, kuriuose pateikta „preload_callback“.
	 * Jei dabartinės užklausos iškvietimo (callback) vertė yra true, stiliaus failas bus iš anksto įkeltas.
	 *
	 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Preloading_content
	 */
	public function action_preload_styles() {

		// Jei išankstinio įkėlimo stiliai išjungti, stabdome metodo vykdymą čia.
		if ( ! $this->preloading_styles_enabled() ) {
			return;
		}

		$wp_styles = wp_styles();

		$css_files = $this->get_css_files();
		foreach ( $css_files as $handle => $data ) {

			// Praleisti, jei stiliaus failas neužregistruotas.
			if ( ! isset( $wp_styles->registered[ $handle ] ) ) {
				continue;
			}

			// Praleisti, jei nepateikiama išankstinio įkėlimo iškvietimo funkcija callback).
			if ( ! is_callable( $data['preload_callback'] ) ) {
				continue;
			}

			// Praleisti, jei išankstinis įkėlimas šiai užklausai nereikalingas.
			if ( ! call_user_func( $data['preload_callback'] ) ) {
				continue;
			}

			$preload_uri = $wp_styles->registered[ $handle ]->src . '?ver=' . $wp_styles->registered[ $handle ]->ver;

			echo '<link rel="preload" id="' . esc_attr( $handle ) . '-preload" href="' . esc_url( $preload_uri ) . '" as="style">';
			echo "\n";
		}
	}


	/**
	 * Įkelia „WordPress“ temų stilius redaktoriui.
	 */
	public function action_add_editor_styles() {

		// Įtraukia „Google Fonts“.
		$google_fonts_url = $this->get_google_fonts_url();
		if ( ! empty( $google_fonts_url ) ) {
			add_editor_style( $this->get_google_fonts_url() );
		}

		// Įtraukia blokų redaktoriaus stilius.
		add_editor_style( 'assets/css/editor/editor-styles.css' );
	}


	/**
	 * Prideda išankstinio prisijungimo šaltinio užuominą apie „Google Fonts“.
	 *
	 * @param array  $urls          URL adresai, kuriuos reikia spausdinti dėl išteklių patarimų.
	 * @param string $relation_type Ryšio tipas spausdinamam URL.
	 * @return array URL adresai, kuriuos reikia spausdinti dėl išteklių patarimų.
	 */
	public function filter_resource_hints( array $urls, string $relation_type ) : array {
		if ( ! empty( $this->get_google_fonts() ) && 'preconnect' === $relation_type && wp_style_is( 'anwas-scratch-fonts', 'queue' ) ) {
			$urls[] = array(
				'href' => 'https://fonts.gstatic.com',
				'crossorigin',
			);
		}

		return $urls;
	}


	/**
	 * Nustato, ar iš anksto įkelti stilius ir įterpti jų nuorodų žymas tiesiai į puslapio turinį.
	 *
	 * Naudojant šią techniką paprastai pagerėja našumas, tačiau tam tikromis aplinkybėmis tai gali būti nepriimtina.
	 * Pavyzdžiui, kadangi AMP visas stiliaus taisykles įtrauks tiesiai į „head“, jis negali būti naudojamas
	 * tame kontekste. Pagal numatytuosius nustatymus šis metodas grąžina „true“, nebent puslapis teikiamas AMP.
	 * Grąžinamai vertei pakoreguoti galima naudoti filtrą {@see 'anwas_scratch_preloading_styles_enabled'}.
	 *
	 * @return bool „true“, jei įgalintas išankstinis stilių įkėlimas ir įterpimas, kitu atveju – „false“.
	 */
	public function preloading_styles_enabled() {
		// TODO: jei tema naudos AMP, padaryti tikrinimą, ar AMP aktyvus.
		// Šiuo metu nustatyta, kad visada leidžiamas išankstinis įkėlimas.
		$preloading_styles_enabled = true;

		/**
		 * Filtruoja, ar iš anksto įkelti stilius ir įterpti jų nuorodų žymas į puslapio turinį.
		 *
		 * @param bool $preloading_styles_enabled Ar įgalintas išankstinis stilių įkėlimas ir įterpimas.
		 */
		return apply_filters( 'anwas_scratch_preloading_styles_enabled', $preloading_styles_enabled );
	}


	/**
	 * Grąžina „Google Fonts“ URL, kurį naudosite „Google Fonts“ CSS eilėje.
	 *
	 * Pagal numatytuosius nustatymus naudojamas „lotynų kalbos“ poaibis.
	 * Norėdami naudoti kitus poaibius, prie $query_args pridėkite raktą „subset“ ir norimą reikšmę.
	 *
	 * @return string „Google Fonts“ URL arba tuščia eilutė, jei nereikėtų naudoti „Google“ šriftų.
	 */
	protected function get_google_fonts_url() : string {
		$google_fonts = $this->get_google_fonts();

		if ( empty( $google_fonts ) ) {
			return '';
		}

		$font_families = array();

		foreach ( $google_fonts as $font_name => $font_variants ) {
			if ( ! empty( $font_variants ) ) {
				if ( ! is_array( $font_variants ) ) {
					$font_variants = explode( ',', str_replace( ' ', '', $font_variants ) );
				}

				$font_families[] = $font_name . ':' . implode( ',', $font_variants );
				continue;
			}

			$font_families[] = $font_name;
		}

		$query_args = array(
			'family'  => implode( '|', $font_families ),
			'display' => 'swap',
		);

		return add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}


	/**
	 * Prideda SRI atributus pagal apibrėžtas stilių pavadinimus (handles).
	 *
	 * @link https://developer.mozilla.org/en-US/docs/Web/Security/Subresource_Integrity
	 *
	 * @param string $tag Eilėje esančio stiliaus nuorodos („<link>“) žyma.
	 * @param string $handle Stiliaus registruotas pavadinimas (handle).
	 * @param string $href Stilių šaltinio failo URL.
	 * @param string $media Stiliaus medijos atributas.
	 *
	 * @return string
	 */
	public function filter_add_subresource_integrity( string $tag, string $handle, string $href, string $media ): string {
		$css_files  = $this->get_css_files();
		$sri_string = '';

		if ( isset( $css_files[ $handle ]['integrity'] ) ) {
			$sri_string .= ' integrity="' . $css_files[ $handle ]['integrity'] . '"';
		}

		if ( isset( $css_files[ $handle ]['crossorigin'] ) ) {
			$sri_string .= ' crossorigin="' . $css_files[ $handle ]['crossorigin'] . '"';
		}

		if ( ! empty( $sri_string ) ) {
			$search  = ' href=';
			$replace = $sri_string . $search;
			$tag     = str_replace( $search, $replace, $tag );
		}

		return $tag;
	}

}
