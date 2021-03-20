
/* Expanding menus for desktop layout */

$( function() {
	// When the menus (or their respective little arrows, which are still
	// contained in the menu element) are clicked, show their contents.
	// CSS/LESS takes care of the users who don't have JS or have it disabled.
	$( '#p-personal h3, #header-navigation .mw-portlet h3, #p-toolbox h3' ).on( 'click', function( e ) {
		if ( $( window ).width() > 760 ) {
			// Check if it's already open so we don't open it again
			if ( $( this ).next( '.dropdown' ).is( ':visible' ) ) {
				var wasOpen = true;
			}
			closeOpen(); // close all open ones, including this one
			e.stopPropagation(); // stop hiding it!
			if ( !wasOpen ) {
				$( this ).next( '.dropdown' ).slideDown( 200 );
			}
		}
	} );
	$( document ).click( function( e ) {
		if ( $( window ).width() > 760 ) {
			if ( $( e.target ).closest( '#p-personal, #header-navigation .mw-portlet, #p-toolbox, #p-actions' ).length > 0 ) {
				// Clicked inside an open menu; don't close anything
			} else {
				closeOpen();
			}
		}
	} );
} );

function closeOpen( e ) {
	// Close all dropdowns
	$( '#p-personal, #header-navigation .mw-portlet, #p-toolbox, #p-actions' ).children( '.dropdown' ).each( function() {
		if ( $( window ).width() > 760 ) {
			if ( $( this ).is( ':visible' ) ) {
				// .closest() doesn't work here like it does above...
				$( this ).slideUp( 200 );
			}
		}
	} );
};

/* Expanding menus (footer), p-actions */

$( function() {
	$( '#footer-navigation h3, #p-actions h3' ).on( 'click', function() {
		$( this ).next( 'div' ).slideToggle( 300 );
		$( this ).parent().toggleClass( 'visible', 300, 'slide' );
	} );

	$( '#footer-navigation h3, #p-actions h3' ).next( 'div' ).hide();
} );
