/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "../sites/wp/web/wp-content/themes/anwas-scratch/_src/js/_search-form.js":
/*!*******************************************************************************!*\
  !*** ../sites/wp/web/wp-content/themes/anwas-scratch/_src/js/_search-form.js ***!
  \*******************************************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* global anwas_scratch_screen_reader_text */

/**
 * Failas _search-form.js.
 *
 * Valdo paieškos formos svetainės antraštėje išskleidimą/suskleidimą.
 */
var SEARCH_FORM_INIT = function SEARCH_FORM_INIT() {
  var SEARCH_TOGGLE = document.querySelector('.search-toggle');
  var SEARCH_CONTAINER = document.querySelector('.header-search-form');

  if (!SEARCH_TOGGLE || !SEARCH_CONTAINER) {
    return;
  }

  SEARCH_TOGGLE.addEventListener('click', function (e) {
    e.preventDefault();

    if (SEARCH_CONTAINER.classList.contains('toggled')) {
      SEARCH_TOGGLE.setAttribute('aria-expanded', 'false');
      SEARCH_TOGGLE.setAttribute('aria-label', anwas_scratch_screen_reader_text.expand_search_form);
      SEARCH_CONTAINER.classList.remove('toggled');
    } else {
      SEARCH_TOGGLE.setAttribute('aria-expanded', 'true');
      SEARCH_TOGGLE.setAttribute('aria-label', anwas_scratch_screen_reader_text.collapse_search_form);
      SEARCH_CONTAINER.classList.add('toggled');
      var SEARCH_FIELD = SEARCH_CONTAINER.querySelector('.search-field');

      if (SEARCH_FIELD) {
        SEARCH_FIELD.focus();
      }
    }
  }, false);
};

/* harmony default export */ __webpack_exports__["default"] = (SEARCH_FORM_INIT);

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!*****************************************************************************!*\
  !*** ../sites/wp/web/wp-content/themes/anwas-scratch/_src/js/navigation.js ***!
  \*****************************************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _search_form__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./_search-form */ "../sites/wp/web/wp-content/themes/anwas-scratch/_src/js/_search-form.js");
/* global anwas_scratch_screen_reader_text */

/**
 * Failas navigation.js.
 *
 * Valdo naršymo meniu perjungimą mažuose ekranuose ir įgalina TAB klavišo
 * naršymo palaikymą išskleidžiamiesiems meniu.
 */

var KEYMAP = {
  TAB: 9,
  ESC: 27
};

if ('loading' === document.readyState) {
  // DOM dar neužkrautas.
  document.addEventListener('DOMContentLoaded', initNavigation);
} else {
  // DOM jau užkrautas.
  initNavigation();
} // Inicijuoja meniu, kai DOM užkrautas.


function initNavigation() {
  (0,_search_form__WEBPACK_IMPORTED_MODULE_0__["default"])();
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
 * Inicijuoja scenarijų, kad būtų apdoroti visi naršymo meniu,
 * kai įjungtas submeniu perjungimas.
 */


function initNavToggleSubmenus() {
  var NAV_TOGGLE = document.querySelectorAll('.main-navigation'); // Nėra prasmės tęsti, jei nėra meniu elemento.

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
 * Perjungia submeniu atidarymą ir uždarymą ir praneša ekrano skaitytuvams, kas vyksta.
 * @param {Object} parentMenuItem Tėvinis meniu elementas.
 * @param {boolean} forceToggle Priverstinai perjungti meniu.
 * @return {void}
 */


function toggleSubMenu(parentMenuItem, forceToggle) {
  var TOGGLE_BUTTON = parentMenuItem.querySelector('.dropdown-toggle');

  if (!TOGGLE_BUTTON) {
    return;
  }

  var SUB_MENU = parentMenuItem.querySelector('ul');
  var parentMenuItemToggled = parentMenuItem.classList.contains('menu-item--toggled-on'); // Bus „true“, jei norime priverstinai įjungti, „false“, jei priverstinį perjungimą uždaryti.

  if (undefined !== forceToggle && 'boolean' === typeof forceToggle) {
    parentMenuItemToggled = !forceToggle;
  } // Perjungia aria-expanded būseną.


  TOGGLE_BUTTON.setAttribute('aria-expanded', (!parentMenuItemToggled).toString());
  /*
   * Veiksmai, kuriuos reikia atlikti perjungimo metu:
   * - Pranešame pagrindiniam meniu elementui, kad įjungiame / išjungiame.
   * - Perjunti ARIA etiketę, kad ekrano skaitytuvai žinotų, kad išplėsta arba sutraukta.
   */

  if (parentMenuItemToggled) {
    // Perjunti "off" submeniu elementui.
    parentMenuItem.classList.remove('menu-item--toggled-on');
    SUB_MENU.classList.remove('toggle-show');
    TOGGLE_BUTTON.setAttribute('aria-label', anwas_scratch_screen_reader_text.expand); // Įsitikinti, kad visi vaikai yra uždaryti.

    var SUB_MENU_ITEMS_TOGGLED = parentMenuItem.querySelectorAll('.menu-item--toggled-on');

    for (var i = 0; i < SUB_MENU_ITEMS_TOGGLED.length; i++) {
      toggleSubMenu(SUB_MENU_ITEMS_TOGGLED[i], false);
    }
  } else {
    // Įsitikinti, kad broliai ir seserys (siblings) yra uždaryti.
    var PARENT_MENU_ITEMS_TOGGLED = parentMenuItem.parentNode.querySelectorAll('li.menu-item--toggled-on');

    for (var _i = 0; _i < PARENT_MENU_ITEMS_TOGGLED.length; _i++) {
      toggleSubMenu(PARENT_MENU_ITEMS_TOGGLED[_i], false);
    } // Perjunti "on" submeniu elementui.


    parentMenuItem.classList.add('menu-item--toggled-on');
    SUB_MENU.classList.add('toggle-show');
    TOGGLE_BUTTON.setAttribute('aria-label', anwas_scratch_screen_reader_text.collapse);
  }
}

function actionCloseAllMenus() {
  var NAV = document.querySelector('nav.main-navigation'); // Nėra prasmės tęsti, jei nėra meniu.

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
}();
/******/ })()
;
//# sourceMappingURL=navigation.js.map