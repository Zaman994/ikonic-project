<?php
get_header();
?>
<main class="site-main">
    <div class="container project-page">
        <h1>Projects</h1>
        <!-- Filter Form -->
        <form id="project-filter-form">
            <div class="start">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date">
            </div>
            <div class="end">
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" id="end_date">
            </div>
            <button class="filter-btn" type="submit">Filter</button>
        </form>

        <!-- Results Container -->
        <div id="project-results">
            <?php
            // Initial load of projects
            $args = array(
                'post_type' => 'project',
                'posts_per_page' => -1,
            );
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
            ?>
        </div>
    </div>
</main>
<?php
get_footer();
