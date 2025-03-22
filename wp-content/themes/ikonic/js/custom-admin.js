/*
* for unused images
*/
jQuery(document).ready(function ($) {
    $('#scan-unused-media').click(function () {
        $.ajax({
            url: custom_admin_script_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'fetch_unused_media',
                security: custom_admin_script_vars.nonce
            },
            beforeSend: function () {
                $('#unused-media-list').html('<p>Scanning for unused media...</p>');
            },
            success: function (response) {
                $('#unused-media-list').html(response);
            }
        });
    });

    $(document).on('click', '.delete-media', function () {
        var mediaId = $(this).data('id');
        var row = $(this).closest('tr');

        if (confirm('Are you sure you want to delete this media file?')) {
            $.ajax({
                url: custom_admin_script_vars.ajax_url,
                type: 'POST',
                data: {
                    action: 'delete_unused_media',
                    media_id: mediaId,
                    security: custom_admin_script_vars.nonce
                },
                success: function (response) {
                    if (response.success) {
                        row.remove();
                    } else {
                        alert('Failed to delete media. Try again.');
                    }
                }
            });
        }
    });
});
