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
		add_action( 'admin_notices', array($this, 'simple_notice') );
		add_action( 'admin_init', array($this, 'elementemplater_dismiss_notice') );
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

	public function simple_notice() {

		global $current_user;
		$user_id        = $current_user->ID;
		$ignored_notice = get_user_meta( $user_id, 'elementemplater_ignore_neve_notice' );

		if ( ! empty( $ignored_notice ) ) {
			return;
		}

		$dismiss_button = sprintf(
			'<a href="%s" class="notice-dismiss" style="text-decoration:none;"></a>',
			'?elementemplater_ignore_notice=0'
		);

		$message = sprintf(
			esc_html__( 'Do you enjoy working with Elementor? %1$s %2$s', 'elementor-templater' ),
			sprintf( '<br>Check out <strong>Neve</strong>, our new <strong>FREE multipurpose theme</strong>. It\'s simple, fast and fully compatible with both Elementor and Gutenberg. See ' ),
			sprintf(
				'<a target="_blank" href="%1$s">%2$s</a>',
				'https://themeisle.com/demo/?theme=Neve',
				esc_html__( 'theme demo.', 'elementor-templater' )
			)
		);

		printf(
			'<div class="notice updated" style="position:relative;">%1$s<p>%2$s</p></div>',
			$dismiss_button,
			$message
		);
	}

	public function elementemplater_dismiss_notice() {
		global $current_user;
		$user_id = $current_user->ID;
		if ( isset( $_GET['elementemplater_ignore_notice'] ) && '0' == $_GET['elementemplater_ignore_notice'] ) {
			add_user_meta( $user_id, 'elementemplater_ignore_neve_notice', 'true', true );
		}
	}
}

add_action( 'plugins_loaded', array( 'ElemenTemplater', 'get_instance' ) );
