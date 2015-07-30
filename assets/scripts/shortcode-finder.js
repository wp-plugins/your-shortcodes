function YOURSC_launch() {
	jQuery( '.shortcode_finder h3' ).click( function() {
		var id = jQuery(this).attr( 'data-id' );
		var arrow = jQuery(this).find('span').hasClass( 'dashicons-arrow-down' );

		jQuery(this).find('span').removeClass( 'dashicons-arrow-down' ).removeClass( 'dashicons-arrow-right' );

		jQuery( '.shortcode_info[data-id="' + id + '"]' ).toggle();

		if ( arrow ) {
			jQuery(this).find('span').addClass( 'dashicons-arrow-right' );
		} else {
			jQuery(this).find('span').addClass( 'dashicons-arrow-down' );
		}
	});

	jQuery( '.shortcode_finder h4' ).click( function() {
		jQuery(this).next().toggle();
		var arrow = jQuery(this).find('span').hasClass( 'dashicons-arrow-down' );
		jQuery(this).find('span').removeClass( 'dashicons-arrow-down' ).removeClass( 'dashicons-arrow-right' );

		if ( arrow ) {
			jQuery(this).find('span').addClass( 'dashicons-arrow-right' );
		} else {
			jQuery(this).find('span').addClass( 'dashicons-arrow-down' );
		}
	});
}
