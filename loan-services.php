<?php
/**
 * Plugin Name: Loan Services
 * Plugin URI: https://yourwebsite.com/loan-services
 * Description: Comprehensive loan management system for financial institutions
 * Version: 1.0.0
 * Author: Prashant J,
 * Author URI: https://prashantj.info
 * License: GPL v2 or later
 * Text Domain: loan-services
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('LS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('LS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('LS_VERSION', '1.0.0');

/**
 * Include required files with error checking
 */
function ls_include_files() {
    $files = array(
        'includes/class-loan-services-db.php',
        'includes/class-loan-services-loader.php',
        'includes/class-loan-services-i18n.php',
        'includes/class-loan-services.php',
        'includes/class-loan-services-admin.php',
        'includes/class-loan-services-public.php',
        'includes/class-loan-services-ajax.php'
    );
    
    foreach ($files as $file) {
        $file_path = LS_PLUGIN_PATH . $file;
        if (file_exists($file_path)) {
            require_once $file_path;
        } else {
            error_log('Loan Services Plugin: Missing file - ' . $file_path);
            // Don't die in production, but show error in admin
            if (is_admin()) {
                add_action('admin_notices', function() use ($file) {
                    echo '<div class="error"><p>Loan Services Plugin: Missing required file - ' . esc_html($file) . '</p></div>';
                });
            }
        }
    }
}

// Include required files
ls_include_files();

/**
 * Initialize the plugin on plugins loaded
 */
function ls_init() {
    // Load text domain
    load_plugin_textdomain('loan-services', false, dirname(plugin_basename(__FILE__)) . '/languages');
    
    // Initialize database tables on activation
    register_activation_hook(__FILE__, array('LS_DB', 'create_tables'));
    
    // Initialize main class
    if (class_exists('LS_Main')) {
        $plugin = new LS_Main();
        $plugin->run();
    } else {
        add_action('admin_notices', function() {
            echo '<div class="error"><p>Loan Services Plugin: Main class not found. Please check file permissions.</p></div>';
        });
    }
}
add_action('plugins_loaded', 'ls_init');

/**
 * Plugin activation hook
 */
function ls_activate() {
    // Check WordPress version
    if (version_compare(get_bloginfo('version'), '5.0', '<')) {
        wp_die(__('This plugin requires WordPress version 5.0 or higher.', 'loan-services'));
    }
    
    // Create database tables
    if (class_exists('LS_DB')) {
        LS_DB::create_tables();
    }
    
    // Set default options
    add_option('ls_version', LS_VERSION);
    
    // Clear permalinks
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'ls_activate');

/**
 * Plugin deactivation hook
 */
function ls_deactivate() {
    // Clear scheduled hooks
    wp_clear_scheduled_hook('ls_daily_cleanup');
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'ls_deactivate');

/**
 * Add settings link on plugins page
 */
function ls_add_settings_link($links) {
    $settings_link = '<a href="admin.php?page=loan-services-settings">' . __('Settings', 'loan-services') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'ls_add_settings_link');