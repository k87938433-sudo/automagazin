<?php
/**
 * Template Name: Оформление заказа
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
        <?php
        $status = $_GET['checkout'] ?? '';
        if ( $status === 'success' ) {
            echo '<p class="notice-success">Спасибо! Ваш заказ отправлен. Наш менеджер свяжется с вами.</p>';
        } elseif ( $status === 'error' ) {
            echo '<p class="notice-error">Произошла ошибка при отправке заказа. Попробуйте ещё раз.</p>';
        } elseif ( $status === 'empty' ) {
            echo '<p class="notice-error">Корзина пуста. Добавьте товары перед оформлением заказа.</p>';
        }
        ?>
        <?php if ( empty( $cart ) ) : ?>
            <p>Корзина пуста.</p>
        <?php else : ?>
            <h2>Состав заказа</h2>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Цена, грн</th>
                        <th>Количество</th>
                        <th>Сумма, грн</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $cart as $product_id => $qty ) :
                        $title = get_the_title( $product_id );
                        $price = floatval( get_post_meta( $product_id, '_automagazin_adv_price', true ) );
                        $sum   = $price * $qty;
                        ?>
                        <tr>
                            <td><?php echo esc_html( $title ); ?></td>
                            <td><?php echo number_format( $price, 2, ',', ' ' ); ?></td>
                            <td><?php echo intval( $qty ); ?></td>
                            <td><?php echo number_format( $sum, 2, ',', ' ' ); ?></td>
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
            <h2>Контактные данные</h2>
            <form method="post" class="checkout-form">
                <label>ФИО:
                    <input type="text" name="customer_name" required>
                </label>
                <label>Телефон:
                    <input type="text" name="customer_phone" required>
                </label>
                <label>Email:
                    <input type="email" name="customer_email">
                </label>
                <label>Комментарий к заказу:
                    <textarea name="customer_comment" rows="4"></textarea>
                </label>
                <button type="submit" name="automagazin_adv_checkout" class="button">Отправить заказ</button>
            </form>
        <?php endif; ?>
    </main>
    <?php get_sidebar(); ?>
</div>
<?php
get_footer();
