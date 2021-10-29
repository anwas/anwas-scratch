<?php
/**
 * Pagrindinis temos poraštės failas.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

use \Anwas_Scratch\Setup\Sidebars as Anwas_Scratch_Sidebars;

use function \get_theme_mod;
use function \get_template_part;
use function \wp_footer;

?>
	</div> <!-- END #content .site-content --> <?php // Žyma atidaryta header.php faile. ?>

	<footer class="site-footer" id="colophon">
		<?php
		if ( Anwas_Scratch_Sidebars::is_footer_sidebar_active() ) {
			?>
			<asides id="footer-widgets" class="widgets-area widgets-area--footer">
				<h2 class="screen-reader-text"><?php esc_attr_e( 'Asides Footer', 'anwas-scratch' ); ?></h2>
				<?php
				Anwas_Scratch_Sidebars::display_footer_sidebar();
				?>
			</asides>
			<?php
		}

		if ( ! empty( get_theme_mod( 'anwas_scratch_site_info_text', '' ) ) ) {
			get_template_part( '/template-parts/footer/site-info' );
		}
		?>
	</footer>
</div> <!-- END #page .site -->

<?php wp_footer(); ?>
</body>
</html>
