<?php
/**
 * Anwas_Scratch\Setup\Post_Thumbnails klasė.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch\Setup;

// Globalios WordPress funkcijos.
use function \add_action;
use function \add_theme_support;
use function \set_post_thumbnail_size;
use function \add_image_size;

/**
 * Klasė, skirta pridėti pagrindinio įrašo paveikslėlio palaikymą ir pridėti kitus paveikslėlių dydžius.
 */
class Post_Thumbnails {
	const POST_THUMBNAIL_WIDTH  = 1920;
	const POST_THUMBNAIL_HEIGHT = 9999;
	const POST_THUMBNAIL_CROP   = false;

	/**
	 * Pasirinktinių paveikslėlių dydžių masyvas.
	 *
	 * @var array|null
	 */
	private $custom_image_sizes = null;

	/**
	 * Vykdomi add_action ir add_filter veiksmai.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'after_setup_theme', array( $this, 'action_add_post_thumbnail_support' ) );
		add_action( 'after_setup_theme', array( $this, 'action_set_post_thumbnail_size' ) );
		add_action( 'after_setup_theme', array( $this, 'action_add_image_sizes' ) );
	}

	/**
	 * Paveikslių dydžių nustatymo masyvas.
	 *
	 * @return array
	 */
	public function get_image_sizes(): array {
		if ( is_array( $this->custom_image_sizes ) ) {
			return $this->custom_image_sizes;
		}

		$this->custom_image_sizes = array(
			'anwas-scratch-featured' => array(
				'width'  => static::POST_THUMBNAIL_WIDTH,
				'height' => static::POST_THUMBNAIL_HEIGHT,
				'crop'   => static::POST_THUMBNAIL_CROP,
			),
		);

		return $this->custom_image_sizes;
	}


	/**
	 * Įgalinkite įrašų pagrindinio paveikslėlio (post thumbnails) palaikymą įrašuose ir puslapiuose.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 *
	 * @return void
	 */
	public function action_add_post_thumbnail_support(): void {
		add_theme_support( 'post-thumbnails' );
	}

	/**
	 * Užregistruojamas įrašo pagrindinio paveikslėlio vaizdo dydį.
	 *
	 * @return void
	 */
	public function action_set_post_thumbnail_size(): void {
		set_post_thumbnail_size( static::POST_THUMBNAIL_WIDTH, static::POST_THUMBNAIL_HEIGHT, static::POST_THUMBNAIL_CROP );
	}

	/**
	 * Pridedmi tinkinti (papildomi) paveiklėlių dydžiai.
	 *
	 * @return void
	 */
	public function action_add_image_sizes(): void {
		$image_sizes = $this->get_image_sizes();

		foreach ( $image_sizes as $img_name => $img_args ) {
			add_image_size( $img_name, $img_args['width'], $img_args['height'], $img_args['crop'] );
		}
	}

}
