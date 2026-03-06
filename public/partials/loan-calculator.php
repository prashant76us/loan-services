<div class="ls-calculator-container">
    <h3><?php _e('Loan EMI Calculator', 'loan-services'); ?></h3>
    
    <form id="ls-calculator-form" class="ls-form">
        <div class="ls-form-group">
            <label for="calc_loan_amount"><?php _e('Loan Amount', 'loan-services'); ?></label>
            <input type="range" id="calc_loan_amount" name="amount" min="10000" max="10000000" step="10000" value="500000">
            <div class="ls-range-value">
                <span class="ls-amount-display"><?php _e('₹5,00,000', 'loan-services'); ?></span>
            </div>
        </div>
        
        <div class="ls-form-group">
            <label for="calc_interest_rate"><?php _e('Interest Rate (% p.a.)', 'loan-services'); ?></label>
            <input type="range" id="calc_interest_rate" name="rate" min="5" max="20" step="0.1" value="10.5">
            <div class="ls-range-value">
                <span class="ls-rate-display">10.5%</span>
            </div>
        </div>
        
        <div class="ls-form-group">
            <label for="calc_loan_term"><?php _e('Loan Term (months)', 'loan-services'); ?></label>
            <input type="range" id="calc_loan_term" name="term" min="6" max="360" step="6" value="60">
            <div class="ls-range-value">
                <span class="ls-term-display">60 <?php _e('months', 'loan-services'); ?></span>
            </div>
        </div>
        
        <button type="submit" class="ls-submit-btn"><?php _e('Calculate EMI', 'loan-services'); ?></button>
    </form>
    
    <div id="calculator-result" class="ls-calculator-result" style="display:none;">
        <div class="ls-result-item">
            <h4><?php _e('Monthly EMI', 'loan-services'); ?></h4>
            <div class="ls-value" id="emi-amount">₹0</div>
        </div>
        
        <div class="ls-result-item">
            <h4><?php _e('Total Interest', 'loan-services'); ?></h4>
            <div class="ls-value" id="total-interest">₹0</div>
        </div>
        
        <div class="ls-result-item">
            <h4><?php _e('Total Payment', 'loan-services'); ?></h4>
            <div class="ls-value" id="total-payment">₹0</div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Update range input displays
    $('#calc_loan_amount').on('input', function() {
        var value = parseFloat($(this).val());
        $('.ls-amount-display').text('₹' + value.toLocaleString('en-IN'));
    });
    
    $('#calc_interest_rate').on('input', function() {
        var value = $(this).val();
        $('.ls-rate-display').text(value + '%');
    });
    
    $('#calc_loan_term').on('input', function() {
        var value = $(this).val();
        $('.ls-term-display').text(value + ' <?php _e('months', 'loan-services'); ?>');
    });
});
</script>