<?php
/*
Template Name: Blog
*/
get_header();
?>
<main class="site-main">
    <div class="container">
        <h1>Blogs</h1>
        <?php
        // Custom query to fetch blog posts
        $args = array(
            'post_type' => 'post',
            'posts_per_page' => -1,
        );
        $blog_query = new WP_Query($args);

        if ($blog_query->have_posts()) :
            while ($blog_query->have_posts()) : $blog_query->the_post();
        ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="entry-content">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
        <?php
            endwhile;

            wp_reset_postdata();
        else :
            // If no posts are found
            echo '<p>No blog posts found.</p>';
        endif;
        ?>
    </div>
</main>
<?php
get_footer();
