<?php

/*
* Ajax Function Filter the Projects Posts
*/

function filter_projects()
{
    // Sanitize inputs
    $start_date = isset($_POST['start_date']) ? sanitize_text_field($_POST['start_date']) : '';
    $end_date = isset($_POST['end_date']) ? sanitize_text_field($_POST['end_date']) : '';

    $args = array(
        'post_type' => 'project',
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'AND',
        ),
    );

    // Add start date condition if provided
    if (!empty($start_date)) {
        $args['meta_query'][] = array(
            'key' => 'project_start_date',
            'value' => $start_date,
            'compare' => '>=', // Show projects with a start date on or after the specified date
            'type' => 'DATE',
        );
    }

    // Add end date condition if provided
    if (!empty($end_date)) {
        $args['meta_query'][] = array(
            'key' => 'project_end_date',
            'value' => $end_date,
            'compare' => '<=', // Show projects with an end date on or before the specified date
            'type' => 'DATE',
        );
    }

    $projects = new WP_Query($args);

    if ($projects->have_posts()) :
        while ($projects->have_posts()) : $projects->the_post();
            // Fetch all values in variables
            $post_id       = intval(get_the_ID());
            $title         = get_the_title();
            $permalink     = get_permalink();
            $featured_img  = get_the_post_thumbnail_url($post_id, 'full'); // Full-size featured image
            $start_date    = get_field('project_start_date');
            $end_date      = get_field('project_end_date');
            $project_url   = get_field('project_url');
            $content = get_the_content();
?>
            <article id="project-<?php echo esc_attr($post_id); ?>" class="project-card">

                <?php if ($featured_img) : ?>
                    <img class="project-img" src="<?php echo esc_url($featured_img); ?>" alt="<?php echo esc_attr($title); ?>">
                <?php endif; ?>

                <!-- Project Title -->
                <h2 class="project-title">
                    <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
                </h2>

                <!-- Meta Info -->
                <div class="project-meta">
                    <?php if ($start_date) : ?>
                        <p><strong>Start Date:</strong> <?php echo esc_html($start_date); ?></p>
                    <?php endif; ?>
                    <?php if ($end_date) : ?>
                        <p><strong>End Date:</strong> <?php echo esc_html($end_date); ?></p>
                    <?php endif; ?>
                    <?php if ($project_url) : ?>
                        <p><strong>Project URL:</strong> <a href="<?php echo esc_url($project_url); ?>" target="_blank">Visit Project</a></p>
                    <?php endif; ?>
                </div>

                <!-- Project Description -->
                <div class="project-content">
                    <p><?php echo wp_kses_post($content); ?></p>
                </div>

                <!-- Read More Button -->
                <a class="read-more-btn" href="<?php echo esc_url($permalink); ?>">Read More</a>

            </article>
<?php
        endwhile;
    else :
        echo '<p>No projects found.</p>';
    endif;

    wp_reset_postdata();

    wp_die();
}
add_action('wp_ajax_filter_projects', 'filter_projects'); // For logged-in users
add_action('wp_ajax_nopriv_filter_projects', 'filter_projects'); // For non-logged-in users