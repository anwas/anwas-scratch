/******/ (function() { // webpackBootstrap
var __webpack_exports__ = {};
/*!*****************************************************************************!*\
  !*** ../sites/wp/web/wp-content/themes/anwas-scratch/_src/js/customizer.js ***!
  \*****************************************************************************/
/* global wp */

/**
 * Failas customizer.js.
 *
 * Temos tinkinimo priemonės patobulinimai geresnei vartotojo patirčiai.
 *
 * Yra tvarkyklių, leidžiančių asinchroniškai atlikti
 * temos tinkinimo priemonės peržiūros pakeitimus.
 */
if ('loading' === document.readyState) {
  // DOM dar neįkeltas.
  document.addEventListener('DOMContentLoaded', initThemeCustomizePreview);
} else {
  // DOM jau įkeltas.
  initThemeCustomizePreview();
}

function initThemeCustomizePreview() {
  actionCustomizerBlogName();
  actionCustomizerSiteDescription();
  actionCustomizerHeaderTextColor();
  actionCustomizerSiteFooter();
}

function actionCustomizerBlogName() {
  var site_title = document.querySelector('.site-title a');

  if (!site_title) {
    return;
  } // Svetainės antraštės tekstas.


  wp.customize('blogname', function (value) {
    value.bind(function (to) {
      site_title.textContent = to;
    });
  });
}

function actionCustomizerSiteDescription() {
  var site_description = document.querySelector('.site-description');

  if (!site_description) {
    return;
  } // Svetainės aprašymo tekstas.


  wp.customize('blogdescription', function (value) {
    value.bind(function (to) {
      site_description.textContent = to;
    });
  });
}

function actionCustomizerHeaderTextColor() {
  var site_branding = document.querySelector('.site-branding');
  var site_title = document.querySelector('.site-title a');
  var site_description = document.querySelector('.site-description');

  if (!site_branding || !site_title || !site_description) {
    return;
  } // Svetainės antraštės teksto spalva.


  wp.customize('header_textcolor', function (value) {
    value.bind(function (to) {
      if ('blank' === to) {
        site_branding.style.clip = 'rect(1px, 1px, 1px, 1px)';
        site_branding.style.position = 'absolute';
      } else {
        site_branding.style.clip = 'auto';
        site_branding.style.position = 'relative';
        site_title.style.color = to;
        site_description.style.color = to;
      }
    });
  });
}

function actionCustomizerSiteFooter() {
  var site_info = document.querySelector('.site-info');

  if (!site_info) {
    return;
  } // TODO: padaryti, kad automatiškai įterpų pastraipas ir <br /> žymas, kai reikia.


  wp.customize('anwas_scratch_site_footer', function (value) {
    value.bind(function (to) {
      site_info.innerHTML = to;
    });
  });
}
/******/ })()
;
//# sourceMappingURL=customizer.js.map