
/* Popout menus (header) (FIX THIS MESS) */

$( function() {
	$( '#personal-menu-toggle' ).on( 'click', function( e ) {
		if ( $( window ).width() <= 760 ) {
			$( '#p-personal, #menus-cover' ).fadeToggle( 300 );
		}
	} );
	$( '#main-menu-toggle' ).on( 'click', function( e ) {
		if ( $( window ).width() <= 760 ) {
			$( '#header-navigation .navigation, #menus-cover' ).fadeToggle( 300 );
		}
	} );
	$( '#tools-menu-toggle' ).on( 'click', function( e ) {
		if ( $( window ).width() <= 760 ) {
			$( '#header-navigation .navigation-tools, #menus-cover' ).fadeToggle( 400 );
		}
	} );

	$( document ).click( function( e ) {
		if ( $( window ).width() <= 760 ) {
			if ( $( e.target ).closest( '#personal-menu-toggle, #main-menu-toggle, #tools-menu-toggle, #p-personal, #header-navigation .navigation, #header-navigation .navigation-tools' ).length > 0 ) {
				// Clicked inside an open menu; don't close anything
			} else {
				$( '#menus-cover' ).fadeOut( 200 );
				$( '#header-navigation .navigation' ).fadeOut( 300 );
				$( '#header-navigation .navigation-tools' ).fadeOut( 300 );
				$( '#p-personal' ).fadeOut( 300 );
			}

			if ( $( e.target ).closest( '#p-actions' ).length > 0 ) {
				// Special case for actions
				// Should maybe replace with handling like the above later?
			} else {
				$( '#p-actions .dropdown' ).slideUp( 300 );
			}
		}
	} );
} );
