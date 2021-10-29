<?php
/**
 * Šablono dalis, skirta rodyti puslapio turinį, kai įvyksta neprisijungus (offline) klaida.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

?>
<section class="page page--error">
	<header class="page__header">
		<h1 class="page__title">
			<?php esc_html_e( 'Oops! It looks like you&#8217;re offline.', 'anwas-scratch' ); ?>
		</h1>
	</header><!-- END .page__header -->

	<div class="page__content">
		<?php
		// Veikia, jei įjungtas AMP įskiepis.
		// @link https://github.com/GoogleChromeLabs/pwa-wp/wiki/Service-Worker#offline--500-error-handling .
		if ( function_exists( 'wp_service_worker_error_message_placeholder' ) ) {
			wp_service_worker_error_message_placeholder();
		}
		?>
	</div><!-- END .page__content -->
</section><!-- END .page.page--error -->
