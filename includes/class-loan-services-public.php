<?php
class LS_Public {
    
    private $plugin_name;
    private $version;
    
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, LS_PLUGIN_URL . 'public/css/loan-services-public.css', array(), $this->version, 'all');
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, LS_PLUGIN_URL . 'public/js/loan-services-public.js', array('jquery'), $this->version, false);
        wp_localize_script($this->plugin_name, 'ls_public', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ls_public_nonce')
        ));
    }
    
    public function display_application_form($atts) {
        ob_start();
        include LS_PLUGIN_PATH . 'public/partials/loan-application-form.php';
        return ob_get_clean();
    }
    
    public function display_loan_calculator($atts) {
        ob_start();
        include LS_PLUGIN_PATH . 'public/partials/loan-calculator.php';
        return ob_get_clean();
    }
    
    public function display_loan_types($atts) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'loan_types';
        $loan_types = $wpdb->get_results("SELECT * FROM $table_name WHERE is_active = 1");
        
        ob_start();
        include LS_PLUGIN_PATH . 'public/partials/loan-types.php';
        return ob_get_clean();
    }
    
    public function display_eligibility_checker($atts) {
        ob_start();
        include LS_PLUGIN_PATH . 'public/partials/loan-eligibility-checker.php';
        return ob_get_clean();
    }
}