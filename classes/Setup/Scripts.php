<?php
/**
 * Anwas_Scratch\Setup\Scripts klasė.
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
use function \get_parent_theme_file_uri;
use function \wp_enqueue_script;
use function \wp_register_script;
use function \wp_script_add_data;
use function \wp_localize_script;
use function \wp_scripts;

// Temos pagalbinių metodų rinkinys.
use function \Anwas_Scratch\anwas_scratch;

/**
 * Klasė, skirta įtraukti JavaScript scenarijų failams, kurie nėra įtraukti kitose klasėse (moduliuose).
 */
class Scripts {

	/**
	 * Asociatyvus JavaScript failų masyvas, kaip $handle => $data poros.
	 * $data turi būti masyvas su raktais „file“ (failo kelias, reliatyvus su temos šakniniui katalogui)
	 * arba „url“ (visas URL į failą) ir pasirinktinai „global“ (ar failas turi būti nedelsiant
	 * įtrauktas į eilę, o ne tik registruojamas) ir „enqueue_callback“ (iškvietimo (callback) funkcija,
	 * nustatanti, ar failas turi būti įtrauktas į dabartinės užklausos eilę).
	 *
	 * Tiesiogiai nenaudokite šio kintamojo, naudokite metodą „get_js_files()“.
	 *
	 * @var array
	 */
	private $js_files;

	/**
	 * Vykdomi add_action ir add_filter veiksmai.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_scripts' ) );
		add_action( 'wp_print_footer_scripts', array( $this, 'action_print_skip_link_focus_fix' ) );

		add_filter( 'script_loader_tag', array( $this, 'filter_script_loader_tag' ), 10, 2 );
		add_filter( 'script_loader_tag', array( $this, 'action_add_subresource_integrity' ), 10, 3 );
	}


	/**
	 * JavaScript failų, kurie neįtraukti kitose klasėse (moduliuose), masyvas.
	 *
	 * @return array
	 */
	private function get_js_files(): array {
		if ( is_array( $this->js_files ) ) {
			return $this->js_files;
		}

		$js_files = array(
			'anwas-scratch-main' => array(
				'file'             => '/assets/js/main.js',
				'enqueue_callback' => function() {
					return is_front_page();
				},
				'localize'         => array(
					'obj_str' => 'anwas_scratch_main_object',
					'data'    => array(
						'expand'   => __( 'Expand child menu', 'anwas-scratch' ),
						'collapse' => __( 'Collapse child menu', 'anwas-scratch' ),
					),
				),
			),
			'anwas-bavigation' => array(
				'file'        => '/assets/js/navigation.js',
				'in_footer'   => false,
				'defer'       => true,
			),
		);

		/**
		 * Filters default JavaScript files.
		 *
		 * @param array $js_files Asociatyvus JavaScript failų masyvas, kaip $handle => $data poros.
		 *                        $data turi būti masyvas su raktais „file“ (failo kelias, reliatyvus
		 *                        su temos šakniniui katalogui) arba „url“ (visas URL į failą) ir
		 *                        pasirinktinai „global“ (ar failas turi būti nedelsiant įtrauktas į eilę,
		 *                        o ne tik registruojamas) ir „enqueue_callback“ (iškvietimo (callback) funkcija
		 *                        nustatanti, ar failas turi būti įtrauktas į dabartinės užklausos eilę).
		 */
		$js_files = apply_filters( 'anwas_scratch_js_files', $js_files );

		$this->js_files = array();

		foreach ( $js_files as $handle => $data ) {

			if ( empty( $data['file'] ) && empty( $data['url'] ) ) {
				continue;
			}

			$this->js_files[ $handle ] = array_merge(
				array(
					'global'           => false,
					'enqueue_callback' => '__return_true',
					'deps'             => array(),
					'in_footer'        => true,
					'async'            => false,
					'defer'            => false,
				),
				$data
			);
		}

		return $this->js_files;
	}


