/* global anwas_scratch_screen_reader_text */
/**
 * Failas navigation.js.
 *
 * Valdo naršymo meniu perjungimą mažuose ekranuose ir įgalina TAB klavišo
 * naršymo palaikymą išskleidžiamiesiems meniu.
 */

import SEARCH_FORM_INIT from './_search-form';

const KEYMAP = {
	TAB: 9,
	ESC: 27,
};

if ('loading' === document.readyState) {
	// DOM dar neužkrautas.
	document.addEventListener('DOMContentLoaded', initNavigation);
} else {
	// DOM jau užkrautas.
	initNavigation();
}

// Inicijuoja meniu, kai DOM užkrautas.
function initNavigation() {
	SEARCH_FORM_INIT();

	initNavToggleSubmenus();

	const BODY_EL = document.querySelector('html > body');

	BODY_EL.addEventListener('keydown', (e) => {
		if (KEYMAP.ESC === e.keyCode) {
			actionCloseAllMenus();
		}
	}, false);

	BODY_EL.addEventListener('click', (e) => {
		actionCloseAllMenus();
	}, false);
}

/**
 * Inicijuoja scenarijų, kad būtų apdoroti visi naršymo meniu,
 * kai įjungtas submeniu perjungimas.
 */
function initNavToggleSubmenus() {
	const NAV_TOGGLE = document.querySelectorAll('.main-navigation');

	// Nėra prasmės tęsti, jei nėra meniu elemento.
	if (!NAV_TOGGLE.length) {
		return;
	}

	for (let i = 0; i < NAV_TOGGLE.length; i++) {
		initMenuToggleButton(NAV_TOGGLE[i]);
		initEachDropdown(NAV_TOGGLE[i]);
	}
}

function initMenuToggleButton(nav) {
	// Mobilaus meniu perjungimo mygtukas.
	const MENU_TOOGLE = nav.querySelector('.menu-toggle');

	// Jei nėra meniu perjungimo mygtuko, nėra prasmės tęsti.
	if (!MENU_TOOGLE) {
		return;
	}

	const MENU_CONTAINER = nav.querySelector('.primary-menu-container');

	// Jei nėra meniu konteinerio, nėra prasmės tęsti.
	if (!MENU_CONTAINER) {
		return;
	}

	MENU_TOOGLE.addEventListener('click', function (e) {
		e.preventDefault();
		e.stopPropagation();

		if (MENU_CONTAINER.classList.contains('toggled')) {
			MENU_CONTAINER.classList.remove('toggled');
			MENU_TOOGLE.setAttribute('aria-label', anwas_scratch_screen_reader_text.expand_menu);
			MENU_TOOGLE.setAttribute('aria-expanded', 'false');
		} else {
			MENU_CONTAINER.classList.add('toggled');
			MENU_TOOGLE.setAttribute('aria-label', anwas_scratch_screen_reader_text.collapse_menu);
			MENU_TOOGLE.setAttribute('aria-expanded', 'true');
		}

	}, false);
}

function initEachDropdown(nav) {
	const DROPDOWNS = nav.querySelectorAll('.menu-item-has-children', '.page_item_has_children'); // li elementas su menu-item-has-children arba .page_item_has_children CSS klase.

	// Jei nėra išskleidžiamų meniu, nėra prasmės tęsti.
	if (!DROPDOWNS.length) {
		return;
	}

	for (let i = 0; i < DROPDOWNS.length; i++) {

		// Elementas su .dropdown-toggle CSS klase, kuris išskleidžia submeniu (įprastai tai <a> elementas bet gali būti ir <button> ar pan.).
		const DROPDOWN_TOGGLE = DROPDOWNS[i].querySelector('.dropdown-toggle');

		if (!DROPDOWN_TOGGLE) {
			continue;
		}

		DROPDOWN_TOGGLE.addEventListener('click', function (e) {
			e.preventDefault();
			e.stopPropagation();
			const DROPDOWN_TOGGLE_PARENT = DROPDOWN_TOGGLE.parentNode;

			toggleSubMenu(DROPDOWN_TOGGLE_PARENT);
		}, false);
	}
}



/**
 * Perjungia submeniu atidarymą ir uždarymą ir praneša ekrano skaitytuvams, kas vyksta.
 * @param {Object} parentMenuItem Tėvinis meniu elementas.
 * @param {boolean} forceToggle Priverstinai perjungti meniu.
 * @return {void}
 */
