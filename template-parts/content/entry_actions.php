<?php
/**
 * Šablono dalis, skirta rodyti įrašo komentaro sukūrimo ir redaguoti (įrašą) nuorodas.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

?>
<div class="entry-actions entry__actions">
	<?php
	if (
		! is_singular( get_post_type() )
		&& ! post_password_required()
		&& post_type_supports( get_post_type(), 'comments' )
		&& comments_open()
		) {
		?>
		<span class="comments-link">
			<?php
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'anwas-scratch' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			?>
		</span>
		<?php
	}

	edit_post_link(
		sprintf(
			wp_kses(
				/* translators: %s: post title */
				__( 'Edit <span class="screen-reader-text">%s</span>', 'anwas-scratch' ),
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
			get_the_title()
		),
		'<span class="edit-link">',
		' </span>'
	);
	?>
</div><!-- END .entry__actions -->
