<?php
/**
 * Šablono dalis, skirta įrašo taksonomijos terminams rodyti.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

$taxonomies = wp_list_filter(
	get_object_taxonomies( $post, 'objects' ),
	array(
		'public' => true,
	)
);

?>
<div class="entry__taxonomies">
	<?php
	$taxonomies_counter = 0;
	// Rodyti visų su įrašu susijusių taksonomijų terminus.
	foreach ( $taxonomies as $taxonomy ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		$taxonomies_counter++;

		/* translators: separator between taxonomy terms */
		$separator = _x( ', ', 'list item separator', 'anwas-scratch' );

		switch ( $taxonomy->name ) {
			case 'category':
				$class = 'term-links entry__term-links entry__term-links--category';
				$list  = get_the_category_list( esc_html( $separator ), '', $post->ID );
				/* translators: %s: list of taxonomy terms */
				$placeholder_text = __( 'Posted in %s', 'anwas-scratch' );
				break;
			case 'post_tag':
				$class = 'term-links entry__term-links entry__term-links--tag';
				$list  = get_the_tag_list( '', esc_html( $separator ), '', $post->ID );
				/* translators: %s: list of taxonomy terms */
				$placeholder_text = __( 'Tagged %s', 'anwas-scratch' );
				break;
			default:
				$class            = 'term-links entry__term-links entry__term-links--' . str_replace( '_', '-', $taxonomy->name );
				$list             = get_the_term_list( $post->ID, $taxonomy->name, '', esc_html( $separator ), '' );
				$placeholder_text = sprintf(
					'%s: %s',
					$taxonomy->labels->name, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'%s'
				);
		}

		if ( empty( $list ) ) {
			continue;
		}
		?>
		<span class="<?php echo esc_attr( $class ); ?>">
			<?php
			printf(
				esc_html( $placeholder_text ),
				$list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			?>
		</span>
		<?php
		if ( count( $taxonomies ) !== $taxonomies_counter ) {
			?>
			<br />
			<?php
		}
	}
	?>
</div><!-- END .entry__taxonomies -->
