<div class="ls-loan-types-container">
    <h2><?php _e('Our Loan Products', 'loan-services'); ?></h2>
    
    <div class="ls-loan-types-grid">
        <?php foreach ($loan_types as $type) : ?>
            <div class="ls-loan-type-card">
                <h3><?php echo esc_html($type->name); ?></h3>
                <p><?php echo esc_html($type->description); ?></p>
                
                <ul class="ls-loan-features">
                    <li>
                        <span class="feature-label"><?php _e('Loan Amount:', 'loan-services'); ?></span>
                        <span class="feature-value">
                            <?php echo number_format($type->min_amount, 0); ?> - 
                            <?php echo number_format($type->max_amount, 0); ?>
                        </span>
                    </li>
                    
                    <li>
                        <span class="feature-label"><?php _e('Interest Rate:', 'loan-services'); ?></span>
                        <span class="feature-value"><?php echo $type->interest_rate; ?>% p.a.</span>
                    </li>
                    
                    <li>
                        <span class="feature-label"><?php _e('Loan Term:', 'loan-services'); ?></span>
                        <span class="feature-value">
                            <?php echo $type->min_term; ?> - <?php echo $type->max_term; ?> months
                        </span>
                    </li>
                    
                    <li>
                        <span class="feature-label"><?php _e('Processing Fee:', 'loan-services'); ?></span>
                        <span class="feature-value"><?php echo $type->processing_fee; ?>%</span>
                    </li>
                </ul>
                
                <?php if (!empty($type->eligibility_criteria)) : ?>
                    <div class="ls-eligibility-info">
                        <h4><?php _e('Eligibility:', 'loan-services'); ?></h4>
                        <p><?php echo nl2br(esc_html($type->eligibility_criteria)); ?></p>
                    </div>
                <?php endif; ?>
                
                <div class="ls-card-footer">
                    <a href="<?php echo add_query_arg('loan_type', $type->id, get_permalink(get_page_by_path('apply-for-loan'))); ?>" 
                       class="ls-apply-btn">
                        <?php _e('Apply Now', 'loan-services'); ?>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>