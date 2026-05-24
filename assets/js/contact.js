$('#contact-form').on('submit', function(e) {
    e.preventDefault(); // Stop page from hard reloading

    const $form = $(this);
    const formData = new FormData(this); // 'this' refers to the raw HTML form element
    const $messageContainer = $('.form-message');
    const $submitButton = $form.find('button[type="submit"]');

    // Visual loading feedback
    $submitButton.prop('disabled', true).text('Sending...');

    // Async AJAX configuration pointing to your PHP script
    $.ajax({
        url: $form.attr('action'),
        type: 'POST',
        data: formData,
        processData: false, // Required for FormData
        contentType: false, // Required for FormData
        success: function(response) {
            // --- SECURITY INTERCEPTOR ---
            // If the server returns a script injection instead of 'Success', treat it as a firewall error
            if (typeof response === 'string' && response.includes('humans_21909')) {
                $messageContainer.css('color', 'red').text('Security Error: Request blocked by hosting firewall. Please try switching off Wi-Fi or submit from a desktop.');
                return; // Stop execution immediately so the modal doesn't show up!
            }

            // Success! Reveal the Thank You Modal
            $('#thankYouModal').addClass('show');
            $form[0].reset(); // Reset the form fields
            $messageContainer.text('');
        },
        error: function(xhr, status, error) {
            $messageContainer.css('color', 'red');
            
            // Fallback chain: Server text -> HTTP Status text -> Default catch-all
            const errorMsg = xhr.responseText || xhr.statusText || "Server error: " + xhr.status;
            $messageContainer.text(errorMsg);
        },
        complete: function() {
            // Restore standard button state regardless of success or failure
            $submitButton.prop('disabled', false).text('Send Message');
        }
    });
});

// Close Modal event handlers
$('#closeModalBtn').on('click', function() {
    $('#thankYouModal').removeClass('show');
});

// Optional: Dismiss modal if user clicks outside the inner main container box
$('#thankYouModal').on('click', function(e) {
    if (e.target === this) {
        $(this).removeClass('show');
    }
});