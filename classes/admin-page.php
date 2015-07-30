<?php
/**
 * Your Shortcodes - Admin Page
 *
 * @package
 * @author Sujin 수진 Choi
 * @version 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Your_Shortcodes extends WP_Admin_Page {
	function metabox_shortcode_information() {
		?>
		<script>
			jQuery( document ).ready( function($) {
				var data = {
					action: 'YOURSC_Ajax'
				};

				$.post( '<?php bloginfo('home') ?>/?shortcode_finder=shortcode_finder', null, function( response ) {
					$( response ).appendTo( $( '#shortcode_information.postbox' ) );
					YOURSC_launch();
				});
			});
		</script>
		<?php
	}
}
