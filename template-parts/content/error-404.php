<?php
/**
 * Šablono dalis, skirta rodyti puslapio turinį, kai įvyksta 400 klaida.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

?>
<section class="page page--error">
	<?php get_template_part( 'template-parts/content/page_header' ); ?>

	<div class="page__content">
		<p>
			<?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'anwas-scratch' ); ?>
		</p>

		<?php
		get_search_form();

		the_widget( 'WP_Widget_Recent_Posts' );
		?>

		<div class="widget widget--categories">
			<h2 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'anwas-scratch' ); ?></h2>
			<ul>
			<?php
			wp_list_categories(
				array(
					'orderby'    => 'count',
					'order'      => 'DESC',
					'show_count' => 1,
					'title_li'   => '',
					'number'     => 10,
				)
			);
			?>
			</ul>
		</div><!-- END .widget.widget--categories -->

		<?php
		/* translators: %1$s: smiley */
		$archive_content = '<p>' . sprintf( esc_html__( 'Try looking in the monthly archives. %1$s', 'anwas-scratch' ), convert_smilies( ':)' ) ) . '</p>';
		the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>{$archive_content}" );

		the_widget( 'WP_Widget_Tag_Cloud' );
		?>
	</div><!-- END .page__content -->
</section><!-- END .page.page--error -->
