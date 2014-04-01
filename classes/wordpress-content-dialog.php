<?php

if ( ! class_exists( 'WordPress_Content_Dialog' ) ) {

  /**
   * Main / front controller class
   *
   * WordPress_Content_Dialog is an object-oriented/MVC WordPress plugin to make installing Content Dialogs on your website easy
   */
  class WordPress_Content_Dialog extends WPCD_Module {
    // Needs to be static so static methods can call enqueue
    // notices. Needs to be public so other modules can enqueue
    // notices.
    public static $notices;                           
    
    // These should really be constants, but PHP doesn't allow
    // class constants to be arrays
    protected static $readable_properties  = array(); 
    protected static $writeable_properties = array();
    protected $modules;

    const VERSION    = '0.1';
    const PREFIX     = 'wpcd_';
    const DEBUG_MODE = false;


    /*
     * Magic methods
     */

    /**
     * Constructor
     *
     * @mvc Controller
     */
    protected function __construct() {
      $this->register_hook_callbacks();
      $this->modules = array('WPCD_Settings'    => WPCD_Settings::get_instance());
    }


    /*
     * Static methods
     */

    /**
     * Enqueues CSS, JavaScript, etc
     *
     * @mvc Controller
     */
    public static function load_resources() {
      wp_register_script(
			 self::PREFIX . 'wordpress-content-dialog',
			 plugins_url(
				     'javascript/wordpress-content-dialog.js',
				     dirname( __FILE__ ) 
				     ),
			 array( 'jquery' ),
			 self::VERSION,
			 true
			 );

      wp_register_style(
			self::PREFIX . 'admin',
			plugins_url( 'css/admin.css', dirname( __FILE__ ) ),
			array(),
			self::VERSION,
			'all'
			);

      if ( is_admin() ) {
	wp_enqueue_style( self::PREFIX . 'admin' );
      } else {
	wp_enqueue_script( self::PREFIX . 'wordpress-content-dialog' );
      }
    }

    /**
     * Clears caches of content generated by caching plugins like WP Super Cache
     *
     * @mvc Model
     */
    protected static function clear_caching_plugins() {
      // WP Super Cache
      if ( function_exists( 'wp_cache_clear_cache' ) ) {
	wp_cache_clear_cache();
      }

      // W3 Total Cache
      if ( class_exists( 'W3_Plugin_TotalCacheAdmin' ) ) {
	$w3_total_cache = w3_instance( 'W3_Plugin_TotalCacheAdmin' );

	if ( method_exists( $w3_total_cache, 'flush_all' ) ) {
	  $w3_total_cache->flush_all();
	}
      }
    }


    /*
     * Instance methods
     */

    /**
     * Prepares sites to use the plugin during single or network-wide activation
     *
     * @mvc Controller
     *
     * @param bool $network_wide
     */
    public function activate( $network_wide ) {
      global $wpdb;

      if ( function_exists( 'is_multisite' ) && is_multisite() ) {
	if ( $network_wide ) {
	  $blogs = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

	  foreach ( $blogs as $blog ) {
	    switch_to_blog( $blog );
	    $this->single_activate( $network_wide );
	  }

	  restore_current_blog();
	} else {
	  $this->single_activate( $network_wide );
	}
      } else {
	$this->single_activate( $network_wide );
      }
    }

    /**
     * Runs activation code on a new WPMS site when it's created
     *
     * @mvc Controller
     *
     * @param int $blog_id
     */
    public function activate_new_site( $blog_id ) {
      switch_to_blog( $blog_id );
      $this->single_activate( true );
      restore_current_blog();
    }

    /**
     * Prepares a single blog to use the plugin
     *
     * @mvc Controller
     *
     * @param bool $network_wide
     */
    protected function single_activate( $network_wide ) {
      foreach ( $this->modules as $module ) {
	$module->activate( $network_wide );
      }
      flush_rewrite_rules();
    }

    /**
     * Rolls back activation procedures when de-activating the plugin
     *
     * @mvc Controller
     */
    public function deactivate() {
      foreach ( $this->modules as $module ) {
	$module->deactivate();
      }
      flush_rewrite_rules();
    }

    /**
     * Register callbacks for actions and filters
     *
     * @mvc Controller
     */
    public function register_hook_callbacks() {
      add_action( 'wpmu_new_blog',         __CLASS__ . '::activate_new_site' );
      add_action( 'wp_enqueue_scripts',    __CLASS__ . '::load_resources' );
      add_action( 'admin_enqueue_scripts', __CLASS__ . '::load_resources' );

      add_action( 'init',                  array( $this, 'init' ) );
      add_action( 'init',                  array( $this, 'upgrade' ), 11 );
    }

    /**
     * Initializes variables
     *
     * @mvc Controller
     */
    public function init() {
      self::$notices = IDAdminNotices::getSingleton();
      if ( self::DEBUG_MODE ) {
	self::$notices->debugMode = true;
      }

      try {
	$instance_example = new WPCD_Instance_Class( 'Instance example', '42' );
	//self::$notices->enqueue( $instance_example->foo .' '. $instance_example->bar );
      } catch ( Exception $exception ) {
	self::$notices->enqueue( __METHOD__ . ' error: ' . $exception->getMessage(), 'error' );
      }
    }

    /**
     * Checks if the plugin was recently updated and upgrades if necessary
     *
     * @mvc Controller
     *
     * @param string $db_version
     */
    public function upgrade( $db_version = 0 ) {
      if ( version_compare( $this->modules['WPCD_Settings']->settings['db-version'], self::VERSION, '==' ) ) {
	return;
      }

      foreach ( $this->modules as $module ) {
	$module->upgrade( $this->modules['WPCD_Settings']->settings['db-version'] );
      }

      $this->modules['WPCD_Settings']->settings = array( 'db-version' => self::VERSION );
      self::clear_caching_plugins();
    }

    /**
     * Checks that the object is in a correct state
     *
     * @mvc Model
     *
     * @param string $property An individual property to check, or 'all' to check all of them
     * @return bool
     */
    protected function is_valid( $property = 'all' ) {
      return true;
    }
  } // end WordPress_Content_Dialog
}
