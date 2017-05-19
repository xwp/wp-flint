( function( $ ) {
	// your code
	$( '.roles .join' ).click( function() {
		$( this ).next( '.row' ).submit();
	}).hover(
		function() {
			$( this ).prevAll( '.avatar.empty' ).addClass( 'request' );
		},
		function() {
			$( this ).prevAll( '.avatar.empty' ).removeClass( 'request' );
		}
	);
}) ( jQuery );