<?php

/*
* Add "Unused Media" menu in WordPress Dashboard
*/
function unused_media_menu()
{
    add_menu_page(
        'Unused Media',
        'Unused Media',
        'manage_options',
        'unused-media',
        'unused_media_page',
        'dashicons-trash',
        20
    );
}
add_action('admin_menu', 'unused_media_menu');

/*
* Display the "Unused Media" Page
*/
function unused_media_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
?>
    <div class="wrap">
        <h1>Unused Media Files</h1>
        <p>Below is a list of media files that are not linked to any post, page, or custom field.</p>
        <button id="scan-unused-media" class="button button-primary">Scan for Unused Media</button>
        <div id="unused-media-list"></div>
    </div>
<?php
}

/*
* Fetch Unused Media via AJAX
*/
function fetch_unused_media()
{
    check_ajax_referer('custom_admin_script_nonce', 'security');

    global $wpdb;
    $unused_media = array();

    // Get all media attachments
    $media_files = get_posts(array(
        'post_type'      => 'attachment',
        'posts_per_page' => -1,
        'post_status'    => 'inherit'
    ));

    // Get custom logo ID & site icon ID
    $custom_logo_id = get_theme_mod('custom_logo');
    $site_icon_id   = get_option('site_icon');

    // Get all theme options
    $theme_options = get_option('theme_mods_' . get_stylesheet());

    foreach ($media_files as $media) {
        $media_id  = $media->ID;
        $file_url  = wp_get_attachment_url($media_id);
        $file_name = basename($file_url);

        // Exclude logo and site icon
        if ($media_id == $custom_logo_id || $media_id == $site_icon_id) {
            continue;
        }

        // Check if media is used in post/page content
        $content_check = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_content LIKE %s",
            '%' . $wpdb->esc_like($file_url) . '%'
        ));

        // Check if media is used in custom fields
        $meta_check = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_value LIKE %s OR meta_value = %d",
            '%' . $wpdb->esc_like($file_url) . '%',
            $media_id
        ));

        // Check if media is used as featured image
        $featured_check = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->posts} WHERE meta_key = '_thumbnail_id' AND meta_value = %d",
            $media_id
        ));

        // Check if media is attached to any post/page
        $attached_check = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->posts} WHERE ID = %d AND post_parent > 0",
            $media_id
        ));

        // Check if media is inside theme options (header images, banners, etc.)
        $theme_check = 0;
        if (!empty($theme_options) && is_array($theme_options)) {
            foreach ($theme_options as $key => $value) {
                if (is_string($value) && strpos($value, $file_url) !== false) {
                    $theme_check = 1;
                    break;
                } elseif ($value == $media_id) {
                    $theme_check = 1;
                    break;
                }
            }
        }

        // Mark media as unused if not found in any check
        if ($content_check == 0 && $meta_check == 0 && $featured_check == 0 && $attached_check == 0 && $theme_check == 0) {
            $unused_media[] = array(
                'id'   => $media_id,
                'name' => $file_name,
                'url'  => $file_url
            );
        }
    }

    // Display results
    if (!empty($unused_media)) {
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead><tr><th>File Name</th><th>Preview</th><th>Actions</th></tr></thead>';
        echo '<tbody>';
        foreach ($unused_media as $media) {
            echo '<tr>';
            echo '<td>' . esc_html($media['name']) . '</td>';
            echo '<td><img src="' . esc_url($media['url']) . '" width="100"></td>';
            echo '<td><button class="button delete-media" data-id="' . esc_attr($media['id']) . '">Delete</button></td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<p>No unused media found.</p>';
    }

    wp_die();
}
add_action('wp_ajax_fetch_unused_media', 'fetch_unused_media');

/*
* Delete Unused Media via AJAX
*/
function delete_unused_media()
{
    check_ajax_referer('custom_admin_script_nonce', 'security');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized action.');
    }

    $media_id = isset($_POST['media_id']) ? intval($_POST['media_id']) : 0;

    if ($media_id > 0) {
        $deleted = wp_delete_attachment($media_id, true);

        if ($deleted) {
            wp_send_json_success('Media deleted successfully.');
        } else {
            wp_send_json_error('Error deleting media.');
        }
    } else {
        wp_send_json_error('Invalid media ID.');
    }

    wp_die();
}
add_action('wp_ajax_delete_unused_media', 'delete_unused_media');
