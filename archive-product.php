<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
?>
<div class="site-content">
    <main class="content-area">
        <h1>Каталог автозапчастей</h1>
        <?php if ( have_posts() ) : ?>
            <div class="card-grid">
                <?php while ( have_posts() ) : the_post();
                    $price = get_post_meta( get_the_ID(), '_automagazin_adv_price', true );
                    ?>
                    <article class="card" id="post-<?php the_ID(); ?>">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
                        <?php endif; ?>
                        <h2 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <?php if ( $price ) : ?>
                            <div class="card-price"><?php echo esc_html( $price ); ?> грн</div>
                        <?php endif; ?>
                        <a class="card-button" href="<?php the_permalink(); ?>">Подробнее</a>
                    </article>
                <?php endwhile; ?>
            </div>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p>Товары не найдены.</p>
        <?php endif; ?>
    </main>
    <?php get_sidebar(); ?>
</div>
<?php
get_footer();
