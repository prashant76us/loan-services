<div class="wrap ls-admin-wrap">
    <h1 class="wp-heading-inline"><?php _e('Loan Types', 'loan-services'); ?></h1>
    <a href="#" class="page-title-action" id="add-new-loan-type"><?php _e('Add New', 'loan-services'); ?></a>
    
    <hr class="wp-header-end">
    
    <?php
    global $wpdb;
    $loan_types_table = $wpdb->prefix . 'loan_types';
    $loan_types = $wpdb->get_results("SELECT * FROM $loan_types_table ORDER BY id DESC");
    ?>
    
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e('ID', 'loan-services'); ?></th>
                <th><?php _e('Name', 'loan-services'); ?></th>
                <th><?php _e('Amount Range', 'loan-services'); ?></th>
                <th><?php _e('Term Range', 'loan-services'); ?></th>
                <th><?php _e('Interest Rate', 'loan-services'); ?></th>
                <th><?php _e('Processing Fee', 'loan-services'); ?></th>
                <th><?php _e('Status', 'loan-services'); ?></th>
                <th><?php _e('Actions', 'loan-services'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($loan_types)) : ?>
                <tr>
                    <td colspan="8"><?php _e('No loan types found.', 'loan-services'); ?></td>
                </tr>
            <?php else : ?>
                <?php foreach ($loan_types as $type) : ?>
                    <tr>
                        <td>#<?php echo $type->id; ?></td>
                        <td><strong><?php echo $type->name; ?></strong></td>
                        <td>
                            <?php echo number_format($type->min_amount, 2); ?> - 
                            <?php echo number_format($type->max_amount, 2); ?>
                        </td>
                        <td>
                            <?php echo $type->min_term; ?> - <?php echo $type->max_term; ?> months
                        </td>
                        <td><?php echo $type->interest_rate; ?>%</td>
                        <td><?php echo $type->processing_fee; ?>%</td>
                        <td>
                            <span class="ls-status ls-status-<?php echo $type->is_active ? 'active' : 'inactive'; ?>">
                                <?php echo $type->is_active ? __('Active', 'loan-services') : __('Inactive', 'loan-services'); ?>
                            </span>
                        </td>
                        <td>
                            <div class="ls-action-buttons">
                                <a href="#" class="button button-small edit-loan-type" data-id="<?php echo $type->id; ?>">
                                    <?php _e('Edit', 'loan-services'); ?>
                                </a>
                                <a href="#" class="button button-small toggle-status" data-id="<?php echo $type->id; ?>" 
                                   data-status="<?php echo $type->is_active ? '0' : '1'; ?>">
                                    <?php echo $type->is_active ? __('Deactivate', 'loan-services') : __('Activate', 'loan-services'); ?>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <!-- Add/Edit Loan Type Modal -->
    <div id="ls-loan-type-modal" class="ls-modal">
        <div class="ls-modal-content">
            <span class="ls-modal-close">&times;</span>
            <h2 id="ls-modal-title"><?php _e('Add New Loan Type', 'loan-services'); ?></h2>
            
            <form id="ls-loan-type-form">
                <input type="hidden" id="loan_type_id" name="loan_type_id" value="0">
                
                <div class="ls-form-group">
                    <label for="name"><?php _e('Loan Name *', 'loan-services'); ?></label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="ls-form-group">
                    <label for="description"><?php _e('Description', 'loan-services'); ?></label>
                    <textarea id="description" name="description" rows="4"></textarea>
                </div>
                
                <div class="ls-form-row">
                    <div class="ls-form-group">
                        <label for="min_amount"><?php _e('Minimum Amount *', 'loan-services'); ?></label>
                        <input type="number" id="min_amount" name="min_amount" min="0" step="1000" required>
                    </div>
                    
                    <div class="ls-form-group">
                        <label for="max_amount"><?php _e('Maximum Amount *', 'loan-services'); ?></label>
                        <input type="number" id="max_amount" name="max_amount" min="0" step="1000" required>
                    </div>
                </div>
                
                <div class="ls-form-row">
                    <div class="ls-form-group">
                        <label for="min_term"><?php _e('Minimum Term (months) *', 'loan-services'); ?></label>
                        <input type="number" id="min_term" name="min_term" min="1" required>
                    </div>
                    
                    <div class="ls-form-group">
                        <label for="max_term"><?php _e('Maximum Term (months) *', 'loan-services'); ?></label>
                        <input type="number" id="max_term" name="max_term" min="1" required>
                    </div>
                </div>
                
                <div class="ls-form-row">
                    <div class="ls-form-group">
                        <label for="interest_rate"><?php _e('Interest Rate (% p.a.) *', 'loan-services'); ?></label>
                        <input type="number" id="interest_rate" name="interest_rate" min="0" step="0.1" required>
                    </div>
                    
                    <div class="ls-form-group">
                        <label for="processing_fee"><?php _e('Processing Fee (%)', 'loan-services'); ?></label>
                        <input type="number" id="processing_fee" name="processing_fee" min="0" step="0.1">
                    </div>
                </div>
                
                <div class="ls-form-group">
                    <label for="eligibility_criteria"><?php _e('Eligibility Criteria', 'loan-services'); ?></label>
                    <textarea id="eligibility_criteria" name="eligibility_criteria" rows="3"></textarea>
                </div>
                
                <div class="ls-form-group">
                    <label for="documents_required"><?php _e('Documents Required', 'loan-services'); ?></label>
                    <textarea id="documents_required" name="documents_required" rows="3"></textarea>
                </div>
                
                <div class="ls-form-actions">
                    <button type="submit" class="button button-primary"><?php _e('Save Loan Type', 'loan-services'); ?></button>
                    <button type="button" class="button ls-modal-close"><?php _e('Cancel', 'loan-services'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Add new loan type
    $('#add-new-loan-type').on('click', function(e) {
        e.preventDefault();
        $('#ls-modal-title').text('<?php _e('Add New Loan Type', 'loan-services'); ?>');
        $('#loan_type_id').val('0');
        $('#ls-loan-type-form')[0].reset();
        $('#ls-loan-type-modal').show();
    });
    
    // Edit loan type
    $('.edit-loan-type').on('click', function(e) {
        e.preventDefault();
        var typeId = $(this).data('id');
        
        $.ajax({
            url: ls_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_loan_type',
                type_id: typeId,
                nonce: ls_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    var type = response.data;
                    $('#ls-modal-title').text('<?php _e('Edit Loan Type', 'loan-services'); ?>');
                    $('#loan_type_id').val(type.id);
                    $('#name').val(type.name);
                    $('#description').val(type.description);
                    $('#min_amount').val(type.min_amount);
                    $('#max_amount').val(type.max_amount);
                    $('#min_term').val(type.min_term);
                    $('#max_term').val(type.max_term);
                    $('#interest_rate').val(type.interest_rate);
                    $('#processing_fee').val(type.processing_fee);
                    $('#eligibility_criteria').val(type.eligibility_criteria);
                    $('#documents_required').val(type.documents_required);
                    $('#ls-loan-type-modal').show();
                }
            }
        });
    });
    
    // Toggle status
    $('.toggle-status').on('click', function(e) {
        e.preventDefault();
        var typeId = $(this).data('id');
        var newStatus = $(this).data('status');
        
        $.ajax({
            url: ls_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'toggle_loan_type_status',
                type_id: typeId,
                status: newStatus,
                nonce: ls_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            }
        });
    });
});
</script>