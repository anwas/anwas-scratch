<?php
/**
 * Komentarų rodymo šablonas.
 *
 * Tai šablonas, kuris rodo sritį su esamais komentarais ir komentarų forma.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

/*
 * Jei dabartinis įrašas yra apsaugotas slaptažodžiu
 * ir lankytojas dar neįvedė slaptažodžio,
 * stabdome vykdymą anksčiau neįkeldami komentarų.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	if ( have_comments() ) :
		?>
		<h2 class="comments-title">
			<?php
			$anwas_scratch_comment_count = get_comments_number();
			if ( '1' === $anwas_scratch_comment_count ) {
				printf(
					/* translators: 1: title. */
					esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'anwas-scratch' ),
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			} else {
				printf(
					/* translators: 1: comment count number, 2: title. */
					esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $anwas_scratch_comment_count, 'comments title', 'anwas-scratch' ) ),
					number_format_i18n( $anwas_scratch_comment_count ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'<span>' . wp_kses_post( get_the_title() ) . '</span>'
				);
			}
			?>
		</h2><!-- END .comments-title -->

		<?php the_comments_navigation(); ?>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
				)
			);
			?>
		</ol><!-- END .comment-list -->

		<?php
		the_comments_navigation();

		// Jei komentavimas išjungtas, bet yra komentarų, paliekame trumpą žinutę.
		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'anwas-scratch' ); ?></p>
			<?php
		endif;

	endif; // END if have_comments().

	comment_form();
	?>

</div><!-- END #comments .comments-area -->
