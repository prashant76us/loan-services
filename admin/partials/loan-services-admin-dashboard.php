<div class="wrap ls-admin-wrap">
    <h1><?php echo esc_html__('Loan Services Dashboard', 'loan-services'); ?></h1>
    
    <div class="ls-dashboard-stats">
        <?php
        global $wpdb;
        $applications_table = $wpdb->prefix . 'loan_applications';
        
        $total_applications = $wpdb->get_var("SELECT COUNT(*) FROM $applications_table");
        $pending_applications = $wpdb->get_var("SELECT COUNT(*) FROM $applications_table WHERE status = 'pending'");
        $approved_applications = $wpdb->get_var("SELECT COUNT(*) FROM $applications_table WHERE status = 'approved'");
        $rejected_applications = $wpdb->get_var("SELECT COUNT(*) FROM $applications_table WHERE status = 'rejected'");
        ?>
        
        <div class="ls-stat-box">
            <h3><?php _e('Total Applications', 'loan-services'); ?></h3>
            <p class="ls-stat-number"><?php echo $total_applications; ?></p>
        </div>
        
        <div class="ls-stat-box pending">
            <h3><?php _e('Pending', 'loan-services'); ?></h3>
            <p class="ls-stat-number"><?php echo $pending_applications; ?></p>
        </div>
        
        <div class="ls-stat-box approved">
            <h3><?php _e('Approved', 'loan-services'); ?></h3>
            <p class="ls-stat-number"><?php echo $approved_applications; ?></p>
        </div>
        
        <div class="ls-stat-box rejected">
            <h3><?php _e('Rejected', 'loan-services'); ?></h3>
            <p class="ls-stat-number"><?php echo $rejected_applications; ?></p>
        </div>
    </div>
    
    <div class="ls-recent-applications">
        <h2><?php _e('Recent Applications', 'loan-services'); ?></h2>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php _e('ID', 'loan-services'); ?></th>
                    <th><?php _e('Name', 'loan-services'); ?></th>
                    <th><?php _e('Loan Type', 'loan-services'); ?></th>
                    <th><?php _e('Amount', 'loan-services'); ?></th>
                    <th><?php _e('Status', 'loan-services'); ?></th>
                    <th><?php _e('Date', 'loan-services'); ?></th>
                    <th><?php _e('Actions', 'loan-services'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $recent = $wpdb->get_results("SELECT a.*, t.name as loan_type_name 
                                              FROM $applications_table a 
                                              LEFT JOIN {$wpdb->prefix}loan_types t ON a.loan_type_id = t.id 
                                              ORDER BY a.submission_date DESC 
                                              LIMIT 10");
                
                foreach ($recent as $app) : ?>
                    <tr>
                        <td><?php echo $app->id; ?></td>
                        <td><?php echo $app->first_name . ' ' . $app->last_name; ?></td>
                        <td><?php echo $app->loan_type_name; ?></td>
                        <td><?php echo number_format($app->loan_amount, 2); ?></td>
                        <td>
                            <span class="ls-status ls-status-<?php echo $app->status; ?>">
                                <?php echo ucfirst($app->status); ?>
                            </span>
                        </td>
                        <td><?php echo date('Y-m-d', strtotime($app->submission_date)); ?></td>
                        <td>
                            <a href="#" class="button button-small view-application" data-id="<?php echo $app->id; ?>">
                                <?php _e('View', 'loan-services'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>