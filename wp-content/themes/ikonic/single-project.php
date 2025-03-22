<?php

/*
* Single Detail Page for project
*/

get_header();
?>
<main class="site-main">
    <div class="container">
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                the_title('<h1>', '</h1>');
                the_content();
                echo '<p><strong>Start Date:</strong> ' . get_field('project_start_date') . '</p>';
                echo '<p><strong>End Date:</strong> ' . get_field('project_end_date') . '</p>';
                echo '<p><strong>Project URL:</strong> <a href="' . get_field('project_url') . '">' . get_field('project_url') . '</a></p>';
            endwhile;
        endif;
        ?>
    </div>
</main>
<?php
get_footer();
