<?php
/**
 * Šablono dalis, skirta rodyti svetainės informaciją temos poraštės faile.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

use function \get_theme_mod;

$site_info_text = get_theme_mod( 'anwas_scratch_site_info_text', '' ); // Šis tekstas nustatomas per temos Tinkinimo priemonę (Customizer).
?>
<div class="site-info">
	<?php
	echo wp_kses_post( wpautop( $site_info_text ) );

	if ( function_exists( 'the_privacy_policy_link' ) ) {
		the_privacy_policy_link( '<div class="privacy-info">', '</div>' );
	}
	?>
</div> <!-- END .site-info -->
