/******/ (function() { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************************************************!*\
  !*** ../sites/wp/web/wp-content/themes/anwas-scratch/_src/js/navigation.js ***!
  \*****************************************************************************/
/* global anwas_scratch_screen_reader_text */

/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
var KEYMAP = {
  TAB: 9,
  ESC: 27
};

if ('loading' === document.readyState) {
  // The DOM has not yet been loaded.
  document.addEventListener('DOMContentLoaded', initNavigation);
} else {
  // The DOM has already been loaded.
  initNavigation();
} // Initiate the menus when the DOM loads.


function initNavigation() {
  initNavToggleSubmenus();
  var BODY_EL = document.querySelector('html > body');
  BODY_EL.addEventListener('keydown', function (e) {
    if (KEYMAP.ESC === e.keyCode) {
      actionCloseAllMenus();
    }
  }, false);
  BODY_EL.addEventListener('click', function (e) {
    actionCloseAllMenus();
  }, false);
}
/**
 * Initiate the script to process all
 * navigation menus with submenu toggle enabled.
 */


function initNavToggleSubmenus() {
  var NAV_TOGGLE = document.querySelectorAll('.main-navigation'); // No point if no navs.

  if (!NAV_TOGGLE.length) {
    return;
  }

  for (var i = 0; i < NAV_TOGGLE.length; i++) {
    initMenuToggleButton(NAV_TOGGLE[i]);
    initEachDropdown(NAV_TOGGLE[i]);
  }
}

function initMenuToggleButton(nav) {
  // Mobilaus meniu perjungimo mygtukas.
  var MENU_TOOGLE = nav.querySelector('.menu-toggle'); // Jei nėra meniu perjungimo mygtuko, nėra prasmės tęsti.

  if (!MENU_TOOGLE) {
    return;
  }

  var MENU_CONTAINER = nav.querySelector('.primary-menu-container'); // Jei nėra meniu konteinerio, nėra prasmės tęsti.

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
  var DROPDOWNS = nav.querySelectorAll('.menu-item-has-children', '.page_item_has_children'); // li elementas su menu-item-has-children arba .page_item_has_children CSS klase.
  // Jei nėra išskleidžiamų meniu, nėra prasmės tęsti.

  if (!DROPDOWNS.length) {
    return;
  }

  var _loop = function _loop(i) {
    // Elementas su .dropdown-toggle CSS klase, kuris išskleidžia submeniu (įprastai tai <a> elementas bet gali būti ir <button> ar pan.).
    var DROPDOWN_TOGGLE = DROPDOWNS[i].querySelector('.dropdown-toggle');

    if (!DROPDOWN_TOGGLE) {
      return "continue";
    }

    DROPDOWN_TOGGLE.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      var DROPDOWN_TOGGLE_PARENT = DROPDOWN_TOGGLE.parentNode;
      toggleSubMenu(DROPDOWN_TOGGLE_PARENT);
    }, false);
  };

  for (var i = 0; i < DROPDOWNS.length; i++) {
    var _ret = _loop(i);

    if (_ret === "continue") continue;
  }
}
/**
 * Toggle submenus open and closed, and tell screen readers what's going on.
 * @param {Object} parentMenuItem Parent menu element.
 * @param {boolean} forceToggle Force the menu toggle.
 * @return {void}
 */


function toggleSubMenu(parentMenuItem, forceToggle) {
  var TOGGLE_BUTTON = parentMenuItem.querySelector('.dropdown-toggle');

  if (!TOGGLE_BUTTON) {
    return;
  }

  var SUB_MENU = parentMenuItem.querySelector('ul');
  var parentMenuItemToggled = parentMenuItem.classList.contains('menu-item--toggled-on'); // Will be true if we want to force the toggle on, false if force toggle close.

  if (undefined !== forceToggle && 'boolean' === typeof forceToggle) {
    parentMenuItemToggled = !forceToggle;
  } // Toggle aria-expanded status.


  TOGGLE_BUTTON.setAttribute('aria-expanded', (!parentMenuItemToggled).toString());
  /*
   * Steps to handle during toggle:
   * - Let the parent menu item know we're toggled on/off.
   * - Toggle the ARIA label to let screen readers know will expand or collapse.
   */

  if (parentMenuItemToggled) {
    // Toggle "off" the submenu.
    parentMenuItem.classList.remove('menu-item--toggled-on');
    SUB_MENU.classList.remove('toggle-show');
    TOGGLE_BUTTON.setAttribute('aria-label', anwas_scratch_screen_reader_text.expand); // Make sure all children are closed.

    var SUB_MENU_ITEMS_TOGGLED = parentMenuItem.querySelectorAll('.menu-item--toggled-on');

    for (var i = 0; i < SUB_MENU_ITEMS_TOGGLED.length; i++) {
      toggleSubMenu(SUB_MENU_ITEMS_TOGGLED[i], false);
    }
  } else {
    // Make sure siblings are closed.
    var PARENT_MENU_ITEMS_TOGGLED = parentMenuItem.parentNode.querySelectorAll('li.menu-item--toggled-on');

    for (var _i = 0; _i < PARENT_MENU_ITEMS_TOGGLED.length; _i++) {
      toggleSubMenu(PARENT_MENU_ITEMS_TOGGLED[_i], false);
    } // Toggle "on" the submenu.


    parentMenuItem.classList.add('menu-item--toggled-on');
    SUB_MENU.classList.add('toggle-show');
    TOGGLE_BUTTON.setAttribute('aria-label', anwas_scratch_screen_reader_text.collapse);
  }
}

function actionCloseAllMenus() {
  var NAV = document.querySelector('nav.main-navigation'); // No point if no nav menu.

  if (!NAV) {
    return;
  }

  var MENU_CONTAINER = NAV.querySelector('.primary-menu-container'); // Jei nėra meniu konteinerio, nėra prasmės tęsti.

  if (!MENU_CONTAINER) {
    return;
  } // Mobilaus meniu perjungimo mygtukas.


  var MENU_TOOGLE = NAV.querySelector('.menu-toggle'); // Jei nėra meniu perjungimo mygtuko, nėra prasmės tęsti.

  if (!MENU_TOOGLE) {
    return;
  }

  if (MENU_CONTAINER.classList.contains('toggled')) {
    MENU_CONTAINER.classList.remove('toggled');
    MENU_TOOGLE.setAttribute('aria-label', anwas_scratch_screen_reader_text.expand_menu);
    MENU_TOOGLE.setAttribute('aria-expanded', 'false');
  }

  MENU_TOOGLE.focus();
  var PARENT_MENU_ITEMS_TOGGLED = NAV.querySelectorAll('.menu li.menu-item--toggled-on');

  if (!PARENT_MENU_ITEMS_TOGGLED.length) {
    return;
  }

  for (var i = 0; i < PARENT_MENU_ITEMS_TOGGLED.length; i++) {
    toggleSubMenu(PARENT_MENU_ITEMS_TOGGLED[i], false);
  }
}
/******/ })()
;
//# sourceMappingURL=navigation.js.map