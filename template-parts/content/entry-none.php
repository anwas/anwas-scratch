<?php
/**
 * Šablono dalis, skirta rodyti informaciją, kai įrašų sąrašas yra tuščias.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

?>
<section class="page page--no-results page--not-found">
	<header class="page__header">
		<h1 class="page__title"><?php esc_html_e( 'Nieko nerasta', 'anwas-scratch' ); ?></h1>
	</header><!-- END .page-header -->

	<div class="page__content">
		<p><?php esc_html_e( 'Čia nieko nerasta.', 'anwas-scratch' ); ?></p>
	</div><!-- END .page__content -->
</section><!-- END .page.page--no-results.page--not-found -->
