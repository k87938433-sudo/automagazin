<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
?>
<div class="site-content">
    <main class="content-area">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>">
                    <h1><?php the_title(); ?></h1>
                    <?php the_content(); ?>
                </article>
            <?php endwhile; ?>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p>Записей не найдено.</p>
        <?php endif; ?>
    </main>
    <?php get_sidebar(); ?>
</div>
<?php
get_footer();
