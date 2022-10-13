/* global anwas_scratch_screen_reader_text */
/**
 * Failas _search-form.js.
 *
 * Valdo paieškos formos svetainės antraštėje išskleidimą/suskleidimą.
 */

const SEARCH_FORM_INIT = function () {
	const SEARCH_TOGGLE = document.querySelector( '.search-toggle' );
	const SEARCH_CONTAINER = document.querySelector( '.header-search-form' );

	if ( ! SEARCH_TOGGLE || ! SEARCH_CONTAINER ) {
		return;
	}

	SEARCH_TOGGLE.addEventListener(
		'click',
		function ( e ) {
			e.preventDefault();

			if ( SEARCH_CONTAINER.classList.contains( 'toggled' ) ) {
				SEARCH_TOGGLE.setAttribute( 'aria-expanded', 'false' );
				SEARCH_TOGGLE.setAttribute( 'aria-label', anwas_scratch_screen_reader_text.expand_search_form );
				SEARCH_CONTAINER.classList.remove( 'toggled' );
			} else {
				SEARCH_TOGGLE.setAttribute( 'aria-expanded', 'true' );
				SEARCH_TOGGLE.setAttribute( 'aria-label', anwas_scratch_screen_reader_text.collapse_search_form );
				SEARCH_CONTAINER.classList.add( 'toggled' );

				const SEARCH_FIELD = SEARCH_CONTAINER.querySelector( '.search-field' );

				if ( SEARCH_FIELD ) {
					SEARCH_FIELD.focus();
				}
			}
		},
		false
	);
};

export default SEARCH_FORM_INIT;
