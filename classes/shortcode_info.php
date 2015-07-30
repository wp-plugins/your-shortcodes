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

class YOURSC_Shortcode_Info {
	public $shortcodes = array();

	public $plugins = array();
	public $shortcode = null;
	public $shortcode_info = null;

	# Get Installed Plugins
	function get_plugins() {
		$plugins = get_plugins();

		foreach( $plugins as $key => $plugin ) {
			preg_match( "/(.+)\//", $key, $match );
			$this->plugins[] = array(
				'dir' => $match[1],
				'name' => $plugin['Name']
			);
		}
	}

	# Get Installed Shortcodes
	function get_shortcodes() {
		global $shortcode_tags;

		foreach( $shortcode_tags as $shortcode_key => $shortcode_tag ) {
			$this->shortcode = $shortcode_key;
			$this->shortcode_info = $shortcode_tag;

			$this->shortcodes[$this->shortcode] = array(
				'callback' => $this->shortcode_info
			);

			add_filter( 'shortcode_atts_' . $shortcode_key, array( $this, 'get_shortcode_att' ), 15, 3 );
			do_shortcode( "[{$shortcode_key}]" );
		}
	}

	function get_shortcode_att( $out, $pairs, $atts ) {
		$this->shortcodes[$this->shortcode] = array(
			'callback' => $this->shortcode_info,
			'out' => $out
		);

		return $out;
	}

	function shortcode_information() {
		global $shortcode_tags;
		$regex_function = "~%s\s*\(.*?\)\s*(\{.*?\})~six";
		$regex_shortcode = "~shortcode_atts\s*\(\s*(array\s*\(.*?\))~six";

		echo '<div class="shortcode_finder">';

		foreach( $this->shortcodes as $key => &$shortcode ) {
			if ( is_array( $shortcode['callback'] ) ) {
				$Reflection = new ReflectionClass( $shortcode['callback'][0] );
			} else {
				$Reflection = new ReflectionFunction( $shortcode['callback'] );
			}

			$shortcode['file_name'] = $Reflection->getFileName();
			# Match Callback with Plugins
			foreach( $this->plugins as $plugins ) {
				if ( ( strpos( $shortcode['file_name'], trim( $plugins['dir'] ) ) !== false ) ) {
					$shortcode['plugin'] = '<h4><span class="dashicons dashicons-arrow-down"></span> Plugin</h4><p class="value">' . $plugins['name'] . '</p>';
					break;
				}
			}

			if ( empty( $shortcode['out'] ) ) {
				$shortcode['out'] = "";

				$file_handle = fopen( $shortcode['file_name'], 'r' );
				$file_content = fread( $file_handle, filesize( $shortcode['file_name'] ) );
				fclose( $file_handle );

				$callback = ( is_array( $shortcode['callback'] ) ) ? $shortcode['callback'][1] : $shortcode['callback'];
				$regex = sprintf( $regex_function, $callback );

				preg_match_all($regex, $file_content, $matches );

				if ( !empty( $matches[1][0] ) ) {
					preg_match_all($regex_shortcode, $matches[1][0], $matches );

					if ( !empty( $matches[1][0] ) ) {
						$shortcode['out'] = eval( 'return ' . $matches[1][0] . ';' );
					}
				}
			}

			if ( in_array( $key, array( 'embed', 'wp_caption', 'caption', 'gallery', 'playlist', 'audio', 'video' ) ) ) {
				$shortcode['plugin'] = '<h4><span class="dashicons dashicons-arrow-down"></span> WP Core</h4><p class="value">This shortcode is provided by Wordpress.</p>';
			}

			require( YOURSC_VIEW_DIR . '/admin.metabox.information.php' );
		}

		echo '</div>';
	}
}
