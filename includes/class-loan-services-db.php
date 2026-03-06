<?php
/**
 * Database operations for Loan Services
 *
 * @package Loan_Services
 */

class LS_DB {
    
    /**
     * Create database tables on plugin activation
     */
    public static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        // Loan Applications table
        $table_name = $wpdb->prefix . 'loan_applications';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            user_id int(11) DEFAULT NULL,
            loan_type_id int(11) NOT NULL,
            first_name varchar(100) NOT NULL,
            last_name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            phone varchar(20) NOT NULL,
            loan_amount decimal(15,2) NOT NULL,
            loan_term int(11) NOT NULL,
            monthly_income decimal(15,2) NOT NULL,
            employment_status varchar(50) NOT NULL,
            employment_years int(11) DEFAULT NULL,
            credit_score int(11) DEFAULT NULL,
            address text NOT NULL,
            city varchar(100) NOT NULL,
            state varchar(50) NOT NULL,
            zip_code varchar(20) NOT NULL,
            status varchar(20) DEFAULT 'pending',
            submission_date datetime DEFAULT CURRENT_TIMESTAMP,
            last_updated datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY status (status),
            KEY loan_type_id (loan_type_id)
        ) $charset_collate;";
        
        // Loan Types table
        $table_name2 = $wpdb->prefix . 'loan_types';
        $sql2 = "CREATE TABLE IF NOT EXISTS $table_name2 (
            id int(11) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            description text,
            min_amount decimal(15,2) NOT NULL,
            max_amount decimal(15,2) NOT NULL,
            min_term int(11) NOT NULL,
            max_term int(11) NOT NULL,
            interest_rate decimal(5,2) NOT NULL,
            processing_fee decimal(5,2) DEFAULT 0,
            eligibility_criteria text,
            documents_required text,
            is_active tinyint(1) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY is_active (is_active)
        ) $charset_collate;";
        
        // Documents table
        $table_name3 = $wpdb->prefix . 'loan_documents';
        $sql3 = "CREATE TABLE IF NOT EXISTS $table_name3 (
            id int(11) NOT NULL AUTO_INCREMENT,
            application_id int(11) NOT NULL,
            document_name varchar(255) NOT NULL,
            file_path varchar(255) NOT NULL,
            uploaded_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY application_id (application_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        dbDelta($sql2);
        dbDelta($sql3);
        
        // Insert default loan types
        self::insert_default_loan_types();
    }
    
    /**
     * Insert default loan types if none exist
     */
    private static function insert_default_loan_types() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'loan_types';
        
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        
        if ($count == 0) {
            $default_loans = array(
                array(
                    'name' => 'Personal Loan',
                    'description' => 'Flexible personal loans for your needs',
                    'min_amount' => 5000,
                    'max_amount' => 500000,
                    'min_term' => 6,
                    'max_term' => 60,
                    'interest_rate' => 10.5,
                    'processing_fee' => 2.0,
                    'eligibility_criteria' => 'Minimum monthly income: ₹15,000, Credit score: 650+',
                    'documents_required' => 'ID Proof, Address Proof, Income Proof, Bank Statements'
                ),
                array(
                    'name' => 'Home Loan',
                    'description' => 'Affordable home loans with competitive rates',
                    'min_amount' => 500000,
                    'max_amount' => 10000000,
                    'min_term' => 12,
                    'max_term' => 360,
                    'interest_rate' => 8.5,
                    'processing_fee' => 1.0,
                    'eligibility_criteria' => 'Minimum monthly income: ₹25,000, Credit score: 700+',
                    'documents_required' => 'Property Documents, ID Proof, Income Proof, Tax Returns'
                ),
                array(
                    'name' => 'Business Loan',
                    'description' => 'Grow your business with our business loans',
                    'min_amount' => 100000,
                    'max_amount' => 5000000,
                    'min_term' => 12,
                    'max_term' => 84,
                    'interest_rate' => 12.0,
                    'processing_fee' => 1.5,
                    'eligibility_criteria' => 'Business vintage: 2+ years, Minimum turnover: ₹10 Lakhs',
                    'documents_required' => 'Business Registration, GST Returns, Bank Statements'
                )
            );
            
            foreach ($default_loans as $loan) {
                $wpdb->insert($table_name, $loan);
            }
        }
    }
    
    /**
     * Drop database tables on plugin uninstall
     */
    public static function drop_tables() {
        global $wpdb;
        $tables = array(
            $wpdb->prefix . 'loan_applications',
            $wpdb->prefix . 'loan_types',
            $wpdb->prefix . 'loan_documents'
        );
        
        foreach ($tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS $table");
        }
    }
    
    /**
     * Get all loan applications with optional filters
     */
    public static function get_applications($filters = array(), $limit = 20, $offset = 0) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'loan_applications';
        $loan_types_table = $wpdb->prefix . 'loan_types';
        
        $where = array('1=1');
        
        if (!empty($filters['status'])) {
            $where[] = $wpdb->prepare("a.status = %s", $filters['status']);
        }
        
        if (!empty($filters['from_date'])) {
            $where[] = $wpdb->prepare("DATE(a.submission_date) >= %s", $filters['from_date']);
        }
        
        if (!empty($filters['to_date'])) {
            $where[] = $wpdb->prepare("DATE(a.submission_date) <= %s", $filters['to_date']);
        }
        
        $where_clause = implode(' AND ', $where);
        
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT a.*, t.name as loan_type_name 
            FROM $table_name a 
            LEFT JOIN $loan_types_table t ON a.loan_type_id = t.id 
            WHERE $where_clause 
            ORDER BY a.submission_date DESC 
            LIMIT %d OFFSET %d",
            $limit,
            $offset
        ));
        
        return $results;
    }
    
    /**
     * Get a single application by ID
     */
    public static function get_application($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'loan_applications';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE id = %d",
            $id
        ));
    }
    
    /**
     * Update application status
     */
    public static function update_application_status($id, $status) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'loan_applications';
        
        return $wpdb->update(
            $table_name,
            array('status' => $status),
            array('id' => $id),
            array('%s'),
            array('%d')
        );
    }
    
    /**
     * Delete application
     */
    public static function delete_application($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'loan_applications';
        
        return $wpdb->delete(
            $table_name,
            array('id' => $id),
            array('%d')
        );
    }
    
    /**
     * Get statistics for dashboard
     */
    public static function get_statistics() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'loan_applications';
        
        $stats = array(
            'total' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name"),
            'pending' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'pending'"),
            'approved' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'approved'"),
            'rejected' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'rejected'"),
            'processing' => $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'processing'")
        );
        
        return $stats;
    }
}