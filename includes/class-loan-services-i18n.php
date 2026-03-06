<?php
/**
 * Define the internationalization functionality
 *
 * @package    Loan_Services
 * @subpackage Loan_Services/includes
 */

class LS_i18n {

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'loan-services',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );
    }
}