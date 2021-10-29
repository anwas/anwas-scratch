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
		$indent       = str_repeat( "\t", $depth );
		$submenu      = ( $depth > 0 ) ? ' sub-menu' : '';
		$css_depth    = $depth + 1;
		$css_menu_lvl = $depth + 2;
		$output      .= "\n{$indent}<ul class=\"dropdown-menu{$submenu} depth-{$css_depth} menu-lvl-{$css_menu_lvl}\" >\n";
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
	 * @param int      $depth  Meniu elemento gylis. Naudojamas atitraukimui (paddings).
	 * @param stdClass $args   „wp_nav_menu()“ argumentų objektas.
	 * @param int      $id     Dabartinio elemento ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$li_attributes = '';
		$class_names   = '';
		$value         = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		// Skyriklio (divider) valdymas: prideda skyriklio klasę prie elemento, kad prieš jį būtų gautas skyriklis.
		// Managing divider: add divider class to an element to get a divider before it.
		$divider_class_position = array_search( 'divider', $classes, true );
		if ( false !== $divider_class_position ) {
			$output .= "<li class=\"divider\"></li>\n";
			unset( $classes[ $divider_class_position ] );
		}

		$classes[] = ( $args->has_children ) ? 'dropdown' : '';
		$classes[] = ( $item->current || $item->current_item_ancestor ) ? 'active' : '';
		$classes[] = 'menu-item-' . $item->ID;
		if ( $depth && $args->has_children ) {
			$classes[] = 'dropdown-submenu';
		}

		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		$aria_current = ( $item->current ) ? ' aria-current="page"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';
		$attributes .= ( $args->has_children ) ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';

		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . $aria_current . '>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;

		// Prideda meniu elemento pavadinimo palaikymą.
		if ( strlen( $item->attr_title ) > 2 ) {
			$item_output .= '<h3 class="menu-item-title">' . $item->attr_title . '</h3>';
		}

		// Prideda meniu elementų aprašymų palaikymą.
		if ( strlen( $item->description ) > 2 ) {
			$item_output .= '</a> <span class="sub">' . $item->description . '</span>';
		}
		$item_output .= ( ( 0 === $depth || 1 ) && $args->has_children ) ? ' <span class="caret"></span></a>' : '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Perrenka (traverce) elementus, kad sudarytų sąrašą iš elementų.
	 *
	 * Rodo vieną elementą, jei elementas neturi vaikų“, kitu atveju rodo elementą ir jo vaikus.
	 * Perrinks tik iki didžiausio gylio ir nepaisys elementų žemiau šio gylio.
	 * Galima nustatyti maksimalų gylį, kad būtų įtraukti visi gyliai, žr. walk() metodą.
	 *
	 * Šio metodo nereikėtų iškviesti tiesiogiai, vietoj jo naudokite metodą walk().
	 *
	 * @since 2.5.0
	 *
	 * @param object $element           Duomenų objektas.
	 * @param array  $children_elements Elementų sąrašas, kuriuos reikia tęsti perrinti (perduodamas pagal nuorodą (by reference)).
	 * @param int    $max_depth         maksimalus perrinkimo gylis.
	 * @param int    $depth             Esamo elemento gylis.
	 * @param array  $args              Argumentų masyvas.
	 * @param string $output            Naudojamas papildomam turiniui pridėti (perduotas pagal nuorodą (by reference)).
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( ! $element ) {
			return;
		}

		$id_field = $this->db_fields['id'];

		// Rodyti šį elementą.
		if ( is_array( $args[0] ) ) {
			$args[0]['has_children'] = ! empty( $children_elements[ $element->$id_field ] );
		} elseif ( is_object( $args[0] ) ) {
			$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
		}

		$cb_args = array_merge( array( &$output, $element, $depth ), $args );
		call_user_func_array( array( $this, 'start_el' ), $cb_args );

		$id = $element->$id_field;

		// Eiti gilyn tik tada, kai gylis tinkamas ir yra vaikų šiam elementui.
		if ( ( 0 === $max_depth || $max_depth > $depth + 1 ) && isset( $children_elements[ $id ] ) ) {
			foreach ( $children_elements[ $id ] as $child ) {
				if ( ! isset( $newlevel ) ) {
					$newlevel = true;
					// Pradėti vaiko skyriklį.
					$cb_args = array_merge( array( &$output, $depth ), $args );
					call_user_func_array( array( $this, 'start_lvl' ), $cb_args );
				}
				$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
			}
			unset( $children_elements[ $id ] );
		}

		if ( isset( $newlevel ) && $newlevel ) {
			// Baigti vaiko skyriklį.
			$cb_args = array_merge( array( &$output, $depth ), $args );
			call_user_func_array( array( $this, 'end_lvl' ), $cb_args );
		}

		// Baigti šį elmentą.
		$cb_args = array_merge( array( &$output, $element, $depth ), $args );
		call_user_func_array( array( $this, 'end_el' ), $cb_args );
	}
}
