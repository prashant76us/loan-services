<?php
/**
 * The core plugin class
 *
 * @package    Loan_Services
 * @subpackage Loan_Services/includes
 */

class LS_Main {

    /**
     * The loader that's responsible for maintaining and registering all hooks.
     *
     * @since    1.0.0
     * @access   protected
     * @var      LS_Loader    $loader    Maintains and registers all hooks.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * @since    1.0.0
     */
    public function __construct() {
        $this->plugin_name = 'loan-services';
        $this->version = LS_VERSION;

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        // The class responsible for orchestrating the actions and filters
        if (class_exists('LS_Loader')) {
            $this->loader = new LS_Loader();
        } else {
            // Fallback if loader class is missing
            $this->loader = $this->create_fallback_loader();
        }
    }

    /**
     * Create a fallback loader if the main loader class is missing
     *
     * @since    1.0.0
     * @access   private
     */
    private function create_fallback_loader() {
        // Simple fallback loader
        return new class() {
            private $actions = array();
            private $filters = array();
            
            public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
                add_action($hook, array($component, $callback), $priority, $accepted_args);
            }
            
            public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
                add_filter($hook, array($component, $callback), $priority, $accepted_args);
            }
            
            public function run() {
                // Actions and filters are already added
            }
        };
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {
        if (class_exists('LS_i18n')) {
            $plugin_i18n = new LS_i18n();
            $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
        }
    }

    /**
     * Register all of the hooks related to the admin area functionality.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        if (class_exists('LS_Admin')) {
            $admin = new LS_Admin($this->get_plugin_name(), $this->get_version());

            $this->loader->add_action('admin_enqueue_scripts', $admin, 'enqueue_styles');
            $this->loader->add_action('admin_enqueue_scripts', $admin, 'enqueue_scripts');
            $this->loader->add_action('admin_menu', $admin, 'add_admin_menu');
        }
    }

    /**
     * Register all of the hooks related to the public-facing functionality.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        if (class_exists('LS_Public')) {
            $public = new LS_Public($this->get_plugin_name(), $this->get_version());

            $this->loader->add_action('wp_enqueue_scripts', $public, 'enqueue_styles');
            $this->loader->add_action('wp_enqueue_scripts', $public, 'enqueue_scripts');
            
            // Register shortcodes
            add_shortcode('loan_application_form', array($public, 'display_application_form'));
            add_shortcode('loan_calculator', array($public, 'display_loan_calculator'));
            add_shortcode('loan_types_list', array($public, 'display_loan_types'));
            add_shortcode('eligibility_checker', array($public, 'display_eligibility_checker'));
        }
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        if ($this->loader) {
            $this->loader->run();
        }
    }

    /**
     * The name of the plugin used to uniquely identify it.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks.
     *
     * @since     1.0.0
     * @return    LS_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
}