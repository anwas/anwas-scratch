<?php
/**
 * Šablono dalis, skirta rodyti puslapio turinį, kai įvyksta 500 klaida.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

?>
<section class="page page--error">
	<header class="page-header page__header">
		<h1 class="page-title page__title">
			<?php esc_html_e( 'Oops! Something went wrong.', 'anwas-scratch' ); ?>
		</h1>
	</header><!-- END .page__header -->

	<div class="page-content page__content">
		<?php
		// Veikia, jei įjungtas AMP įskiepis.
		// @link https://github.com/GoogleChromeLabs/pwa-wp/wiki/Service-Worker#offline--500-error-handling .
		if ( function_exists( 'wp_service_worker_error_message_placeholder' ) ) {
			wp_service_worker_error_message_placeholder();
		}
		if ( function_exists( 'wp_service_worker_error_details_template' ) ) {
			wp_service_worker_error_details_template();
		}
		?>
	</div><!-- END .page__content -->
</section><!-- END .page.page--error -->
