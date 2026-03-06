<div class="wrap ls-admin-wrap">
    <h1><?php _e('Loan Services Settings', 'loan-services'); ?></h1>
    
    <?php
    // Save settings
    if (isset($_POST['ls_save_settings']) && check_admin_referer('ls_settings_nonce')) {
        update_option('ls_general_settings', $_POST['general']);
        update_option('ls_email_settings', $_POST['email']);
        update_option('ls_payment_settings', $_POST['payment']);
        echo '<div class="notice notice-success"><p>' . __('Settings saved.', 'loan-services') . '</p></div>';
    }
    
    $general = get_option('ls_general_settings', array());
    $email = get_option('ls_email_settings', array());
    $payment = get_option('ls_payment_settings', array());
    ?>
    
    <form method="post" action="" id="ls-settings-form">
        <?php wp_nonce_field('ls_settings_nonce'); ?>
        
        <h2 class="nav-tab-wrapper">
            <a href="#general" class="nav-tab nav-tab-active"><?php _e('General', 'loan-services'); ?></a>
            <a href="#email" class="nav-tab"><?php _e('Email', 'loan-services'); ?></a>
            <a href="#payment" class="nav-tab"><?php _e('Payment', 'loan-services'); ?></a>
            <a href="#notifications" class="nav-tab"><?php _e('Notifications', 'loan-services'); ?></a>
        </h2>
        
        <div id="general" class="tab-content">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="company_name"><?php _e('Company Name', 'loan-services'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="company_name" name="general[company_name]" 
                               value="<?php echo isset($general['company_name']) ? esc_attr($general['company_name']) : ''; ?>" 
                               class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="currency"><?php _e('Currency', 'loan-services'); ?></label>
                    </th>
                    <td>
                        <select id="currency" name="general[currency]">
                            <option value="USD" <?php selected(isset($general['currency']) ? $general['currency'] : '', 'USD'); ?>>USD ($)</option>
                            <option value="EUR" <?php selected(isset($general['currency']) ? $general['currency'] : '', 'EUR'); ?>>EUR (€)</option>
                            <option value="GBP" <?php selected(isset($general['currency']) ? $general['currency'] : '', 'GBP'); ?>>GBP (£)</option>
                            <option value="INR" <?php selected(isset($general['currency']) ? $general['currency'] : '', 'INR'); ?>>INR (₹)</option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="date_format"><?php _e('Date Format', 'loan-services'); ?></label>
                    </th>
                    <td>
                        <select id="date_format" name="general[date_format]">
                            <option value="d/m/Y" <?php selected(isset($general['date_format']) ? $general['date_format'] : '', 'd/m/Y'); ?>>DD/MM/YYYY</option>
                            <option value="m/d/Y" <?php selected(isset($general['date_format']) ? $general['date_format'] : '', 'm/d/Y'); ?>>MM/DD/YYYY</option>
                            <option value="Y-m-d" <?php selected(isset($general['date_format']) ? $general['date_format'] : '', 'Y-m-d'); ?>>YYYY-MM-DD</option>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="min_credit_score"><?php _e('Minimum Credit Score', 'loan-services'); ?></label>
                    </th>
                    <td>
                        <input type="number" id="min_credit_score" name="general[min_credit_score]" 
                               value="<?php echo isset($general['min_credit_score']) ? esc_attr($general['min_credit_score']) : '650'; ?>" 
                               min="300" max="850" class="small-text">
                        <p class="description"><?php _e('Minimum credit score required for loan approval', 'loan-services'); ?></p>
                    </td>
                </tr>
            </table>
        </div>
        
        <div id="email" class="tab-content" style="display:none;">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="from_email"><?php _e('From Email', 'loan-services'); ?></label>
                    </th>
                    <td>
                        <input type="email" id="from_email" name="email[from_email]" 
                               value="<?php echo isset($email['from_email']) ? esc_attr($email['from_email']) : get_option('admin_email'); ?>" 
                               class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="from_name"><?php _e('From Name', 'loan-services'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="from_name" name="email[from_name]" 
                               value="<?php echo isset($email['from_name']) ? esc_attr($email['from_name']) : get_bloginfo('name'); ?>" 
                               class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="admin_notifications"><?php _e('Admin Notifications', 'loan-services'); ?></label>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" name="email[admin_notifications]" value="1" 
                                   <?php checked(isset($email['admin_notifications']) ? $email['admin_notifications'] : 1, 1); ?>>
                            <?php _e('Send email notifications to admin for new applications', 'loan-services'); ?>
                        </label>
                    </td>
                </tr>
            </table>
        </div>
        
        <div id="payment" class="tab-content" style="display:none;">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="enable_payments"><?php _e('Enable Online Payments', 'loan-services'); ?></label>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" id="enable_payments" name="payment[enable_payments]" value="1" 
                                   <?php checked(isset($payment['enable_payments']) ? $payment['enable_payments'] : 0, 1); ?>>
                            <?php _e('Allow customers to make payments online', 'loan-services'); ?>
                        </label>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="payment_gateway"><?php _e('Payment Gateway', 'loan-services'); ?></label>
                    </th>
                    <td>
                        <select id="payment_gateway" name="payment[gateway]">
                            <option value="stripe" <?php selected(isset($payment['gateway']) ? $payment['gateway'] : '', 'stripe'); ?>>Stripe</option>
                            <option value="paypal" <?php selected(isset($payment['gateway']) ? $payment['gateway'] : '', 'paypal'); ?>>PayPal</option>
                            <option value="razorpay" <?php selected(isset($payment['gateway']) ? $payment['gateway'] : '', 'razorpay'); ?>>Razorpay</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        
        <div id="notifications" class="tab-content" style="display:none;">
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="enable_sms"><?php _e('Enable SMS Notifications', 'loan-services'); ?></label>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" id="enable_sms" name="notifications[enable_sms]" value="1" 
                                   <?php checked(isset($notifications['enable_sms']) ? $notifications['enable_sms'] : 0, 1); ?>>
                            <?php _e('Send SMS notifications to customers', 'loan-services'); ?>
                        </label>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="twilio_sid"><?php _e('Twilio Account SID', 'loan-services'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="twilio_sid" name="notifications[twilio_sid]" 
                               value="<?php echo isset($notifications['twilio_sid']) ? esc_attr($notifications['twilio_sid']) : ''; ?>" 
                               class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="twilio_token"><?php _e('Twilio Auth Token', 'loan-services'); ?></label>
                    </th>
                    <td>
                        <input type="password" id="twilio_token" name="notifications[twilio_token]" 
                               value="<?php echo isset($notifications['twilio_token']) ? esc_attr($notifications['twilio_token']) : ''; ?>" 
                               class="regular-text">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="twilio_phone"><?php _e('Twilio Phone Number', 'loan-services'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="twilio_phone" name="notifications[twilio_phone]" 
                               value="<?php echo isset($notifications['twilio_phone']) ? esc_attr($notifications['twilio_phone']) : ''; ?>" 
                               class="regular-text">
                    </td>
                </tr>
            </table>
        </div>
        
        <p class="submit">
            <input type="submit" name="ls_save_settings" class="button-primary" value="<?php _e('Save Settings', 'loan-services'); ?>">
        </p>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    // Tab switching
    $('.nav-tab-wrapper a').on('click', function(e) {
        e.preventDefault();
        $('.nav-tab-wrapper a').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $('.tab-content').hide();
        $($(this).attr('href')).show();
    });
});
</script>