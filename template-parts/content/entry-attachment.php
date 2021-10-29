<?php
/**
 * Šablono dalis, skirta rodyti 'attachment' įrašų tipo įrašą.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
	<?php
	get_template_part( 'template-parts/content/entry_header', get_post_type() );

	get_template_part( 'template-parts/content/entry_content', get_post_type() );

	get_template_part( 'template-parts/content/entry_footer', get_post_type() );
	?>
</article>

<?php
if ( is_singular( get_post_type() ) ) {
	// Rodyti priedo (failo) navigaciją, jei šis priedas turi tėvinį įrašą.
	if ( ! empty( $post->post_parent ) ) {

		// TODO: Tam turėtų būti pagrindinė „WordPress“ funkcija, panaši į „the_post_navigation()“.
		$attachment_navigation = '';

		ob_start();
		previous_image_link( false );
		$prev_link = ob_get_clean();
		if ( ! empty( $prev_link ) ) {
			$attachment_navigation .= '<div class="nav-previous">';
			$attachment_navigation .= '<div class="post-navigation-sub"><span>' . esc_html__( 'Previous:', 'anwas-scratch' ) . '</span></div>';
			$attachment_navigation .= $prev_link;
			$attachment_navigation .= '</div>';
		}

		ob_start();
		next_image_link( false );
		$next_link = ob_get_clean();
		if ( ! empty( $next_link ) ) {
			$attachment_navigation .= '<div class="nav-next">';
			$attachment_navigation .= '<div class="post-navigation-sub"><span>' . esc_html__( 'Next:', 'anwas-scratch' ) . '</span></div>';
			$attachment_navigation .= $next_link;
			$attachment_navigation .= '</div>';
		}

		if ( ! empty( $attachment_navigation ) ) {
			echo _navigation_markup( $attachment_navigation, $class = 'post-navigation', __( 'Post navigation', 'anwas-scratch' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	// Rodyti komentarus tik tada, kai įrašo tipas jį palaiko ir kai komentarai yra atviri arba yra bent vienas komentaras.
	if ( post_type_supports( get_post_type(), 'comments' ) && ( comments_open() || get_comments_number() ) ) {
		comments_template();
	}
}
