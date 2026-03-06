<div class="ls-application-form-container">
    <h2><?php _e('Apply for a Loan', 'loan-services'); ?></h2>
    
    <form id="ls-application-form" method="post" class="ls-form">
        <?php wp_nonce_field('ls_submit_application', 'ls_nonce'); ?>
        
        <div class="ls-form-row">
            <div class="ls-form-group">
                <label for="first_name"><?php _e('First Name *', 'loan-services'); ?></label>
                <input type="text" id="first_name" name="first_name" required>
            </div>
            
            <div class="ls-form-group">
                <label for="last_name"><?php _e('Last Name *', 'loan-services'); ?></label>
                <input type="text" id="last_name" name="last_name" required>
            </div>
        </div>
        
        <div class="ls-form-row">
            <div class="ls-form-group">
                <label for="email"><?php _e('Email Address *', 'loan-services'); ?></label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="ls-form-group">
                <label for="phone"><?php _e('Phone Number *', 'loan-services'); ?></label>
                <input type="tel" id="phone" name="phone" required>
            </div>
        </div>
        
        <div class="ls-form-row">
            <div class="ls-form-group">
                <label for="loan_type"><?php _e('Loan Type *', 'loan-services'); ?></label>
                <select id="loan_type" name="loan_type" required>
                    <option value=""><?php _e('Select Loan Type', 'loan-services'); ?></option>
                    <?php
                    global $wpdb;
                    $loan_types = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}loan_types WHERE is_active = 1");
                    foreach ($loan_types as $type) : ?>
                        <option value="<?php echo $type->id; ?>" 
                                data-min="<?php echo $type->min_amount; ?>" 
                                data-max="<?php echo $type->max_amount; ?>"
                                data-rate="<?php echo $type->interest_rate; ?>">
                            <?php echo $type->name; ?> (<?php echo $type->interest_rate; ?>% p.a.)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="ls-form-group">
                <label for="loan_amount"><?php _e('Loan Amount *', 'loan-services'); ?></label>
                <input type="number" id="loan_amount" name="loan_amount" min="1000" step="1000" required>
            </div>
        </div>
        
        <div class="ls-form-row">
            <div class="ls-form-group">
                <label for="loan_term"><?php _e('Loan Term (Months) *', 'loan-services'); ?></label>
                <input type="number" id="loan_term" name="loan_term" min="1" max="360" required>
            </div>
            
            <div class="ls-form-group">
                <label for="monthly_income"><?php _e('Monthly Income *', 'loan-services'); ?></label>
                <input type="number" id="monthly_income" name="monthly_income" min="0" step="1000" required>
            </div>
        </div>
        
        <div class="ls-form-row">
            <div class="ls-form-group">
                <label for="employment_status"><?php _e('Employment Status *', 'loan-services'); ?></label>
                <select id="employment_status" name="employment_status" required>
                    <option value=""><?php _e('Select Status', 'loan-services'); ?></option>
                    <option value="salaried"><?php _e('Salaried', 'loan-services'); ?></option>
                    <option value="self_employed"><?php _e('Self Employed', 'loan-services'); ?></option>
                    <option value="business"><?php _e('Business Owner', 'loan-services'); ?></option>
                    <option value="retired"><?php _e('Retired', 'loan-services'); ?></option>
                </select>
            </div>
            
            <div class="ls-form-group">
                <label for="employment_years"><?php _e('Years in Current Employment', 'loan-services'); ?></label>
                <input type="number" id="employment_years" name="employment_years" min="0" step="1">
            </div>
        </div>
        
        <div class="ls-form-group">
            <label for="address"><?php _e('Address *', 'loan-services'); ?></label>
            <textarea id="address" name="address" rows="3" required></textarea>
        </div>
        
        <div class="ls-form-row">
            <div class="ls-form-group">
                <label for="city"><?php _e('City *', 'loan-services'); ?></label>
                <input type="text" id="city" name="city" required>
            </div>
            
            <div class="ls-form-group">
                <label for="state"><?php _e('State *', 'loan-services'); ?></label>
                <input type="text" id="state" name="state" required>
            </div>
            
            <div class="ls-form-group">
                <label for="zip_code"><?php _e('ZIP Code *', 'loan-services'); ?></label>
                <input type="text" id="zip_code" name="zip_code" required>
            </div>
        </div>
        
        <div class="ls-form-group">
            <label for="credit_score"><?php _e('Credit Score (if known)', 'loan-services'); ?></label>
            <input type="number" id="credit_score" name="credit_score" min="300" max="850">
        </div>
        
        <div class="ls-form-actions">
            <button type="submit" class="ls-submit-btn"><?php _e('Submit Application', 'loan-services'); ?></button>
        </div>
        
        <div id="ls-form-response" style="display:none;"></div>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    $('#loan_type').on('change', function() {
        var selected = $(this).find(':selected');
        var min = selected.data('min');
        var max = selected.data('max');
        
        $('#loan_amount').attr('min', min);
        $('#loan_amount').attr('max', max);
        $('#loan_amount').val(min);
    });
    
    $('#ls-application-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        formData += '&action=submit_loan_application&nonce=' + ls_public.nonce;
        
        $.ajax({
            url: ls_public.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#ls-form-response').removeClass('error').addClass('success')
                        .html('<p>' + response.data.message + '</p>').show();
                    $('#ls-application-form')[0].reset();
                } else {
                    $('#ls-form-response').removeClass('success').addClass('error')
                        .html('<p>' + response.data.message + '</p>').show();
                }
            }
        });
    });
});
</script>