jQuery(document).ready(function($) {
    'use strict';
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Handle application status update
    $('.ls-update-status').on('click', function(e) {
        e.preventDefault();
        
        var applicationId = $(this).data('id');
        var newStatus = $(this).data('status');
        var $row = $(this).closest('tr');
        
        $.ajax({
            url: ls_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'update_application_status',
                application_id: applicationId,
                status: newStatus,
                nonce: ls_ajax.nonce
            },
            beforeSend: function() {
                $row.addClass('ls-updating');
            },
            success: function(response) {
                if (response.success) {
                    $row.removeClass('ls-updating').addClass('ls-updated');
                    updateStatusBadge($row, newStatus);
                    showNotification('Application status updated successfully', 'success');
                }
            },
            error: function() {
                $row.removeClass('ls-updating');
                showNotification('Error updating application status', 'error');
            }
        });
    });
    
    // Handle application deletion
    $('.ls-delete-application').on('click', function(e) {
        e.preventDefault();
        
        if (!confirm('Are you sure you want to delete this application?')) {
            return;
        }
        
        var applicationId = $(this).data('id');
        var $row = $(this).closest('tr');
        
        $.ajax({
            url: ls_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'delete_application',
                application_id: applicationId,
                nonce: ls_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $row.fadeOut(400, function() {
                        $(this).remove();
                    });
                    showNotification('Application deleted successfully', 'success');
                }
            }
        });
    });
    
    // Handle bulk actions
    $('#ls-bulk-actions').on('change', function() {
        var action = $(this).val();
        if (action) {
            var selected = [];
            $('.ls-application-checkbox:checked').each(function() {
                selected.push($(this).val());
            });
            
            if (selected.length === 0) {
                showNotification('Please select at least one application', 'warning');
                return;
            }
            
            processBulkAction(action, selected);
        }
    });
    
    // Export applications
    $('#ls-export-applications').on('click', function(e) {
        e.preventDefault();
        
        var filters = {
            status: $('#filter-status').val(),
            date_from: $('#filter-date-from').val(),
            date_to: $('#filter-date-to').val()
        };
        
        $.ajax({
            url: ls_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'export_applications',
                filters: filters,
                nonce: ls_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    downloadCSV(response.data.csv, 'loan-applications.csv');
                    showNotification('Export completed successfully', 'success');
                }
            }
        });
    });
    
    // Handle loan type form
    $('#ls-loan-type-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        formData += '&action=save_loan_type&nonce=' + ls_ajax.nonce;
        
        $.ajax({
            url: ls_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showNotification('Loan type saved successfully', 'success');
                    setTimeout(function() {
                        window.location.href = 'admin.php?page=loan-services-types';
                    }, 1500);
                } else {
                    showNotification('Error saving loan type', 'error');
                }
            }
        });
    });
    
    // Settings form handling
    $('#ls-settings-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        formData += '&action=save_settings&nonce=' + ls_ajax.nonce;
        
        $.ajax({
            url: ls_ajax.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showNotification('Settings saved successfully', 'success');
                } else {
                    showNotification('Error saving settings', 'error');
                }
            }
        });
    });
    
    // Date range picker initialization
    if ($.fn.daterangepicker) {
        $('#filter-date-range').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });
        
        $('#filter-date-range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });
        
        $('#filter-date-range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    }
    
    // Helper functions
    function updateStatusBadge($row, status) {
        var $badge = $row.find('.ls-status');
        $badge.removeClass('ls-status-pending ls-status-approved ls-status-rejected')
              .addClass('ls-status-' + status)
              .text(status.charAt(0).toUpperCase() + status.slice(1));
    }
    
    function showNotification(message, type) {
        var $notification = $('<div class="ls-notification notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>');
        $('.ls-admin-wrap').prepend($notification);
        
        setTimeout(function() {
            $notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
    
    function processBulkAction(action, selected) {
        $.ajax({
            url: ls_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'bulk_application_action',
                bulk_action: action,
                applications: selected,
                nonce: ls_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Bulk action completed successfully', 'success');
                    location.reload();
                }
            }
        });
    }
    
    function downloadCSV(csv, filename) {
        var csvFile = new Blob([csv], {type: 'text/csv'});
        var downloadLink = document.createElement('a');
        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = 'none';
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }
    
    // Dashboard charts
    if ($('#ls-applications-chart').length) {
        $.ajax({
            url: ls_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_application_stats',
                nonce: ls_ajax.nonce
            },
            success: function(response) {
                if (response.success && Chart) {
                    var ctx = document.getElementById('ls-applications-chart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: response.data.labels,
                            datasets: [{
                                label: 'Applications',
                                data: response.data.values,
                                borderColor: '#007cba',
                                backgroundColor: 'rgba(0,124,186,0.1)',
                                tension: 0.1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                }
            }
        });
    }
});