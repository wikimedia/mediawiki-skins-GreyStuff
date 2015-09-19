/* Expanding menus (footer only so far) */

$( function() {
	$( '#footer-navigation h3' ).on( 'click', function( e ) {
		if ( $( window ).width() <= 760 ) {
			$( this ).next( 'div' ).slideToggle( 300 );
			$( this ).parent().toggleClass( 'visible', 300, 'slide' );
		}
	} );
} );
