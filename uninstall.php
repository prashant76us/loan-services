<?php
/**
 * Uninstall Loan Services Plugin
 * 
 * @package LoanServices
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Check if user has permission to uninstall
if (!current_user_can('activate_plugins')) {
    return;
}

global $wpdb;

// Define all plugin tables
$tables = array(
    $wpdb->prefix . 'loan_applications',
    $wpdb->prefix . 'loan_types',
    $wpdb->prefix . 'loan_documents'
);

// Drop tables
foreach ($tables as $table) {
    $wpdb->query("DROP TABLE IF EXISTS $table");
}

// Delete plugin options
$options = array(
    'ls_general_settings',
    'ls_email_settings',
    'ls_payment_settings',
    'ls_api_settings',
    'ls_version'
);

foreach ($options as $option) {
    delete_option($option);
}

// Clear any scheduled hooks
wp_clear_scheduled_hook('ls_daily_cleanup');

// Remove capabilities
$roles = array('administrator', 'editor', 'author');
foreach ($roles as $role_name) {
    $role = get_role($role_name);
    if ($role) {
        $role->remove_cap('manage_loan_applications');
        $role->remove_cap('view_loan_applications');
    }
}

// Clean up any uploaded files
$upload_dir = wp_upload_dir();
$plugin_upload_dir = $upload_dir['basedir'] . '/loan-services';
if (is_dir($plugin_upload_dir)) {
    array_map('unlink', glob("$plugin_upload_dir/*.*"));
    rmdir($plugin_upload_dir);
}