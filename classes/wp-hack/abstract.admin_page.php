<?php
/**
 * WP_Admin_Page Class
 *
 * @author Sujin 수진 Choi
 * @package wp-hacks
 * @version 1.0.4
 *
 */

if ( !defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

if ( !class_exists('WP_Admin_Page' ) ) {
	abstract class WP_Admin_Page {
		protected $position;
		protected $page_name;
		protected $capability;
		protected $callback;
		protected $key;
		protected $metabox;
		protected $action_link_text;

		protected $dir_name;
		protected $template;
		protected $url;

		public function __call( $name, $arguments ) {
			if ( $name === 'view_' . $this->callback ) {
				$this->save_setting();

				# Template
				if ( $this->metabox ) {
					$this->add_meta_box();
				}

				$this->page__header();
				if ( $this->callback ) {
					$this->{ $this->callback }();
				}
				$this->page__footer();

				return true;
			}

			if ( strpos( $name, 'metabox_' ) !== false ) {
				$key = substr( $name, 8 );
				if ( $this->metabox[$key]['callback'] ) {
					call_user_func( array( $this, $this->metabox[$key]['callback'] ) );
				}

				if ( $this->metabox[$key]['template'] )
					include_once( $this->metabox[$key]['template'] );
			}

			return false;
		}

		function __construct( $options = array() ) {
			extract( shortcode_atts( array(
				'position' => 'option',
				'name' => '',
				'cap' =>'activate_plugins',
				'callback' => 'view_callback',
				'dir_name' => false,
				'template' => false,
				'metabox' => false,
				'action_link_text' => 'Setting'
			), $options ) );

			$this->position = $position;
			$this->capability = $cap;
			$this->callback = $callback;
			$this->key = get_class( $this );
			$this->page_name = ( $name ) ? $name : ucwords( str_replace( '_', ' ', $this->key ) );
			$this->template = $template;
			$this->action_link_text = $action_link_text;

			// Metabox Setting
			if ( $metabox && is_array( $metabox ) ) {
				$metabox_ = array();

				foreach( $metabox as $key => $val ) {
					$key_ = str_replace( ' ', '_', strtolower( $key ) );
					$name_ = ucwords( str_replace( '_', ' ', $key_ ) );
					$position_ = ( $val['position'] ) ? $val['position'] : 'normal';
					$callback_ = ( $val['callback'] ) ? $val['callback'] : '';

					$metabox_[$key_] = array(
						'position' => $position_,
						'template' => $val['template'],
						'name' => $name_,
						'callback' => $callback_
					);
				}

				$this->metabox = $metabox_;
			}

			add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );

			if ( $dir_name ) {
				add_filter( 'plugin_action_links' , array( $this, 'plugin_action_link' ), 15, 2 );
				$this->dir_name = $dir_name;
			}

			switch ( $this->position ) {
				case 'option' :
					$this->url = admin_url( 'options-general.php?page=' . $this->key );
				break;
			}
		}

		protected function save_setting() {
			if( !$_POST || !wp_verify_nonce( $_POST['security'], $this->key ) ) return false;

			$this->show_message( 'Saved!' );
			return true;
		}

		function add_admin_menu() {
			switch ( $this->position ) {
				case 'option' :
					add_options_page( $this->page_name, $this->page_name, $this->capability, $this->key, array( $this, 'view_' . $this->callback ));
				break;
			}
		}

		function page__header() {
			printf( '<div class="wrap" id="%s">', $this->key );
				printf( '<h2>%s</h2>', $this->page_name );
				echo '<div class="clear"></div>';

			# Template
			if ( $this->template ) {
				include_once( $this->template );
			} else {
				printf( '<form id="form-%s" method="POST">', $this->key );
				printf( '<input type="hidden" name="security" value="%s" />', wp_create_nonce( $this->key ) );
				if ( $this->metabox ) {
					$this->show_metabox();
				}
			}
		}

		function page__footer() {
			echo '</div>';
		}

		/**
		 * Setting Link on Plugins Page
		 *
		 * @since 1.0.3
		 * @access public
		 */
		function plugin_action_link( $actions, $plugin_file ) {
			if ( $this->dir_name && strpos( $plugin_file, $this->dir_name ) !== false ) {
 				$actions[] = sprintf( '<a href="%s">%s</a>', $this->url, $this->action_link_text );
			}

			return $actions;
		}

		protected function show_message( $text, $class = 'updated' ) {
			printf( '<div id="message" class="%s"><p>%s</a></p></div>', $class, $text );
		}

		protected function add_meta_box() {
			foreach ( $this->metabox as $key => $metabox ) {
				add_meta_box(
					$key,
					$metabox['name'],
					array( $this, 'metabox_' . $key ),
					false,
					$metabox['position']
				);
			}
		}

		public function show_metabox() {
			wp_enqueue_script( 'postbox' );
			?>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="postbox-container-1" class="postbox-container inner-sidebar">
						<?php do_meta_boxes( false, 'side', false ); ?>
					</div>

					<div id="postbox-container-2" class="postbox-container meta-box-sortables">
						<?php do_meta_boxes( false, 'normal', false ); ?>
						<?php do_meta_boxes( false, 'advanced', false ); ?>
					</div>
				</div>
			</div>
			<script>
			jQuery( document ).ready( function($) {
				postboxes.add_postbox_toggles();
			});
			</script>
			<?php
		}

		public function is_setting_page() {
			$url = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			return ( $this->url == $url );
		}
	}
}

