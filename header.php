<?php
/**
 * Pagrindinis temos antraštės failas.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php // Jei naudotojo naršyklėje veikia JavaScript, iš html žymos bus pašalinta no-js klasė. ?>
	<script>document.documentElement.classList.remove( 'no-js' );</script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'anwas-scratch' ); ?></a>

	<header id="masthead" class="site-header" role="branding">
		<?php
		get_template_part( 'template-parts/header/branding' ); // Viduje dar įtrauktas custom_header failas. Pagal pareikį struktūros išdėstymą galima paprastai keisti.

		get_template_part( '/template-parts/header/navigation' );
		?>
	</header>

	<div id="content" class="site-content"><?php // Uždaroma footer.php faile. ?>
