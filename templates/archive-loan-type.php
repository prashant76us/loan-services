<?php
/**
 * Template Name: Loan Types Archive
 */

get_header(); ?>

<div class="ls-archive-container">
    <header class="ls-archive-header">
        <h1><?php _e('Our Loan Products', 'loan-services'); ?></h1>
        <p><?php _e('Choose the right loan product for your needs', 'loan-services'); ?></p>
    </header>
    
    <div class="ls-loan-filters">
        <select id="filter-loan-amount">
            <option value=""><?php _e('Filter by Amount', 'loan-services'); ?></option>
            <option value="0-500000"><?php _e('Up to ₹5 Lakhs', 'loan-services'); ?></option>
            <option value="500000-2000000"><?php _e('₹5 Lakhs - ₹20 Lakhs', 'loan-services'); ?></option>
            <option value="2000000-5000000"><?php _e('₹20 Lakhs - ₹50 Lakhs', 'loan-services'); ?></option>
            <option value="5000000+"><?php _e('Above ₹50 Lakhs', 'loan-services'); ?></option>
        </select>
        
        <select id="filter-loan-term">
            <option value=""><?php _e('Filter by Term', 'loan-services'); ?></option>
            <option value="0-12"><?php _e('Up to 12 months', 'loan-services'); ?></option>
            <option value="12-60"><?php _e('1-5 years', 'loan-services'); ?></option>
            <option value="60-120"><?php _e('5-10 years', 'loan-services'); ?></option>
            <option value="120+"><?php _e('Above 10 years', 'loan-services'); ?></option>
        </select>
    </div>
    
    <div class="ls-loan-types-grid">
        <?php
        global $wpdb;
        $loan_types = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}loan_types WHERE is_active = 1");
        
        foreach ($loan_types as $type) :
        ?>
            <div class="ls-loan-type-card" 
                 data-amount-min="<?php echo $type->min_amount; ?>" 
                 data-amount-max="<?php echo $type->max_amount; ?>"
                 data-term-min="<?php echo $type->min_term; ?>"
                 data-term-max="<?php echo $type->max_term; ?>">
                
                <h2><?php echo esc_html($type->name); ?></h2>
                <p><?php echo esc_html($type->description); ?></p>
                
                <div class="ls-loan-details">
                    <div class="ls-detail-item">
                        <span class="label"><?php _e('Amount Range:', 'loan-services'); ?></span>
                        <span class="value"><?php echo number_format($type->min_amount); ?> - <?php echo number_format($type->max_amount); ?></span>
                    </div>
                    
                    <div class="ls-detail-item">
                        <span class="label"><?php _e('Interest Rate:', 'loan-services'); ?></span>
                        <span class="value"><?php echo $type->interest_rate; ?>% p.a.</span>
                    </div>
                    
                    <div class="ls-detail-item">
                        <span class="label"><?php _e('Term Range:', 'loan-services'); ?></span>
                        <span class="value"><?php echo $type->min_term; ?> - <?php echo $type->max_term; ?> months</span>
                    </div>
                </div>
                
                <a href="<?php echo get_permalink($type->id); ?>" class="ls-view-details">
                    <?php _e('View Details', 'loan-services'); ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    function filterLoanTypes() {
        var amountFilter = $('#filter-loan-amount').val();
        var termFilter = $('#filter-loan-term').val();
        
        $('.ls-loan-type-card').each(function() {
            var show = true;
            
            if (amountFilter) {
                var amountMin = $(this).data('amount-min');
                var amountMax = $(this).data('amount-max');
                
                if (amountFilter === '0-500000' && amountMax > 500000) show = false;
                if (amountFilter === '500000-2000000' && (amountMin < 500000 || amountMax > 2000000)) show = false;
                if (amountFilter === '2000000-5000000' && (amountMin < 2000000 || amountMax > 5000000)) show = false;
                if (amountFilter === '5000000+' && amountMin < 5000000) show = false;
            }
            
            if (termFilter && show) {
                var termMin = $(this).data('term-min');
                var termMax = $(this).data('term-max');
                
                if (termFilter === '0-12' && termMax > 12) show = false;
                if (termFilter === '12-60' && (termMin < 12 || termMax > 60)) show = false;
                if (termFilter === '60-120' && (termMin < 60 || termMax > 120)) show = false;
                if (termFilter === '120+' && termMin < 120) show = false;
            }
            
            if (show) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }
    
    $('#filter-loan-amount, #filter-loan-term').on('change', filterLoanTypes);
});
</script>

<?php get_footer(); ?>