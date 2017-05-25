( function( $ ) {
	$( 'nav.tabs a' ).click( function( e ) {
		e.preventDefault();

		$( this ).parent().addClass( 'current' );
		$( this ).parent().siblings().removeClass( 'current' );

		var tab = $( this ).attr( 'href' );
		window.location.hash = tab;

		$( '.tab-content' ).not( tab ).removeClass( 'current' );
		$( tab ).addClass( 'current' );
	});

	var hash = window.location.hash;
	if( hash ) {
		$( 'nav.tabs a[href="' + hash + '"]' ).trigger( 'click' );
	}
}) ( jQuery );