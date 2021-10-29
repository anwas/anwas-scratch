<?php
/**
 * Temos nustatymų puslapio dalies šablonas „Custom CSS Settings“ submeniu puslapyje.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch\Admin;

use function \current_user_can;
use function \esc_html_e;
use function \esc_html;
use function \get_admin_page_title;

// Tikriname ar esamas vartotojas turi tinkamas teises.
if ( ! current_user_can( 'manage_options' ) ) {
	?>
	<p><?php esc_html_e( 'You don\'t have permision to view this page', 'anwas-scratch' ); ?></p>
	<?php
	return;
}
?>

<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
</div>
