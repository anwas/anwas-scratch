<?php
/**
 * Anwas_Scratch\Plugins\Woocommerce klasė.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch\Plugins;

// Globalios WordPress funkcijos.
use function \add_action;
use function \apply_filters;
use function \add_theme_support;
use function \get_option;

/**
 * Klasė, skirta pridėti Woocommerce įskiepio palaikymą.
 */
class Woocommerce {
	/**
	 * Vykdomi add_action ir add_filter veiksmai.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'after_setup_theme', array( $this, 'action_add_woocommerce_support' ) );
	}


	/**
	 * Pridėti Woocommerce įskiepio palaikymą.
	 *
	 * @return void
	 */
	public function action_add_woocommerce_support(): void {
		// Prideda temos palaikymą Woocommerce įskiepiui.
		if (
			in_array(
				'woocommerce/woocommerce.php',
				apply_filters(
					'active_plugins',
					get_option( 'active_plugins' )
				),
				true
			)
		) {
			add_theme_support( 'woocommerce' );
		}
	}

}
