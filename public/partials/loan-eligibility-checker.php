<div class="ls-eligibility-container">
    <h3><?php _e('Check Your Loan Eligibility', 'loan-services'); ?></h3>
    
    <form id="ls-eligibility-form" class="ls-form">
        <div class="ls-form-group">
            <label for="elig_income"><?php _e('Monthly Income', 'loan-services'); ?></label>
            <input type="number" id="elig_income" name="income" min="0" step="1000" required>
        </div>
        
        <div class="ls-form-group">
            <label for="elig_employment"><?php _e('Employment Type', 'loan-services'); ?></label>
            <select id="elig_employment" name="employment" required>
                <option value=""><?php _e('Select Employment Type', 'loan-services'); ?></option>
                <option value="salaried"><?php _e('Salaried', 'loan-services'); ?></option>
                <option value="self_employed"><?php _e('Self Employed', 'loan-services'); ?></option>
                <option value="business"><?php _e('Business', 'loan-services'); ?></option>
            </select>
        </div>
        
        <div class="ls-form-group">
            <label for="elig_credit_score"><?php _e('Credit Score', 'loan-services'); ?></label>
            <input type="number" id="elig_credit_score" name="credit_score" min="300" max="850">
        </div>
        
        <div class="ls-form-group">
            <label for="elig_existing_loans"><?php _e('Existing Loans', 'loan-services'); ?></label>
            <select id="elig_existing_loans" name="existing_loans">
                <option value="no"><?php _e('No', 'loan-services'); ?></option>
                <option value="yes"><?php _e('Yes', 'loan-services'); ?></option>
            </select>
        </div>
        
        <button type="submit" class="ls-submit-btn"><?php _e('Check Eligibility', 'loan-services'); ?></button>
    </form>
    
    <div id="eligibility-result" class="ls-eligibility-result" style="display:none;">
        <!-- Results will be loaded via AJAX -->
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#ls-eligibility-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        formData += '&action=check_eligibility&nonce=' + ls_public.nonce;
        
        $.ajax({
            url: ls_public.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#eligibility-result').html(response.data.html).show();
                } else {
                    $('#eligibility-result').html('<p class="error">' + response.data.message + '</p>').show();
                }
            }
        });
    });
});
</script>