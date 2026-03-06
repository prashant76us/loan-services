<?php
/**
 * Template Name: Single Loan Type
 */

get_header();

global $wpdb;
$loan_type_id = get_the_ID();
$loan_type = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}loan_types WHERE id = %d",
    $loan_type_id
));
?>

<div class="ls-single-loan-container">
    <?php if ($loan_type) : ?>
        <div class="ls-loan-header">
            <h1><?php echo esc_html($loan_type->name); ?></h1>
            <p class="ls-loan-description"><?php echo esc_html($loan_type->description); ?></p>
        </div>
        
        <div class="ls-loan-content-wrapper">
            <div class="ls-loan-main-content">
                <div class="ls-loan-features-box">
                    <h2><?php _e('Key Features', 'loan-services'); ?></h2>
                    
                    <table class="ls-features-table">
                        <tr>
                            <th><?php _e('Loan Amount', 'loan-services'); ?></th>
                            <td><?php echo number_format($loan_type->min_amount, 0); ?> - <?php echo number_format($loan_type->max_amount, 0); ?></td>
                        </tr>
                        <tr>
                            <th><?php _e('Interest Rate', 'loan-services'); ?></th>
                            <td><?php echo $loan_type->interest_rate; ?>% p.a.</td>
                        </tr>
                        <tr>
                            <th><?php _e('Loan Term', 'loan-services'); ?></th>
                            <td><?php echo $loan_type->min_term; ?> - <?php echo $loan_type->max_term; ?> months</td>
                        </tr>
                        <tr>
                            <th><?php _e('Processing Fee', 'loan-services'); ?></th>
                            <td><?php echo $loan_type->processing_fee; ?>%</td>
                        </tr>
                    </table>
                </div>
                
                <?php if (!empty($loan_type->eligibility_criteria)) : ?>
                    <div class="ls-eligibility-box">
                        <h2><?php _e('Eligibility Criteria', 'loan-services'); ?></h2>
                        <div class="ls-eligibility-content">
                            <?php echo nl2br(esc_html($loan_type->eligibility_criteria)); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($loan_type->documents_required)) : ?>
                    <div class="ls-documents-box">
                        <h2><?php _e('Documents Required', 'loan-services'); ?></h2>
                        <div class="ls-documents-content">
                            <?php echo nl2br(esc_html($loan_type->documents_required)); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="ls-loan-sidebar">
                <div class="ls-calculator-widget">
                    <h3><?php _e('Calculate EMI', 'loan-services'); ?></h3>
                    <?php echo do_shortcode('[loan_calculator]'); ?>
                </div>
                
                <div class="ls-apply-widget">
                    <h3><?php _e('Ready to Apply?', 'loan-services'); ?></h3>
                    <p><?php _e('Get started with your loan application today.', 'loan-services'); ?></p>
                    <a href="<?php echo add_query_arg('loan_type', $loan_type->id, get_permalink(get_page_by_path('apply-for-loan'))); ?>" 
                       class="ls-apply-now-btn">
                        <?php _e('Apply Now', 'loan-services'); ?>
                    </a>
                </div>
                
                <div class="ls-contact-widget">
                    <h3><?php _e('Need Help?', 'loan-services'); ?></h3>
                    <p><?php _e('Contact our loan experts for assistance', 'loan-services'); ?></p>
                    <div class="ls-contact-info">
                        <p><strong><?php _e('Phone:', 'loan-services'); ?></strong> +1 234 567 890</p>
                        <p><strong><?php _e('Email:', 'loan-services'); ?></strong> loans@example.com</p>
                    </div>
                </div>
            </div>
        </div>
        
    <?php else : ?>
        <p><?php _e('Loan type not found.', 'loan-services'); ?></p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>