<?php
/**
 * Template Name: Loan Application Success
 */

get_header(); ?>

<div class="ls-success-container">
    <div class="ls-success-card">
        <div class="ls-success-icon">
            <span class="dashicons dashicons-yes-alt"></span>
        </div>
        
        <h1><?php _e('Application Submitted Successfully!', 'loan-services'); ?></h1>
        
        <p class="ls-success-message">
            <?php _e('Thank you for submitting your loan application. We have received your request and will review it shortly.', 'loan-services'); ?>
        </p>
        
        <div class="ls-success-details">
            <h3><?php _e('What happens next?', 'loan-services'); ?></h3>
            
            <ol class="ls-steps-list">
                <li>
                    <strong><?php _e('Application Review:', 'loan-services'); ?></strong>
                    <?php _e('Our team will review your application within 24-48 hours.', 'loan-services'); ?>
                </li>
                <li>
                    <strong><?php _e('Document Verification:', 'loan-services'); ?></strong>
                    <?php _e('We may contact you for additional documents if needed.', 'loan-services'); ?>
                </li>
                <li>
                    <strong><?php _e('Approval Decision:', 'loan-services'); ?></strong>
                    <?php _e('You will receive an email with the approval decision.', 'loan-services'); ?>
                </li>
                <li>
                    <strong><?php _e('Disbursement:', 'loan-services'); ?></strong>
                    <?php _e('Upon approval, the loan amount will be disbursed to your account.', 'loan-services'); ?>
                </li>
            </ol>
        </div>
        
        <div class="ls-success-actions">
            <h3><?php _e('Application Reference', 'loan-services'); ?></h3>
            <p class="ls-ref-number">
                <?php 
                $application_id = isset($_GET['application_id']) ? intval($_GET['application_id']) : 0;
                if ($application_id) {
                    echo sprintf(__('Your application reference number is: #%d', 'loan-services'), $application_id);
                }
                ?>
            </p>
            
            <p><?php _e('Please save this number for future reference.', 'loan-services'); ?></p>
        </div>
        
        <div class="ls-success-footer">
            <a href="<?php echo home_url(); ?>" class="ls-button ls-button-primary">
                <?php _e('Return to Home', 'loan-services'); ?>
            </a>
            <a href="<?php echo get_permalink(get_page_by_path('contact-us')); ?>" class="ls-button ls-button-secondary">
                <?php _e('Contact Support', 'loan-services'); ?>
            </a>
        </div>
    </div>
</div>

<style>
.ls-success-container {
    max-width: 800px;
    margin: 50px auto;
    padding: 0 20px;
}

.ls-success-card {
    background: #fff;
    border-radius: 10px;
    padding: 40px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    text-align: center;
}

.ls-success-icon {
    width: 80px;
    height: 80px;
    background: #27ae60;
    border-radius: 50%;
    margin: 0 auto 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.ls-success-icon .dashicons {
    color: #fff;
    font-size: 40px;
    width: 40px;
    height: 40px;
}

.ls-success-card h1 {
    color: #333;
    margin-bottom: 15px;
}

.ls-success-message {
    font-size: 18px;
    color: #666;
    margin-bottom: 30px;
}

.ls-success-details {
    text-align: left;
    background: #f9f9f9;
    padding: 25px;
    border-radius: 8px;
    margin: 30px 0;
}

.ls-steps-list {
    list-style-position: inside;
    padding-left: 0;
}

.ls-steps-list li {
    margin-bottom: 15px;
    color: #555;
}

.ls-steps-list strong {
    color: #333;
}

.ls-ref-number {
    font-size: 24px;
    font-weight: bold;
    color: #007cba;
    padding: 15px;
    background: #f0f8ff;
    border-radius: 5px;
}

.ls-success-footer {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
}

.ls-button {
    padding: 12px 25px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s;
}

.ls-button-primary {
    background: #007cba;
    color: #fff;
}

.ls-button-primary:hover {
    background: #005a87;
    color: #fff;
}

.ls-button-secondary {
    background: #f1f1f1;
    color: #333;
}

.ls-button-secondary:hover {
    background: #e1e1e1;
    color: #333;
}

@media (max-width: 768px) {
    .ls-success-card {
        padding: 25px;
    }
    
    .ls-success-footer {
        flex-direction: column;
    }
}
</style>

<?php get_footer(); ?>