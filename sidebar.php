<?php
/**
 * Temos valdiklių srities šablono failas.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

use \Anwas_Scratch\Setup\Sidebars as Anwas_Scratch_Sidebars;

if ( Anwas_Scratch_Sidebars::is_primary_sidebar_active() ) {
	?>
	<aside id="secondary" class="widgets-area sidebar-primary">
		<h2 class="screen-reader-text"><?php esc_attr_e( 'Sidebar Primary', 'anwas-scratch' ); ?></h2>
		<?php
		Anwas_Scratch_Sidebars::display_primary_sidebar();
		?>
	</aside>
	<?php
}


if ( Anwas_Scratch_Sidebars::is_secondary_sidebar_active() ) {
	?>
	<aside id="tertiary" class="widgets-area sidebar-secondary">
		<h2 class="screen-reader-text"><?php esc_attr_e( 'Sidebar Secondary', 'anwas-scratch' ); ?></h2>
		<?php
		Anwas_Scratch_Sidebars::display_secondary_sidebar();
		?>
	</aside>
	<?php
}
