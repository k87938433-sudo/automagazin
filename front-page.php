<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
?>
<div class="site-content">
    <main class="content-area">
        <h1>Интернет-магазин автозапчастей</h1>
        <p>Добро пожаловать на сайт «Автомагазин». Здесь вы можете подобрать и заказать необходимые запчасти, расходные материалы и аксессуары для вашего автомобиля.</p>
        <h2>Популярные товары</h2>
        <div class="card-grid">
            <?php
            $popular = new WP_Query([
                'post_type'      => 'product',
                'posts_per_page' => 8,
            ]);
            if ( $popular->have_posts() ) :
                while ( $popular->have_posts() ) : $popular->the_post();
                    $price = get_post_meta( get_the_ID(), '_automagazin_adv_price', true );
                    ?>
                    <article class="card" id="post-<?php the_ID(); ?>">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
                        <?php endif; ?>
                        <h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <?php if ( $price ) : ?>
                            <div class="card-price"><?php echo esc_html( $price ); ?> грн</div>
                        <?php endif; ?>
                        <a class="card-button" href="<?php the_permalink(); ?>">Подробнее</a>
                    </article>
                <?php endwhile;
                wp_reset_postdata();
            else : ?>
                <p>Товары пока не добавлены.</p>
            <?php endif; ?>
        </div>
    </main>
    <?php get_sidebar(); ?>
</div>
<?php
get_footer();
