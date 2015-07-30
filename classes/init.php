<?php
/**
 * Your Shortcodes - Initializing
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

class YOURSC_init {
	private static $__instance;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	function __construct() {
		# 활성화 훅
		register_activation_hook( YOURSC_PLUGIN_DIR . '/' . YOURSC_PLUGIN_FILE_NAME , array( $this, 'activate' ) );
		register_deactivation_hook( YOURSC_PLUGIN_DIR . '/' . YOURSC_PLUGIN_FILE_NAME , array( $this, 'deactivate' ) );

		# 텍스트도메인
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

 		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
 		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

 		# FrontEnd Ajax
		add_action( 'plugins_loaded', array( $this, 'ajax_result' ) );

		if ( is_admin() ) {
			$admin_page = new Your_Shortcodes( array(
				'dir_name' => YOURSC_PLUGIN_FILE_NAME,
				'callback' => 'callback_admin_page',
				'action_link_text' => 'See Your Shortcodes',
				'metabox' => array(
					'donation' => array(
						'position' => 'side',
						'template' => YOURSC_VIEW_DIR . '/admin.metabox.donation.php'
					),
					'Shortcode Information' => array(
						'position' => 'normal',
						'callback' => 'metabox_shortcode_information'
					),
				)
			) );
		}
	}

	function ajax_result() {
		if ( $_GET['shortcode_finder'] == 'shortcode_finder' && current_user_can( 'administrator' ) ) {
			add_filter( 'template_include', array( $this, 'get_contents' ), 99 );
		}
	}

	function get_contents() {
		echo '<div class="shortcode_finder-wrapper">';
		$admin_page = new YOURSC_Shortcode_Info();
		$admin_page->get_plugins();
		$admin_page->get_shortcodes();
		$admin_page->shortcode_information();
		echo '</div>';
	}

	function enqueue_scripts() {
		wp_enqueue_script( 'Shortcode Finder', YOURSC_ASSETS_URL . '/script-min/shortcode-finder-min.js', array( 'jquery' ), '1.0.1' );
		wp_enqueue_style( 'Shortcode Finder', YOURSC_ASSETS_URL . '/css/shortcode-finder.css' );
	}

	/**
	 * 텍스트도메인 로딩
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function load_plugin_textdomain() {
		$lang_dir = YOURSC_PLUGIN_NAME . '/languages';
		load_plugin_textdomain( YOURSC_TEXTDOMAIN, 'wp-content/plugins/' . $lang_dir, $lang_dir );
	}

	public function activate() {}
	public function deactivate() {}

	/**
	 * initialize
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function initialize(){
		YOURSC_init::getInstance();
	}

	/**
	 * getInstance
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function getInstance() {
		// check if instance is avaible
		if ( self::$__instance==null ) {
			// create new instance if not
			self::$__instance = new self();
		}
		return self::$__instance;
	}
}

