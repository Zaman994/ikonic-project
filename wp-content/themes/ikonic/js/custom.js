jQuery(document).ready(function ($) {
    /*
    *   Filter the Projects 
    */
    $('#project-filter-form').on('submit', function (e) {
        e.preventDefault(); // Prevent form submission

        // Get form data
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();

        // Send AJAX request
        $.ajax({
            url: custom_script_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'filter_projects', // WordPress AJAX action
                start_date: start_date,
                end_date: end_date,
            },
            success: function (response) {
                // Update results container
                $('#project-results').html(response);
            },
            error: function () {
                // Show error message
                $('#project-results').html('<p>An error occurred. Please try again.</p>');
            }
        });
    });

    /*
    *   For menu items
    */

    $('.primary-menu li').hover(function () {
        $(this).find('ul').toggle();
    });
});

