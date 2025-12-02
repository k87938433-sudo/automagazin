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
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'product' ) ); ?>">Каталог</a> /
                    <span><?php the_title(); ?></span>
                </div>
                <h1><?php the_title(); ?></h1>
                <div class="product-layout">
                    <div class="product-media">
                        <?php if ( has_post_thumbnail() ) { the_post_thumbnail('large'); } ?>
                    </div>
                    <div class="product-info">
                        <?php
                        $price = get_post_meta( get_the_ID(), '_automagazin_adv_price', true );
                        if ( $price ) : ?>
                            <div class="card-price"><?php echo esc_html( $price ); ?> грн</div>
                        <?php endif; ?>
                        <form method="post">
                            <input type="hidden" name="product_id" value="<?php echo esc_attr( get_the_ID() ); ?>">
                            <label>Количество:
                                <input type="number" name="quantity" value="1" min="1" style="width:80px;">
                            </label>
                            <button type="submit" name="automagazin_adv_add_to_cart" class="card-button">Добавить в корзину</button>
                        </form>
                        <?php if ( isset( $_GET['added-to-cart'] ) ) : ?>
                            <p class="notice-success">Товар добавлен в корзину.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <h2>Описание</h2>
                <?php the_content(); ?>
            </article>
        <?php endwhile; else : ?>
            <p>Товар не найден.</p>
        <?php endif; ?>
    </main>
    <?php get_sidebar(); ?>
</div>
<?php
get_footer();
