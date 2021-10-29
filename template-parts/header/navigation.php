<?php
/**
 * Šablono dalis rodyti navigacijos meniu temos antraštės faile (nors galima ir kitur).
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

use \Anwas_Scratch\Nav_Menus\Menus as Anwas_Scratch_Menus;
use \Anwas_Scratch\Nav_Menus\Nav_Walker as Anwas_Scratch_Nav_Walker;

if ( ! Anwas_Scratch_Menus::is_primary_nav_menu_active() ) {
	return;
}
?>

<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Main menu', 'anwas-scratch' ); ?>">

	<button class="menu-toggle" aria-label="<?php esc_attr_e( 'Open menu', 'anwas-scratch' ); ?>" aria-controls="primary-menu" aria-expanded="false">
		<?php esc_html_e( 'Menu', 'anwas-scratch' ); ?>
	</button>

	<?php
	Anwas_Scratch_Menus::display_primary_nav_menu(
		array(
			'container'       => 'div',
			'container_class' => 'menu primary-menu-container',
			'menu_class'      => 'nav-menu',
			'menu_id'         => 'primary-menu',
			'walker'          => new Anwas_Scratch_Nav_Walker(),
		)
	);
	?>
</nav><!-- END #site-navigation .main-navigation -->
