/**
 * Wrapper function to safely use $
 */
function wpcdWrapper( $ ) {
	var wpcd = {

		/**
		 * Main entry point
		 */
		init: function () {
			wpcd.prefix      = 'wpcd_';
			wpcd.templateURL = $( '#template-url' ).val();
			wpcd.ajaxPostURL = $( '#ajax-post-url' ).val();

			wpcd.registerEventHandlers();
		},

		/**
		 * Registers event handlers
		 */
		registerEventHandlers: function () {
			$( '#example-container' ).children( 'a' ).click( wpcd.exampleHandler );
		},

		/**
		 * Example event handler
		 *
		 * @param object event
		 */
		exampleHandler: function ( event ) {
			alert( $( this ).attr( 'href' ) );

			event.preventDefault();
		}
	}; // end wpcd

	$( document ).ready( wpcd.init );

} // end wpcdWrapper()

wpcdWrapper( jQuery );
