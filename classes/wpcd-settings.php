<?php

if ( ! class_exists( 'WPCD_Settings' ) ) {

  /**
   * Handles plugin settings and user profile meta fields
   */
  class WPCD_Settings extends WPCD_Module {
    protected $settings;
    protected static $default_settings;
    protected static $readable_properties  = array( 'settings' );
    protected static $writeable_properties = array( 'settings' );

    const REQUIRED_CAPABILITY = 'administrator';


    /*
     * General methods
     */

    /**
     * Constructor
     *
     * @mvc Controller
     */
    protected function __construct() {
      $this->register_hook_callbacks();
    }

    /**
     * Public setter for protected variables
     *
     * Updates settings outside of the Settings API or other subsystems
     *
     * @mvc Controller
     *
     * @param string $variable @param array $value This will be merged
     * with WPCD_Settings->settings, so it should mimic the structure
     * of the WPCD_Settings::$default_settings. It only needs the
     * contain the values that will change, though. See
     * WordPress_Content_Dialog->upgrade() for an example.
     */
    public function __set( $variable, $value ) {
      // Note: WPCD_Module::__set() is automatically called before this

      if ( $variable != 'settings' ) {
	return;
      }

      $this->settings = self::validate_settings( $value );
      update_option( 'wpcd_settings', $this->settings );
    }

    /**
     * Register callbacks for actions and filters
     *
     * @mvc Controller
     */
    public function register_hook_callbacks() {
      add_action( 'admin_menu',               __CLASS__ . '::register_settings_pages' );
      add_action( 'show_user_profile',        __CLASS__ . '::add_user_fields' );
      add_action( 'edit_user_profile',        __CLASS__ . '::add_user_fields' );
      add_action( 'personal_options_update',  __CLASS__ . '::save_user_fields' );
      add_action( 'edit_user_profile_update', __CLASS__ . '::save_user_fields' );

      add_action( 'init',                     array( $this, 'init' ) );
      add_action( 'admin_init',               array( $this, 'register_settings' ) );

      add_filter(
		 'plugin_action_links_' . plugin_basename( dirname( __DIR__ ) ) . '/bootstrap.php',
		 __CLASS__ . '::add_plugin_action_links'
		 );
    }

    /**
     * Prepares site to use the plugin during activation
     *
     * @mvc Controller
     *
     * @param bool $network_wide
     */
    public function activate( $network_wide ) {
    }

    /**
     * Rolls back activation procedures when de-activating the plugin
     *
     * @mvc Controller
     */
    public function deactivate() {
    }

    /**
     * Initializes variables
     *
     * @mvc Controller
     */
    public function init() {
      self::$default_settings = self::get_default_settings();
      $this->settings         = self::get_settings();
    }

    /**
     * Executes the logic of upgrading from specific older versions of
     * the plugin to the current version
     *
     * @mvc Model
     *
     * @param string $db_version
     */
    public function upgrade( $db_version = 0 ) {
      /*
	if( version_compare( $db_version, 'x.y.z', '<' ) )
	{
	// Do stuff
	}
      */
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
      // Note: __set() calls validate_settings(), so settings are never invalid

      return true;
    }


    /*
     * Plugin Settings
     */

    /**
     * Establishes initial values for all settings
     *
     * @mvc Model
     *
     * @return array
     */
    protected static function get_default_settings() {
      $basic = array(
		     'field-url' => '',
		     'field-type' => 'prettyphoto'
		     );

      return array(
		   'db-version' => '0',
		   'main'      => $main
		   );
    }

    /**
     * Retrieves all of the settings from the database
     *
     * @mvc Model
     *
     * @return array
     */
    protected static function get_settings() {
      $settings = shortcode_atts(
				 self::$default_settings,
				 get_option( 'wpcd_settings', array() )
				 );

      return $settings;
    }

    /**
     * Adds links to the plugin's action link section on the Plugins page
     *
     * @mvc Model
     *
     * @param array $links The links currently mapped to the plugin
     * @return array
     */
    public static function add_plugin_action_links( $links ) {
      $helpLink = '<a href="https://github.com/mcascardi/wp-content-dialog/wiki">Help</a>';
      array_unshift( $links, $helpLink);
      array_unshift( $links, '<a href="options-general.php?page=' . 'wpps_settings">Settings</a>' );
      return $links;
    }

    /**
     * Adds pages to the Admin Panel menu
     *
     * @mvc Controller
     */
    public static function register_settings_pages() {
      add_submenu_page(
		       'options-general.php',
		       WPCD_NAME . ' Settings',
		       WPCD_NAME,
		       self::REQUIRED_CAPABILITY,
		       'wpcd_settings',
		       __CLASS__ . '::markup_settings_page'
		       );
    }

    /**
     * Creates the markup for the Settings page
     *
     * @mvc Controller
     */
    public static function markup_settings_page() {
      if ( current_user_can( self::REQUIRED_CAPABILITY ) ) {
	echo self::render_template( 'wpcd-settings/page-settings.php' );
      } else {
	wp_die( 'Access denied.' );
      }
    }

    /**
     * Registers settings sections, fields and settings
     *
     * @mvc Controller
     */
    public function register_settings() {
      /*
       * Main Section
       */
      add_settings_section(
			   'wpcd_section-main',
			   'Main Settings',
			   __CLASS__ . '::markup_section_headers',
			   'wpcd_settings'
			   );

      add_settings_field(
			 'wpcd_field-url',
			 'Dialog URL:',
			 array( $this, 'markup_fields' ),
			 'wpcd_settings',
			 'wpcd_section-main',
			 array( 'label_for' => 'wpcd_field-url' )
			 );

      
      add_settings_field(
			 'wpcd_field-type',
			 'Overlay box type:',
			 array( $this, 'markup_fields' ),
			 'wpcd_settings',
			 'wpcd_section-main',
			 array( 'label_for' => 'wpcd_field-type' )
			 );


      // The settings container
      register_setting(
		       'wpcd_settings',
		       'wpcd_settings',
		       array( $this, 'validate_settings' )
		       );
    }

    /**
     * Adds the section introduction text to the Settings page
     *
     * @mvc Controller
     *
     * @param array $section
     */
    public static function markup_section_headers( $section ) {
      echo self::render_template(
				 'wpcd-settings/page-settings-section-headers.php', 
				 array( 'section' => $section ), 'always' 
				 );
    }
    
    /**
     * Delivers the markup for settings fields
     *
     * @mvc Controller
     *
     * @param array $field
     */
    public function markup_fields( $field ) {
      switch ( $field['label_for'] ) {
      case 'wpcd_field-url':
	// Do any extra processing here
	break;
      
      case 'wpcd_field-type':
	// Do any extra processing here
	break;
      }
      
      echo self::render_template(
				 'wpcd-settings/page-settings-fields.php', 
				 array('settings' => $this->settings, 'field' => $field), 
				 'always'
				 );
    }

    /**
     * Validates submitted setting values before they get saved to the
     * database. Invalid data will be overwritten with defaults.
     *
     * @mvc Model
     *
     * @param array $new_settings
     * @return array
     */
    public function validate_settings( $new_settings ) {
      $new_settings = shortcode_atts( $this->settings, $new_settings );

      if ( ! is_string( $new_settings['db-version'] ) ) {
	$new_settings['db-version'] = WordPress_Content_Dialog::VERSION;
      }


      /*
       * Main Settings
       */
      $urlFieldError = "Dialog URL field must be a valid URL";
      $urlPattern = '%^((https?://)|(www\.))([a-z0-9-].?)+(:[0-9]+)?(/.*)?$%i';
      if ( preg_match($urlPattern, $new_settings['main']['field-url']) !== 1 ) {
	WordPress_Content_Dialog::$notices->enqueue($urlFieldError, 'error' );
	$new_settings['main']['field-url'] = self::$default_settings['main']['field-url'];
      }

      return $new_settings;
    }
  } // end WPCD_Settings
}
