<?php
/**
 * Anwas_Scratch\Nav_Menus\Nav_Walker klasė.
 *
 * @package Anwas_Scratch
 *
 * @since 1.0.0
 */

declare(strict_types=1);

namespace Anwas_Scratch\Nav_Menus;

use \Walker_Nav_Menu;

/**
 * WordPress Walker_Nav_Menu išplėtimo klasė.
 */
class Nav_Walker extends Walker_Nav_Menu {
	/**
	 * Sąrašas pradedamas prieš įtraukiant elementus.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string   $output Naudojamas papildomam turiniui pridėti (perduotas pagal nuorodą (by reference)).
	 * @param int      $depth  Meniu elemento gylis. Naudojamas atitraukimui (paddings).
	 * @param stdClass $args   „wp_nav_menu()“ argumentų objektas.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Standartinė WordPress CSS klasė.
		$classes = array( 'sub-menu' );

		// Bootstrap 5 CSS klasė.
		$classes[] = 'dropdown-menu';

		// Pasirinktinių CSS klasių valdymas.
		$css_menu_lvl = $depth + 2;  // Antro lygio „<ul>“ elementas turės CSS klasę „menu-lvl-2“, trečio – „menu-lvl-3“ ir t.t.
		$classes[]    = 'menu-lvl-' . $css_menu_lvl;

		/**
		 * Filtruoja meniu sąrašui pritaikytą (-as) CSS klasę (klases).
		 *
		 * @since 4.8.0
		 *
		 * @param string[] $classes Meniu elementui „<ul>“ pritaikytų CSS klasių masyvas.
		 * @param stdClass $args    „wp_nav_menu()“ argumentų objektas.
		 * @param int      $depth   Meniu elemento gylis. Naudojamas atitraukimui (tabuliavimui) ir kt.
		 */
		$class_names = implode( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}<ul$class_names>{$n}";
	}

	/**
	 * Pradedama elemento išvestis.
	 *
	 * @since 3.0.0
	 * @since 4.4.0 Pridėtas filtras {@see 'nav_menu_item_args'}.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string   $output Naudojamas papildomam turiniui pridėti (perduotas pagal nuorodą (by reference)).
	 * @param WP_Post  $item   Meniu elemento duomenų objektas.
	 * @param int      $depth  Meniu elemento gylis. Naudojamas atitraukimui (tabuliavimui) ir kt.
	 * @param stdClass $args   „wp_nav_menu()“ argumentų objektas.
	 * @param int      $id     Dabartinio elemento ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$li_data_attrs = ''; // Jei reikia, galime daabartiniam „<li>“ elementui pridėti reikiamus data atributus.
		$class_names   = ''; // „<li>“ elemento CSS klasių eilutė (sąrašas).

		/**
		 * Filtruoja vieno naršymo meniu elemento argumentus.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args  „wp_nav_menu()“ argumentų objektas.
		 * @param WP_Post  $item  Meniu elemento duomenų objektas.
		 * @param int      $depth Meniu elemento gylis. Naudojamas atitraukimui (tabuliavimui) ir kt.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		// „<li>“ elemento CSS klasių masyvas.
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		/*
		 * Bootstrap 5 skyriklio (dropdown-divider) valdymas:
		 *    jei dabartiniam elementui yra priskirt klasė „dropdown-divider“,
		 *    tai prieš jį pridedamas papildomas „<li>“ elemetas su „<hr>“ žyma.
		 */
		$divider_class_position = array_search( 'dropdown-divider', $classes, true );
		if ( false !== $divider_class_position ) {
			$output .= $indent . '<li aria-hidden="true"><hr class="dropdown-divider"></li>' . $n; // Bootstrap 5 elementas.
			unset( $classes[ $divider_class_position ] ); // dabartiniam „<li>“ elementui pašalinama „dropdown-divider“ klasė.
		}

		// Standartinė WordPress CSS klasė.
		$classes[] = 'menu-item-' . $item->ID;

		// Bootstrap 5 CSS klasės.
		$classes[] = ( $args->walker->has_children && $depth ) ? 'dropend' : ( ( $args->walker->has_children ) ? 'dropdown' : '' );
		$classes[] = ( $item->current || $item->current_item_ancestor || $item->current_item_parent ) ? 'active' : '';
		$classes[] = ( ! $depth ) ? 'nav-item' : '';

		// Pasirinktinių CSS klasių valdymas.
		if ( $args->walker->has_children && $depth ) {
			$classes[] = 'dropdown-submenu';
		}

		/**
		 * Filtruoja CSS klases, taikomas meniu sąrašo elementui.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 Buvo pridėtas parametras „$depth“.
		 *
		 * @param string[] $classes CSS klasių masyvas, taikomas meniu elementui „<li>“.
		 * @param WP_Post  $item    Dabartinis meniu elementas.
		 * @param stdClass $args    „wp_nav_menu()“ argumentų objektas.
		 * @param int      $depth   Meniu elemento gylis. Naudojamas atitraukimui (tabuliavimui) ir kt.
		 */
		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filtruoja ID, taikomą meniu sąrašo elementui.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 Buvo pridėtas parametras „$depth“.
		 *
		 * @param string   $menu_id ID, kuris taikomas meniu elementui „<li>“.
		 * @param WP_Post  $item    Dabartinis meniu elementas.
		 * @param stdClass $args    „wp_nav_menu()“ argumentų objektas.
		 * @param int      $depth   Meniu elemento gylis. Naudojamas atitraukimui (tabuliavimui) ir kt.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . $li_data_attrs . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		if ( '_blank' === $item->target && empty( $item->xfn ) ) {
			$atts['rel'] = 'noopener noreferer';
		} else {
			$atts['rel'] = $item->xfn;
		}
		$atts['href']         = ! empty( $item->url ) ? $item->url : '';
		$atts['aria-current'] = $item->current ? 'page' : '';

		/**
		 * Filtruoja HTML atributus, taikomus meniu elemento nuorodos elementui.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 Buvo pridėtas parametras „$depth“.
		 *
		 * @param array $atts {
		 *     Meniu elemento „<a>“ elementui taikomi HTML atributai, tuščios eilutės yra ignoruojamos.
		 *
		 *     @type string $title        „title“ atributas.
		 *     @type string $target       „target“ atributas.
		 *     @type string $rel          „rel“ atributas.
		 *     @type string $href         „href“ atributas.
		 *     @type string $aria-current „aria-current“ atributas.
		 * }
		 * @param WP_Post  $item  Dabartinis meniu elementas.
		 * @param stdClass $args  „wp_nav_menu()“ argumentų objektas.
		 * @param int      $depth Meniu elemento gylis. Naudojamas atitraukimui (tabuliavimui) ir kt.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** Šis filtras dokumentuotas wp-includes/post-template.php faile. */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filtruoja meniu elemento pavadinimą.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title Meniu elemento pavadinimas.
		 * @param WP_Post  $item  Dabartinis meniu elementas.
		 * @param stdClass $args  „wp_nav_menu()“ argumentų objektas.
		 * @param int      $depth Meniu elemento gylis. Naudojamas atitraukimui (tabuliavimui) ir kt.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		/*
		 * Labai svarbu: „data-bs-auto-close="outside"“,
		 * nes be šito Bootstrap 5 multilevel meniu neveikia – neišsiskleidžia 3 ir didesnio lvl meniu.
		 *
		 * Bootstrap 5 nuorodų („<a>“) CSS klasės: „nav-link“, „dropdown-item“, „dropdown-toggle“.
		 */
		$link_classes  = array();
		$link_atts     = array(); // galima naudoti bet kokiems atributams: data-, aria-, role ar pan.
		$link_atts_str = '';

		if ( isset( $item->object_id ) && get_option( 'page_on_front' ) === $item->object_id ) {
			$link_classes[] = ( $depth ) ? 'dropdown-item--front-page' : 'nav-link--front-page';
		}

		if ( isset( $item->object_id ) && get_option( 'page_for_posts' ) === $item->object_id ) {
			$link_classes[] = ( $depth ) ? 'dropdown-item--blog-page' : 'nav-link--blog-page';
		}

		if ( $item->current || $item->current_item_ancestor || $item->current_item_parent ) {
			$link_classes[] = ( $depth ) ? 'dropdown-item--active' : 'nav-link--active';
		}

		$link_classes[] = ( $depth ) ? 'dropdown-item' : 'nav-link';

		if ( $args->walker->has_children ) {
			$link_classes[]              = 'dropdown-toggle';
			$link_atts['data-bs-toggle'] = 'dropdown';
			$link_atts['data-bs-auto']   = 'outside';
			$link_atts['role']           = 'button';
			$link_atts['aria-expanded']  = 'false';
		}

		// Suformuojama nuorodos „<a>“ CSS klasių eilutė.
		$link_classes_names = implode( ' ', array_filter( $link_classes ) );
		$link_classes_names = $link_classes_names ? ' class="' . esc_attr( $link_classes_names ) . '"' : '';

		// Suformuojama nuorodos „<a>“ data atributų eilutė.
		if ( ! empty( $link_atts ) ) {
			foreach ( $link_atts as $data_att_key => $data_att_value ) {
				$link_atts_str .= ' ' . esc_attr( $data_att_key ) . '="' . esc_attr( $data_att_value ) . '"';
			}
		}

		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . $link_classes_names . $link_atts_str . '>';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= ( ( 0 === $depth || 1 ) && $args->walker->has_children ) ? ' <span class="caret"></span>' : '';
		$item_output .= '</a>';
		// Prideda meniu elemento aprašymo eilutė, jei ji ne tuščia.
		if ( ! empty( $item->description ) ) {
			$item_output .= sprintf( '<span class="nav-item--description">%s</span>', wp_kses_post( $item->description ) );
		}
		$item_output .= $args->after;

		/**
		 * Filtruoja meniu elemento pradžios išvestį.
		 *
		 * Meniu elemento pradžios išvestį sudaro tik „$args->before“, pradžios „<a>“,
		 * meniu elemento pavadinimas, uždarymo „</a>“ ir „$args->after“.
		 * Šiuo metu nėra filtro, skirto keisti meniu elemento atidarymo ir uždarymo „<li>“.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output Meniu elemento HTML išvesties pradžia.
		 * @param WP_Post  $item        Meniu elemento duomenų objektas.
		 * @param int      $depth       Meniu elemento gylis. Naudojamas atitraukimui (tabuliavimui) ir kt.
		 * @param stdClass $args        „wp_nav_menu()“ argumentų objektas.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}
