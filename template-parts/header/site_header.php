<?php
/**
 * Šablono dalis, skirta svetainės pagrindinei antraštei (site-header) rodyti.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

use \Anwas_Scratch\Setup\Custom_Logo as Anwas_Scratch_Custom_Logo;

?>
<header id="masthead" class="site-header" role="branding">
	<?php
	get_template_part( 'template-parts/header/custom_header' );

	get_template_part( 'template-parts/header/branding' ); // Viduje dar įtrauktas custom_header failas. Pagal pareikį struktūros išdėstymą galima paprastai keisti.

	get_template_part( '/template-parts/header/navigation' );
	?>
</header>
