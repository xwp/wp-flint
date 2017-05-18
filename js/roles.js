( function( $ ) {
	// your code
	$( '.roles .join' ).click( function() {
		$( this ).next( '.row' ).submit();
	}).hover(
		function() {
			$( this ).prevAll( '.avatar.empty' ).addClass( 'apply' );
		},
		function() {
			$( this ).prevAll( '.avatar.empty' ).removeClass( 'apply' );
		}
	);
}) ( jQuery );