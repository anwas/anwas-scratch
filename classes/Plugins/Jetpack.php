<?php
/**
 * Anwas_Scratch\Plugins\Jetpack klasė.
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
 * Klasė, skirta pridėti Jetpack įskiepio palaikymą.
 */
class Jetpack {
	/**
	 * Vykdomi add_action ir add_filter veiksmai.
	 *
	 * @return void
	 */
	public function register(): void {
		if ( defined( 'JETPACK__VERSION' ) ) {
			add_action( 'after_setup_theme', array( $this, 'action_add_jetpack_support' ) );
		}
	}


	/**
	 * Pridėti Jetpack įskiepio palaikymą.
	 *
	 * See: https://jetpack.com/support/infinite-scroll/
	 * See: https://jetpack.com/support/responsive-videos/
	 * See: https://jetpack.com/support/content-options/
	 *
	 * @return void
	 */
	public function action_add_jetpack_support(): void {
		// Prideda temos palaikymą „Infinite Scroll“.
		add_theme_support(
			'infinite-scroll',
			array(
				'container' => 'primary',
				'footer'    => 'page',
				'render'    => function() {
					while ( have_posts() ) {
						the_post();
						if ( is_search() ) {
							get_template_part( 'template-parts/content/entry', 'search' );
						} else {
							get_template_part( 'template-parts/content/entry', get_post_type() );
						}
					}
				},
			)
		);

		// Prideda temos palaikymą prisitaikantiems vaizdo įrašams (Responsive Videos).
		add_theme_support( 'jetpack-responsive-videos' );

		// Prideda temos palaikymą turinio nuostatoms (Content Options).
		add_theme_support(
			'jetpack-content-options',
			array(
				'post-details' => array(
					'stylesheet' => 'anwas-scratch-content',
					'date'       => '.posted-on',
					'categories' => '.category-links',
					'tags'       => '.tag-links',
					'author'     => '.posted-by',
					'comment'    => '.comments-link',
				),
			)
		);
	}

}
