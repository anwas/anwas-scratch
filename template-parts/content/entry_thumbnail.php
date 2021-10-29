<?php
/**
 * Šablono dalis, skirta rodyti įrašo pagrindiniui paveikslėliui.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

// Garso ar vaizdo prieduose gali būti rodomų vaizdų, todėl juos reikia specialiai patikrinti.
$support_slug = get_post_type();
if ( 'attachment' === $support_slug ) {
	if ( wp_attachment_is( 'audio' ) ) {
		$support_slug .= ':audio';
	} elseif ( wp_attachment_is( 'video' ) ) {
		$support_slug .= ':video';
	}
}

if ( post_password_required() || ! post_type_supports( $support_slug, 'thumbnail' ) || ! has_post_thumbnail() ) {
	return;
}

if ( is_singular( get_post_type() ) ) {
	?>
	<div class="post-thumbnail">
		<?php the_post_thumbnail( 'anwas-scratch-featured', array( 'class' => 'skip-lazy' ) ); ?>
	</div><!-- END .post-thumbnail -->
	<?php
} else {
	?>
	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
		<?php
		global $wp_query;
		if ( 0 === $wp_query->current_post ) { // $wp_query->current_post talpina informaciją apie esamą įrašą cikle.
			// Pirmam ciklo įrašui nustatome skip-lazy klasę.
			the_post_thumbnail(
				'post-thumbnail',
				array(
					'class' => 'skip-lazy',
					'alt'   => the_title_attribute(
						array(
							'echo' => false,
						)
					),
				)
			);
		} else {
			the_post_thumbnail(
				'post-thumbnail',
				array(
					'alt' => the_title_attribute(
						array(
							'echo' => false,
						)
					),
				)
			);
		}
		?>
	</a><!-- END .post-thumbnail -->
	<?php
}
