<?php
/**
 * Šablono dalis, skirta įrašo turiniui – straipsniui (entry).
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
	get_template_part( '/template-parts/content/entry_header', get_post_type() );

	if ( is_singular() ) {
		get_template_part( '/template-parts/content/entry_content', get_post_type() );
	} else {
		get_template_part( '/template-parts/content/entry_summary', get_post_type() );
	}

	get_template_part( '/template-parts/content/entry_footer', get_post_type() );
	?>
</article>

<?php
if ( is_singular( get_post_type() ) ) {
	// Rodyti įrašo navigaciją tik tada, kai įrašo tipas yra post arba įrašo tipas turi archyvą.
	if ( 'post' === get_post_type() || get_post_type_object( get_post_type() )->has_archive ) {
		the_post_navigation(
			array(
				'prev_text' => '<div class="nav-links__sub"><span>' . esc_html__( 'Previous:', 'anwas-scratch' ) . '</span></div>%title',
				'next_text' => '<div class="nav-links__sub"><span>' . esc_html__( 'Next:', 'anwas-scratch' ) . '</span></div>%title',
			)
		);
	}

	// Rodyti komentarus tik tada, kai įrašo tipas jį palaiko ir kai komentarai yra atviri arba yra bent vienas komentaras.
	if ( post_type_supports( get_post_type(), 'comments' ) && ( comments_open() || get_comments_number() ) ) {
		comments_template();
	}
}
