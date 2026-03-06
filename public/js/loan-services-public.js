jQuery(document).ready(function($) {
    // Form validation
    $('#ls-application-form').on('submit', function(e) {
        var isValid = true;
        
        // Basic validation
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('error');
                isValid = false;
            } else {
                $(this).removeClass('error');
            }
        });
        
        // Email validation
        var email = $('#email').val();
        if (email && !isValidEmail(email)) {
            $('#email').addClass('error');
            isValid = false;
        }
        
        // Phone validation
        var phone = $('#phone').val();
        if (phone && !isValidPhone(phone)) {
            $('#phone').addClass('error');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            showNotification('Please fill all required fields correctly', 'error');
        }
    });
    
    // Loan calculator
    $('#ls-calculator-form').on('submit', function(e) {
        e.preventDefault();
        
        var amount = $('#loan_amount').val();
        var rate = $('#interest_rate').val();
        var term = $('#loan_term').val();
        
        $.ajax({
            url: ls_public.ajax_url,
            type: 'POST',
            data: {
                action: 'calculate_loan_emi',
                amount: amount,
                rate: rate,
                term: term,
                nonce: ls_public.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#emi-amount').text(formatMoney(response.data.emi));
                    $('#total-payment').text(formatMoney(response.data.total_payment));
                    $('#total-interest').text(formatMoney(response.data.total_interest));
                    $('#calculator-result').show();
                }
            }
        });
    });
    
    // Helper functions
    function isValidEmail(email) {
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    function isValidPhone(phone) {
        var re = /^[\d\s\+\-\(\)]{10,}$/;
        return re.test(phone);
    }
    
    function formatMoney(amount) {
        return '₹' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }
    
    function showNotification(message, type) {
        var notification = $('<div class="ls-notification ' + type + '">' + message + '</div>');
        $('body').append(notification);
        
        setTimeout(function() {
            notification.fadeOut(function() {
                $(this).remove();
            });
        }, 3000);
    }
});