<?php 

if ( ! class_exists( 'WPCD_Shortcode' ) ) {

  /**
   * Handles the shortcode creator
   */
  class WPCD_Shortcode extends WPCD_Module {
    protected $settings;
    
    function __construct() {
    }

    
    /**
     * Prepares sites to use the plugin during single or network-wide
     * activation
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
     * Register callbacks for actions and filters
     *
     * @mvc Controller
     */
    public function register_hook_callbacks() {
    }

    /**
     * Initializes variables
     *
     * @mvc Controller
     */
    public function init() {
    }

    /**
     * Checks if the plugin was recently updated and upgrades if necessary
     *
     * @mvc Controller
     *
     * @param string $db_version
     */
    public function upgrade( $db_version = 0 ) {
    }

    /**
     * Checks that the object is in a correct state
     *
     * @mvc Model
     *
     * @param string $property An individual property to check, or
     * 'all' to check all of them @return bool
     */
    protected function is_valid( $property = 'all' ) {
      return true;
    }
 
    
    function display() { 
      return WPCD_Core::getCtaCode(get_option('wpcd_settings'));
    }
  }
}