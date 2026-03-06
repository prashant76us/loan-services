<?php
class LS_Ajax {
    
    public function __construct() {
        // Public AJAX actions
        add_action('wp_ajax_submit_loan_application', array($this, 'submit_loan_application'));
        add_action('wp_ajax_nopriv_submit_loan_application', array($this, 'submit_loan_application'));
        
        add_action('wp_ajax_calculate_loan_emi', array($this, 'calculate_loan_emi'));
        add_action('wp_ajax_nopriv_calculate_loan_emi', array($this, 'calculate_loan_emi'));
        
        // Admin AJAX actions
        add_action('wp_ajax_update_application_status', array($this, 'update_application_status'));
        add_action('wp_ajax_delete_application', array($this, 'delete_application'));
    }
    
    public function submit_loan_application() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'ls_public_nonce')) {
            wp_die('Security check failed');
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'loan_applications';
        
        $data = array(
            'user_id' => get_current_user_id() ?: null,
            'loan_type_id' => intval($_POST['loan_type']),
            'first_name' => sanitize_text_field($_POST['first_name']),
            'last_name' => sanitize_text_field($_POST['last_name']),
            'email' => sanitize_email($_POST['email']),
            'phone' => sanitize_text_field($_POST['phone']),
            'loan_amount' => floatval($_POST['loan_amount']),
            'loan_term' => intval($_POST['loan_term']),
            'monthly_income' => floatval($_POST['monthly_income']),
            'employment_status' => sanitize_text_field($_POST['employment_status']),
            'employment_years' => intval($_POST['employment_years']),
            'credit_score' => intval($_POST['credit_score']),
            'address' => sanitize_textarea_field($_POST['address']),
            'city' => sanitize_text_field($_POST['city']),
            'state' => sanitize_text_field($_POST['state']),
            'zip_code' => sanitize_text_field($_POST['zip_code'])
        );
        
        $result = $wpdb->insert($table_name, $data);
        
        if ($result) {
            $this->send_application_notification($data);
            wp_send_json_success(array('message' => 'Application submitted successfully'));
        } else {
            wp_send_json_error(array('message' => 'Failed to submit application'));
        }
    }
    
    public function calculate_loan_emi() {
        $amount = floatval($_POST['amount']);
        $rate = floatval($_POST['rate']) / 12 / 100; // Monthly interest rate
        $term = intval($_POST['term']); // In months
        
        $emi = $amount * $rate * pow(1 + $rate, $term) / (pow(1 + $rate, $term) - 1);
        $total_payment = $emi * $term;
        $total_interest = $total_payment - $amount;
        
        wp_send_json_success(array(
            'emi' => round($emi, 2),
            'total_payment' => round($total_payment, 2),
            'total_interest' => round($total_interest, 2)
        ));
    }
    
    public function update_application_status() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'loan_applications';
        
        $wpdb->update(
            $table_name,
            array('status' => sanitize_text_field($_POST['status'])),
            array('id' => intval($_POST['application_id']))
        );
        
        wp_send_json_success();
    }
    
    public function delete_application() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'loan_applications';
        
        $wpdb->delete($table_name, array('id' => intval($_POST['application_id'])));
        
        wp_send_json_success();
    }
    
    private function send_application_notification($data) {
        // Send email to admin
        $admin_email = get_option('admin_email');
        $subject = 'New Loan Application Received';
        $message = "A new loan application has been submitted:\n\n";
        $message .= "Name: {$data['first_name']} {$data['last_name']}\n";
        $message .= "Email: {$data['email']}\n";
        $message .= "Phone: {$data['phone']}\n";
        $message .= "Loan Amount: {$data['loan_amount']}\n";
        
        wp_mail($admin_email, $subject, $message);
        
        // Send confirmation to applicant
        $user_subject = 'Loan Application Confirmation';
        $user_message = "Thank you for submitting your loan application. We will review it and get back to you shortly.";
        
        wp_mail($data['email'], $user_subject, $user_message);
    }
}

// Initialize AJAX handlers
new LS_Ajax();