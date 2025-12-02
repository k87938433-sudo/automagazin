<?php
/**
 * Template Name: Корзина
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
$cart = automagazin_adv_get_cart();
?>
<div class="site-content">
    <main class="content-area">
        <h1><?php the_title(); ?></h1>
        <?php if ( empty( $cart ) ) : ?>
            <p>Ваша корзина пуста.</p>
        <?php else : ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Цена, грн</th>
                        <th>Количество</th>
                        <th>Сумма, грн</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $cart as $product_id => $qty ) :
                        $title = get_the_title( $product_id );
                        $price = floatval( get_post_meta( $product_id, '_automagazin_adv_price', true ) );
                        $sum   = $price * $qty;
                        ?>
                        <tr>
                            <td><a href="<?php echo get_permalink( $product_id ); ?>"><?php echo esc_html( $title ); ?></a></td>
                            <td><?php echo number_format( $price, 2, ',', ' ' ); ?></td>
                            <td><?php echo intval( $qty ); ?></td>
                            <td><?php echo number_format( $sum, 2, ',', ' ' ); ?></td>
                            <td><a href="<?php echo esc_url( add_query_arg( 'automagazin_adv_remove', $product_id ) ); ?>">Удалить</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="cart-total">
                <?php
                $total = automagazin_adv_calculate_cart_total( $cart );
                echo 'Итого: ' . number_format( $total, 2, ',', ' ' ) . ' грн';
                ?>
            </div>
            <?php
            $checkout_page = get_page_by_path( 'checkout' );
            if ( $checkout_page ) :
                ?>
                <p style="text-align:right; margin-top:15px;">
                    <a class="button" href="<?php echo esc_url( get_permalink( $checkout_page->ID ) ); ?>">Перейти к оформлению заказа</a>
                </p>
            <?php endif; ?>
        <?php endif; ?>
    </main>
    <?php get_sidebar(); ?>
</div>
<?php
get_footer();
