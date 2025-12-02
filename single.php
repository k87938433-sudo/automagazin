<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
?>
<div class="site-content">
    <main class="content-area">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>">
                <div class="breadcrumbs">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Главная</a> /
                    <span><?php the_title(); ?></span>
                </div>
                <h1><?php the_title(); ?></h1>
                <?php the_content(); ?>
            </article>
        <?php endwhile; else : ?>
            <p>Запись не найдена.</p>
        <?php endif; ?>
    </main>
    <?php get_sidebar(); ?>
</div>
<?php
get_footer();
