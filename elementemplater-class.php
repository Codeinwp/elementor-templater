<?php

class ElemenTemplater {

	/**
	 * A reference to an instance of this class.
	 */
	private static $instance;

	/**
	 * Returns an instance of this class.
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new ElemenTemplater();
		}

		return self::$instance;
	}

	/**
	 * Initializes the plugin by setting filters and administration functions.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'elementemplater_load_plugin_textdomain' ) );
		add_action( 'init', array( $this, 'load_composer_lib' ), 9 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 998 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9999 );

		if ( ! class_exists( 'Ti_Upsell_Notice_Manager' ) ) {
			require ET_PATH . 'inc/class-ti-upsell-notice-manager.php';
			add_action( 'init', array( Ti_Upsell_Notice_Manager::instance(), 'init' ) );
		}
	}

	public function elementemplater_load_plugin_textdomain() {
		load_plugin_textdomain( 'elementor-templater' );
	}

	/**
	 * The base feature of this plugin is under a Composer library which we have to instantiate here.
	 */
	public function load_composer_lib() {
		if ( class_exists( '\ThemeIsle\FullWidthTemplates' ) ) {
			\ThemeIsle\FullWidthTemplates::instance();
		}
	}

	/**
	 * Enqueue Custom CSS - theme agnostic.
	 *
	 * @since   1.0.1
	 * @return  void
	 */
	public function enqueue_styles() {
		if ( is_page_template( 'templates/builder-fullwidth.php' ) ) {
			wp_register_style( 'builder-fullwidth-style', plugins_url( 'assets/custom.css', __FILE__ ) );
			wp_enqueue_style( 'builder-fullwidth-style' );
		}
	}

	public function enqueue_scripts() {
		if ( is_page_template( 'templates/builder-fullwidth.php' ) ) {
			wp_enqueue_script( 'builder-fullwidth-js', plugins_url( 'assets/custom.js', __FILE__ ), array( 'jquery' ), '', true );
		}
	}
}

add_action( 'plugins_loaded', array( 'ElemenTemplater', 'get_instance' ) );
