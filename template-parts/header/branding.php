<?php
/**
 * Šablono dalis, skirta antraštės prekės ženklui rodyti.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

use \Anwas_Scratch\Setup\Custom_Logo as Anwas_Scratch_Custom_Logo;

$branding_bg               = get_theme_mod( 'anwas_scratch_branding_bg', '#2c86ba' );
$anwas_scratch_description = get_bloginfo( 'description', 'display' );
$site_branding_classes     = 'site-branding';

if ( $anwas_scratch_description ) {
	$site_branding_classes .= ' has-site-description';
}
?>
<style>
	.site-branding {
		background-color: <?php echo esc_attr( $branding_bg ); ?>;
		<?php
		if ( has_header_image() && 'blank' !== get_header_textcolor() ) {
			$site_branding_classes .= ' header-background-image';
			?>
			background-image: url( <?php header_image(); ?> );
			<?php
		}
		?>
	}
</style>

<div class="<?php echo $site_branding_classes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
	<div class="site-title-box">
		<?php
		Anwas_Scratch_Custom_Logo::display_custom_logo();

		if ( is_front_page() && is_home() ) {
			?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php
		} else {
			?>
			<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php
		}

		if ( $anwas_scratch_description || is_customize_preview() ) {
			?>
			<p class="site-description">
				<?php echo $anwas_scratch_description; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
			</p>
			<?php
		}
		?>
	</div>
</div>
<!-- END .site-branding -->