function toggleSubMenu(parentMenuItem, forceToggle) {
	const TOGGLE_BUTTON = parentMenuItem.querySelector('.dropdown-toggle');

	if (!TOGGLE_BUTTON) {
		return;
	}

	const SUB_MENU = parentMenuItem.querySelector('ul');
	let parentMenuItemToggled = parentMenuItem.classList.contains('menu-item--toggled-on');

	// Bus „true“, jei norime priverstinai įjungti, „false“, jei priverstinį perjungimą uždaryti.
	if (undefined !== forceToggle && 'boolean' === (
		typeof forceToggle
	)) {
		parentMenuItemToggled = !forceToggle;
	}

	// Perjungia aria-expanded būseną.
	TOGGLE_BUTTON.setAttribute('aria-expanded', (
		!parentMenuItemToggled
	).toString());

	/*
	 * Veiksmai, kuriuos reikia atlikti perjungimo metu:
	 * - Pranešame pagrindiniam meniu elementui, kad įjungiame / išjungiame.
	 * - Perjunti ARIA etiketę, kad ekrano skaitytuvai žinotų, kad išplėsta arba sutraukta.
	 */
	if (parentMenuItemToggled) {
		// Perjunti "off" submeniu elementui.
		parentMenuItem.classList.remove('menu-item--toggled-on');
		SUB_MENU.classList.remove('toggle-show');
		TOGGLE_BUTTON.setAttribute('aria-label', anwas_scratch_screen_reader_text.expand);

		// Įsitikinti, kad visi vaikai yra uždaryti.
		const SUB_MENU_ITEMS_TOGGLED = parentMenuItem.querySelectorAll('.menu-item--toggled-on');
		for (let i = 0; i < SUB_MENU_ITEMS_TOGGLED.length; i++) {
			toggleSubMenu(SUB_MENU_ITEMS_TOGGLED[i], false);
		}
	} else {
		// Įsitikinti, kad broliai ir seserys (siblings) yra uždaryti.
		const PARENT_MENU_ITEMS_TOGGLED = parentMenuItem.parentNode.querySelectorAll('li.menu-item--toggled-on');
		for (let i = 0; i < PARENT_MENU_ITEMS_TOGGLED.length; i++) {
			toggleSubMenu(PARENT_MENU_ITEMS_TOGGLED[i], false);
		}

		// Perjunti "on" submeniu elementui.
		parentMenuItem.classList.add('menu-item--toggled-on');
		SUB_MENU.classList.add('toggle-show');
		TOGGLE_BUTTON.setAttribute('aria-label', anwas_scratch_screen_reader_text.collapse);
	}
}


function actionCloseAllMenus() {
	const NAV = document.querySelector('nav.main-navigation');

	// Nėra prasmės tęsti, jei nėra meniu.
	if (!NAV) {
		return;
	}

	const MENU_CONTAINER = NAV.querySelector('.primary-menu-container');

	// Jei nėra meniu konteinerio, nėra prasmės tęsti.
	if (!MENU_CONTAINER) {
		return;
	}

	// Mobilaus meniu perjungimo mygtukas.
	const MENU_TOOGLE = NAV.querySelector('.menu-toggle');

	// Jei nėra meniu perjungimo mygtuko, nėra prasmės tęsti.
	if (!MENU_TOOGLE) {
		return;
	}

	if (MENU_CONTAINER.classList.contains('toggled')) {
		MENU_CONTAINER.classList.remove('toggled');
		MENU_TOOGLE.setAttribute('aria-label', anwas_scratch_screen_reader_text.expand_menu);
		MENU_TOOGLE.setAttribute('aria-expanded', 'false');
	}

	// TODO: sugalvoti, kaip teisingai padaryti meniu perjungimo mygtuko fokusavimą, kai spaudžiama už meniu ribų.
	// const SEARCH_TOGGLE = document.querySelector('.search-toggle');
	// const SEARCH_CONTAINER = document.querySelector('.header-search-form');

	// if (SEARCH_TOGGLE && SEARCH_CONTAINER && !SEARCH_CONTAINER.classList.contains('toggled')) {
	// 	MENU_TOOGLE.focus();
	// }

	const PARENT_MENU_ITEMS_TOGGLED = NAV.querySelectorAll('.menu li.menu-item--toggled-on');

	if (!PARENT_MENU_ITEMS_TOGGLED.length) {
		return;
	}

	for (let i = 0; i < PARENT_MENU_ITEMS_TOGGLED.length; i++) {
		toggleSubMenu(PARENT_MENU_ITEMS_TOGGLED[i], false);
	}
}
