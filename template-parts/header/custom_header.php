<?php
/**
 * Šablono dalis rodyti pritaikomos antraštės mediją (temos antraštės paveikslėlį).
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch;

use \Anwas_Scratch\Setup\Custom_Header as Anwas_Scratch_Custom_Header;

if ( ! has_header_image() ) {
	return;
}

Anwas_Scratch_Custom_Header::display_custom_header_image();
