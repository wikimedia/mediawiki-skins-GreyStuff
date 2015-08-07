$( function() {
	// When the menus (or their respective little arrows, which are still
	// contained in the menu element) are clicked, show their contents.
	// CSS/LESS takes care of the users who don't have JS or have it disabled.
	$( '#p-personal h3, #header-navigation .navigation .mw-portlet h3, #p-toolbox h3' ).on( 'click', function( e ) {
		// Check if it's already open so we don't open it again
		if ( $( this ).next( '.dropdown' ).is( ':visible' ) ) {
			var wasOpen = true;
		}
		closeOpen(); // close all open ones, including this one
		e.stopPropagation(); // stop hiding it!
		if ( !wasOpen ) {
			$( this ).next( '.dropdown' ).fadeIn( 300 );
			$( this ).closest( 'h3' ).addClass( 'menu-down-arrow' );
		}
	} );
	$( document ).click( function( e ) {
		if ( $( e.target ).closest( '#p-personal, #header-navigation .navigation .mw-portlet, #p-toolbox' ).length > 0 ) {
			// Clicked inside an open menu; don't close anything
		} else {
			closeOpen();
		}
	} );
} );

function closeOpen( e ) {
	// Close all dropdowns
	$( '#p-personal, #header-navigation .navigation .mw-portlet, #p-toolbox' ).children( '.dropdown' ).each( function() {
		if ( $( this ).is( ':visible' ) ) {
			// .closest() doesn't work here like it does above...
			$( this ).parent().children( 'h3' ).removeClass( 'menu-down-arrow' );
			$( this ).fadeOut( 300 );
		}
	} );
};