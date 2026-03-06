<?php
class LS_Admin {
    
    private $plugin_name;
    private $version;
    
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, LS_PLUGIN_URL . 'admin/css/loan-services-admin.css', array(), $this->version, 'all');
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, LS_PLUGIN_URL . 'admin/js/loan-services-admin.js', array('jquery'), $this->version, false);
        wp_localize_script($this->plugin_name, 'ls_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ls_admin_nonce')
        ));
    }
    
    public function add_admin_menu() {
        add_menu_page(
            __('Loan Services', 'loan-services'),
            __('Loan Services', 'loan-services'),
            'manage_options',
            'loan-services',
            array($this, 'display_dashboard'),
            'dashicons-chart-area',
            30
        );
        
        add_submenu_page(
            'loan-services',
            __('Dashboard', 'loan-services'),
            __('Dashboard', 'loan-services'),
            'manage_options',
            'loan-services',
            array($this, 'display_dashboard')
        );
        
        add_submenu_page(
            'loan-services',
            __('Loan Applications', 'loan-services'),
            __('Applications', 'loan-services'),
            'manage_options',
            'loan-services-applications',
            array($this, 'display_applications')
        );
        
        add_submenu_page(
            'loan-services',
            __('Loan Types', 'loan-services'),
            __('Loan Types', 'loan-services'),
            'manage_options',
            'loan-services-types',
            array($this, 'display_loan_types')
        );
        
        add_submenu_page(
            'loan-services',
            __('Settings', 'loan-services'),
            __('Settings', 'loan-services'),
            'manage_options',
            'loan-services-settings',
            array($this, 'display_settings')
        );
    }
    
    public function display_dashboard() {
        include_once LS_PLUGIN_PATH . 'admin/partials/loan-services-admin-dashboard.php';
    }
    
    public function display_applications() {
        include_once LS_PLUGIN_PATH . 'admin/partials/loan-services-admin-loan-applications.php';
    }
    
    public function display_loan_types() {
        include_once LS_PLUGIN_PATH . 'admin/partials/loan-services-admin-loan-types.php';
    }
    
    public function display_settings() {
        include_once LS_PLUGIN_PATH . 'admin/partials/loan-services-admin-settings.php';
    }
}