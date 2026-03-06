<div class="wrap ls-admin-wrap">
    <h1 class="wp-heading-inline"><?php _e('Loan Applications', 'loan-services'); ?></h1>
    <a href="#" id="ls-export-applications" class="page-title-action"><?php _e('Export', 'loan-services'); ?></a>
    
    <hr class="wp-header-end">
    
    <!-- Filters -->
    <div class="ls-filters">
        <select id="filter-status">
            <option value=""><?php _e('All Statuses', 'loan-services'); ?></option>
            <option value="pending"><?php _e('Pending', 'loan-services'); ?></option>
            <option value="approved"><?php _e('Approved', 'loan-services'); ?></option>
            <option value="rejected"><?php _e('Rejected', 'loan-services'); ?></option>
            <option value="processing"><?php _e('Processing', 'loan-services'); ?></option>
        </select>
        
        <input type="text" id="filter-date-range" placeholder="<?php _e('Date Range', 'loan-services'); ?>">
        
        <button class="button" id="apply-filters"><?php _e('Apply Filters', 'loan-services'); ?></button>
    </div>
    
    <!-- Bulk Actions -->
    <div class="ls-bulk-actions">
        <select id="ls-bulk-actions">
            <option value=""><?php _e('Bulk Actions', 'loan-services'); ?></option>
            <option value="approve"><?php _e('Approve', 'loan-services'); ?></option>
            <option value="reject"><?php _e('Reject', 'loan-services'); ?></option>
            <option value="delete"><?php _e('Delete', 'loan-services'); ?></option>
        </select>
    </div>
    
    <?php
    global $wpdb;
    $applications_table = $wpdb->prefix . 'loan_applications';
    $loan_types_table = $wpdb->prefix . 'loan_types';
    
    // Pagination
    $per_page = 20;
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $per_page;
    
    // Get total count
    $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $applications_table");
    $total_pages = ceil($total_items / $per_page);
    
    // Get applications with loan type names
    $applications = $wpdb->get_results("
        SELECT a.*, t.name as loan_type_name 
        FROM $applications_table a 
        LEFT JOIN $loan_types_table t ON a.loan_type_id = t.id 
        ORDER BY a.submission_date DESC 
        LIMIT $offset, $per_page
    ");
    ?>
    
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <td width="20"><input type="checkbox" id="ls-select-all"></td>
                <th><?php _e('ID', 'loan-services'); ?></th>
                <th><?php _e('Applicant', 'loan-services'); ?></th>
                <th><?php _e('Loan Type', 'loan-services'); ?></th>
                <th><?php _e('Amount', 'loan-services'); ?></th>
                <th><?php _e('Term', 'loan-services'); ?></th>
                <th><?php _e('Monthly Income', 'loan-services'); ?></th>
                <th><?php _e('Status', 'loan-services'); ?></th>
                <th><?php _e('Submitted', 'loan-services'); ?></th>
                <th><?php _e('Actions', 'loan-services'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($applications)) : ?>
                <tr>
                    <td colspan="10"><?php _e('No applications found.', 'loan-services'); ?></td>
                </tr>
            <?php else : ?>
                <?php foreach ($applications as $app) : ?>
                    <tr>
                        <td><input type="checkbox" class="ls-application-checkbox" value="<?php echo $app->id; ?>"></td>
                        <td>#<?php echo $app->id; ?></td>
                        <td>
                            <strong><?php echo $app->first_name . ' ' . $app->last_name; ?></strong><br>
                            <small><?php echo $app->email; ?><br><?php echo $app->phone; ?></small>
                        </td>
                        <td><?php echo $app->loan_type_name; ?></td>
                        <td><?php echo number_format($app->loan_amount, 2); ?></td>
                        <td><?php echo $app->loan_term; ?> months</td>
                        <td><?php echo number_format($app->monthly_income, 2); ?></td>
                        <td>
                            <span class="ls-status ls-status-<?php echo $app->status; ?>">
                                <?php echo ucfirst($app->status); ?>
                            </span>
                        </td>
                        <td><?php echo date_i18n(get_option('date_format'), strtotime($app->submission_date)); ?></td>
                        <td>
                            <div class="ls-action-buttons">
                                <a href="#" class="button button-small view-application" data-id="<?php echo $app->id; ?>">
                                    <?php _e('View', 'loan-services'); ?>
                                </a>
                                <button class="button button-small ls-update-status" data-id="<?php echo $app->id; ?>" data-status="approved">
                                    <?php _e('Approve', 'loan-services'); ?>
                                </button>
                                <button class="button button-small ls-update-status" data-id="<?php echo $app->id; ?>" data-status="rejected">
                                    <?php _e('Reject', 'loan-services'); ?>
                                </button>
                                <button class="button button-small ls-delete-application" data-id="<?php echo $app->id; ?>">
                                    <?php _e('Delete', 'loan-services'); ?>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <!-- Pagination -->
    <?php if ($total_pages > 1) : ?>
        <div class="tablenav bottom">
            <div class="tablenav-pages">
                <span class="displaying-num">
                    <?php echo sprintf(__('%d items'), $total_items); ?>
                </span>
                <span class="pagination-links">
                    <?php
                    echo paginate_links(array(
                        'base' => add_query_arg('paged', '%#%'),
                        'format' => '',
                        'prev_text' => '&laquo;',
                        'next_text' => '&raquo;',
                        'total' => $total_pages,
                        'current' => $current_page
                    ));
                    ?>
                </span>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Application Details Modal -->
    <div id="ls-application-modal" class="ls-modal">
        <div class="ls-modal-content">
            <span class="ls-modal-close">&times;</span>
            <div id="ls-application-details"></div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Select all checkbox
    $('#ls-select-all').on('change', function() {
        $('.ls-application-checkbox').prop('checked', $(this).prop('checked'));
    });
    
    // View application details
    $('.view-application').on('click', function(e) {
        e.preventDefault();
        var applicationId = $(this).data('id');
        
        $.ajax({
            url: ls_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_application_details',
                application_id: applicationId,
                nonce: ls_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#ls-application-details').html(response.data.html);
                    $('#ls-application-modal').show();
                }
            }
        });
    });
    
    // Close modal
    $('.ls-modal-close, .ls-modal').on('click', function(e) {
        if (e.target === this) {
            $('#ls-application-modal').hide();
        }
    });
});
</script>