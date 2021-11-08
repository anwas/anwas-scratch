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

$branding_bg = get_theme_mod( 'anwas_scratch_branding_bg', '#2c86ba' );
?>
<style>
	.site-branding {
		background-color: <?php echo esc_attr( $branding_bg ); ?>;
	}
</style>

<div class="site-branding">
	<?php
	Anwas_Scratch_Custom_Logo::display_custom_logo();

	?>
	<div class="site-title-box">
		<?php
		if ( is_front_page() && is_home() ) {
			?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php
		} else {
			?>
			<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php
		}

		$anwas_scratch_description = get_bloginfo( 'description', 'display' );
		if ( $anwas_scratch_description || is_customize_preview() ) {
			?>
			<p class="site-description">
				<?php echo $anwas_scratch_description; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
			</p>
			<?php
		}
		?>
	</div>
	<?php

	get_template_part( 'template-parts/header/custom_header' );
	?>
</div>
<!-- END .site-branding -->
