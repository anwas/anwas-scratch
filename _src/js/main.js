/**
 * Failas main.js.
 *
 * Pagrindinis temos JavaScript failas.
 */

'use strict';

if ('loading' === document.readyState) {
	// DOM dar neįkeltas.
	document.addEventListener('DOMContentLoaded', initThemeMainScripts);
} else {
	// DOM jau įkeltas.
	initThemeMainScripts();
}

function initThemeMainScripts() { }
