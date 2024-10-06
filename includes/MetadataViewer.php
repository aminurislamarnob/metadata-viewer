<?php

namespace WeLabs\MetadataViewer;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * MetadataViewer class
 *
 * @class MetadataViewer The class that holds the entire MetadataViewer plugin
 */
final class MetadataViewer {

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	public $version = '2.0.0';

	/**
	 * Instance of self
	 *
	 * @var MetadataViewer
	 */
	private static $instance = null;

	/**
	 * Holds various class instances
	 *
	 * @since 2.6.10
	 *
	 * @var array
	 */
	private $container = array();

	/**
	 * Constructor for the MetadataViewer class
	 *
	 * Sets up all the appropriate hooks and actions
	 * within our plugin.
	 */
	private function __construct() {
		$this->define_constants();

		register_activation_hook( METADATA_VIEWER_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( METADATA_VIEWER_FILE, array( $this, 'deactivate' ) );

		add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
		add_action( 'woocommerce_flush_rewrite_rules', array( $this, 'flush_rewrite_rules' ) );
	}

	/**
	 * Initializes the MetadataViewer() class
	 *
	 * Checks for an existing MetadataViewer instance
	 * and if it doesn't find one, creates it.
	 */
	public static function init() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Magic getter to bypass referencing objects
	 *
	 * @since 2.6.10
	 *
	 * @param string $prop
	 *
	 * @return Class Instance
	 */
	public function __get( $prop ) {
		if ( array_key_exists( $prop, $this->container ) ) {
			return $this->container[ $prop ];
		}
	}

	/**
	 * Placeholder for activation function
	 *
	 * Nothing being called here yet.
	 */
	public function activate() {
		// On activation
	}

	/**
	 * Flush rewrite rules after metadata_viewer is activated or woocommerce is activated
	 *
	 * @since 3.2.8
	 */
	public function flush_rewrite_rules() {
		// fix rewrite rules
		flush_rewrite_rules();
	}

	/**
	 * Placeholder for deactivation function
	 *
	 * Nothing being called here yet.
	 */
	public function deactivate() {     }

	/**
	 * Define all constants
	 *
	 * @return void
	 */
	public function define_constants() {
		$this->define( 'METADATA_VIEWER_PLUGIN_VERSION', $this->version );
		$this->define( 'METADATA_VIEWER_DIR', dirname( METADATA_VIEWER_FILE ) );
		$this->define( 'METADATA_VIEWER_INC_DIR', METADATA_VIEWER_DIR . '/includes' );
		$this->define( 'METADATA_VIEWER_TEMPLATE_DIR', METADATA_VIEWER_DIR . '/templates' );
		$this->define( 'METADATA_VIEWER_PLUGIN_ASSET', plugins_url( 'assets', METADATA_VIEWER_FILE ) );

		// give a way to turn off loading styles and scripts from parent theme
		$this->define( 'METADATA_VIEWER_LOAD_STYLE', true );
		$this->define( 'METADATA_VIEWER_LOAD_SCRIPTS', true );
	}

	/**
	 * Define constant if not already defined
	 *
	 * @param string      $name
	 * @param string|bool $value
	 *
	 * @return void
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Load the plugin after WP User Frontend is loaded
	 *
	 * @return void
	 */
	public function init_plugin() {
		$this->includes();
		$this->init_hooks();

		do_action( 'metadata_viewer_loaded' );
	}

	/**
	 * Initialize the actions
	 *
	 * @return void
	 */
	public function init_hooks() {
		// initialize the classes
		add_action( 'init', array( $this, 'init_classes' ), 4 );
		add_action( 'plugins_loaded', array( $this, 'after_plugins_loaded' ) );
	}

	/**
	 * Include all the required files
	 *
	 * @return void
	 */
	public function includes() {
		// include_once STUB_PLUGIN_DIR . '/functions.php';
	}

	/**
	 * Init all the classes
	 *
	 * @return void
	 */
	public function init_classes() {
		$this->container['scripts']             = new Assets();
		$this->container['helpers']             = new Helpers();
		$this->container['post_meta_data']      = new PostMetaData();
		$this->container['user_meta_data']      = new UserMetaData();
		$this->container['woo_order_meta_data'] = new OrderMetaData();
	}

	/**
	 * Executed after all plugins are loaded
	 *
	 * At this point metadata_viewer Pro is loaded
	 *
	 * @since 2.8.7
	 *
	 * @return void
	 */
	public function after_plugins_loaded() {
		// Initiate background processes and other tasks
	}
}