	/**
	 * Įtraukia JavaScript failus.
	 *
	 * @return void
	 */
	public function action_enqueue_scripts(): void {
		$js_files = $this->get_js_files();

		foreach ( $js_files as $handle => $script_data ) {

			$src     = ( isset( $script_data['file'] ) ) ? get_parent_theme_file_uri( $script_data['file'] ) : $script_data['url'];
			$version = ( isset( $script_data['file'] ) )
						? anwas_scratch()->get_asset_version( $script_data['file'] )
						: (
							( isset( $script_data['version'] ) )
							? $script_data['version']
							: null
						);

			/*
			 * Nedelsdami įtraukite visuotinius scenarijus į eilę, o kitus užregistruokite
			 * vėlesniam naudojimui (nebent enqueue_callback nenustatytas arba yra null,
			 * tokiu atveju scenarijus reikia nedelsiant įtraukti į eilę, atsižvelgiant į tai,
			 * ar jie reikalingi puslapio turiniui).
			 */
			if ( $script_data['global'] || ( is_callable( $script_data['enqueue_callback'] ) && call_user_func( $script_data['enqueue_callback'] ) ) ) {
				wp_enqueue_script( $handle, $src, $script_data['deps'], $version, $script_data['in_footer'] );
			} else {
				wp_register_script( $handle, $src, $script_data['deps'], $version, $script_data['in_footer'] );
			}

			if ( true === $script_data['defer'] ) {
				wp_script_add_data( $handle, 'defer', true );
			} elseif ( true === $script_data['async'] ) {
				wp_script_add_data( $handle, 'async', true );
				wp_script_add_data( $handle, 'precache', true );
			}

			if ( isset( $script_data['localize'] ) && is_array( $script_data['localize'] ) ) {
				wp_localize_script(
					$handle,
					$script_data['localize']['obj_str'],
					$script_data['localize']['data']
				);
			}
		}
	}


	/**
	 * Spausdina inline scenarijų, kad ištaisytų praleidžiamos nuorodos fokusavimą IE11.
	 *
	 * Scenarijus nėra įtrauktas į eilę, nes jis yra mažas ir skirtas tik IE11.
	 *
	 * Kadangi jo niekada nereikės keisti, jis tiesiog išspausdinamas minimizuota versija.
	 *
	 * @link https://git.io/vWdr2
	 */
	public function action_print_skip_link_focus_fix() {
		// Spausdina minimizuotą scenarijų.
		?>
		<script>
		/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
		</script>
		<?php
	}


	/**
	 * Prideda async / defer atributus į eilės / registruotus scenarijus.
	 *
	 * Jei #12009 pateks į „WordPress“, ši funkcija gali neveikti, nes ji bus tvarkoma WordPress.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12009
	 *
	 * @param string $tag    „script“ žyma.
	 * @param string $handle Scenarijaus pavadinimas (handle).
	 * @return string Script HTML eilutė.
	 */
	public function filter_script_loader_tag( string $tag, string $handle ) : string {

		foreach ( array( 'async', 'defer' ) as $attr ) {
			if ( ! wp_scripts()->get_data( $handle, $attr ) ) {
				continue;
			}

			// Neleisti pridėti atributo, kai jau bus įtraukta į #12009.
			if ( ! preg_match( ":\s$attr(=|>|\s):", $tag ) ) {
				$tag = preg_replace( ':(?=></script>):', " $attr", $tag, 1 );
			}

			// Leisti tik async arba defer, o ne abu.
			break;
		}

		return $tag;
	}


	/**
	 * Pridėkite SRI atributus pagal apibrėžtus scenarijaus pavadinimus (handles).
	 *
	 * @link https://developer.mozilla.org/en-US/docs/Web/Security/Subresource_Integrity
	 *
	 * @param string $tag Eilėje esančio scenarijaus žyma „<script>“.
	 * @param string $handle Scenarijaus registruotas pavadinimas (handle).
	 * @param string $src Scenarijaus šaltinio URL.
	 *
	 * @return string
	 */
	public function action_add_subresource_integrity( string $tag, string $handle, string $src ): string {
		$js_files   = $this->get_js_files();
		$sri_string = '';

		if ( isset( $js_files[ $handle ]['integrity'] ) ) {
			$sri_string .= ' integrity="' . $js_files[ $handle ]['integrity'] . '"';
		}
		if ( isset( $js_files[ $handle ]['crossorigin'] ) ) {
			$sri_string .= ' crossorigin="' . $js_files[ $handle ]['crossorigin'] . '"';
		}

		if ( ! empty( $sri_string ) ) {
			$search  = '></script>';
			$replace = $sri_string . $search;
			$tag     = str_replace( $search, $replace, $tag );
		}

		return $tag;
	}

}
