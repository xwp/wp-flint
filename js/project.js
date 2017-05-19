( function( $ ) {
	$( 'nav.tabs  a' ).click( function( e ) {
		e.preventDefault();

		$( this ).parent().addClass( 'current' );
		$( this ).parent().siblings().removeClass( 'current' );

		var tab = $( this ).attr( 'href' );
		$( '.tab-content' ).not( tab ).removeClass( 'current' );
		$( tab ).addClass( 'current' );
	});
}) ( jQuery );