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
	public $version = '2.2.3';

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
		defined( 'METADATA_VIEWER_PLUGIN_VERSION' ) || define( 'METADATA_VIEWER_PLUGIN_VERSION', $this->version );
		defined( 'METADATA_VIEWER_DIR' ) || define( 'METADATA_VIEWER_DIR', dirname( METADATA_VIEWER_FILE ) );
		defined( 'METADATA_VIEWER_INC_DIR' ) || define( 'METADATA_VIEWER_INC_DIR', METADATA_VIEWER_DIR . '/includes' );
		defined( 'METADATA_VIEWER_TEMPLATE_DIR' ) || define( 'METADATA_VIEWER_TEMPLATE_DIR', METADATA_VIEWER_DIR . '/templates' );
		defined( 'METADATA_VIEWER_PLUGIN_ASSET' ) || define( 'METADATA_VIEWER_PLUGIN_ASSET', plugins_url( 'assets', METADATA_VIEWER_FILE ) );

		// give a way to turn off loading styles and scripts from parent theme
		defined( 'METADATA_VIEWER_LOAD_STYLE' ) || define( 'METADATA_VIEWER_LOAD_STYLE', true );
		defined( 'METADATA_VIEWER_LOAD_SCRIPTS' ) || define( 'METADATA_VIEWER_LOAD_SCRIPTS', true );
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
		$this->container['taxonomy_meta_data']  = new TaxonomyMetaData();
		$this->container['user_meta_data']      = new UserMetaData();
		$this->container['comment_meta_data']   = new CommentMetaData();
		$this->container['woo_order_meta_data'] = new OrderMetaData();

		if ( function_exists( 'dokan' ) ) {
			$this->container['dokan_meta_data'] = new DokanMetaData();
		}
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

	/**
	 * Get the template file path to require or include.
	 *
	 * @param string $name
	 * @return string
	 */
	public function get_template_path( $name ) {
		$template = untrailingslashit( METADATA_VIEWER_TEMPLATE_DIR ) . '/' . untrailingslashit( $name );

		return apply_filters( 'metadata_viewer_template', $template, $name );
	}

	/**
	 * Get templates passing attributes and including the file.
	 *
	 * @param mixed $template_name
	 * @param array $args          (default: array())
	 *
	 * @return void
	 */
	public function get_template( $template_name, $args = array() ) {
		if ( $args && is_array( $args ) ) {
            extract( $args ); // phpcs:ignore
		}

		$template_path = $this->get_template_path( $template_name );

		if ( ! file_exists( $template_path ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', esc_html( $template_path ) ), esc_html( METADATA_VIEWER_PLUGIN_VERSION ) );

			return;
		}

		do_action( 'metadata_viewer_before_template_part', $template_name, $args );

		// Template path is validated above via file_exists; safe to include.
		include $template_path; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable

		do_action( 'metadata_viewer_after_template_part', $template_name, $args );
	}
}
