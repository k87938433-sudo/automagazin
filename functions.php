<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Запуск сессии для хранения корзины
 */
function automagazin_adv_start_session() {
    if ( ! session_id() ) {
        session_start();
    }
}
add_action( 'init', 'automagazin_adv_start_session', 1 );

/**
 * Подключение стилей и скриптов
 */
function automagazin_adv_enqueue_assets() {
    wp_enqueue_style( 'automagazin-advanced-style', get_stylesheet_uri(), [], '2.0' );
    wp_enqueue_script(
        'automagazin-advanced-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [ 'jquery' ],
        '2.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'automagazin_adv_enqueue_assets' );

/**
 * Настройки темы
 */
function automagazin_adv_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'gallery', 'caption' ] );

    register_nav_menus( [
        'main_menu'   => 'Основное меню',
        'footer_menu' => 'Меню в подвале',
    ] );
}
add_action( 'after_setup_theme', 'automagazin_adv_setup' );

/**
 * Виджеты
 */
function automagazin_adv_widgets_init() {
    register_sidebar( [
        'name'          => 'Сайдбар',
        'id'            => 'sidebar-1',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ] );
}
add_action( 'widgets_init', 'automagazin_adv_widgets_init' );

/**
 * Тип записей: товары
 */
function automagazin_adv_register_product_cpt() {
    $labels = [
        'name'          => 'Товары',
        'singular_name' => 'Товар',
        'add_new'       => 'Добавить товар',
        'add_new_item'  => 'Добавить новый товар',
        'edit_item'     => 'Редактировать товар',
        'new_item'      => 'Новый товар',
        'view_item'     => 'Просмотреть товар',
        'search_items'  => 'Искать товары',
        'menu_name'     => 'Товары',
    ];

    $args = [
        'labels'       => $labels,
        'public'       => true,
        'has_archive'  => true,
        'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'rewrite'      => [ 'slug' => 'products' ],
        'show_in_rest' => true,
        'menu_icon'    => 'dashicons-car',
    ];

    register_post_type( 'product', $args );
}
add_action( 'init', 'automagazin_adv_register_product_cpt' );

/**
 * Простейший meta description
 */
function automagazin_adv_meta_description() {
    if ( is_singular() ) {
        global $post;
        $description = get_the_excerpt( $post );
    } else {
        $description = get_bloginfo( 'description' );
    }
    if ( $description ) {
        $description = wp_strip_all_tags( $description );
        $description = esc_attr( mb_substr( $description, 0, 160 ) );
        echo '<meta name="description" content="' . $description . '">' . "\n";
    }
}
add_action( 'wp_head', 'automagazin_adv_meta_description', 1 );

/**
 * Мета-поля для цены товара
 */
function automagazin_adv_add_price_metabox() {
    add_meta_box(
        'automagazin_adv_price',
        'Цена товара, грн',
        'automagazin_adv_price_metabox_html',
        'product',
        'side'
    );
}
add_action( 'add_meta_boxes', 'automagazin_adv_add_price_metabox' );

function automagazin_adv_price_metabox_html( $post ) {
    $value = get_post_meta( $post->ID, '_automagazin_adv_price', true );
    ?>
    <label for="automagazin_adv_price">Цена, грн:</label>
    <input type="number" name="automagazin_adv_price" id="automagazin_adv_price" value="<?php echo esc_attr( $value ); ?>" style="width:100%;">
    <?php
}

function automagazin_adv_save_price_metabox( $post_id ) {
    if ( array_key_exists( 'automagazin_adv_price', $_POST ) ) {
        update_post_meta(
            $post_id,
            '_automagazin_adv_price',
            floatval( $_POST['automagazin_adv_price'] )
        );
    }
}
add_action( 'save_post_product', 'automagazin_adv_save_price_metabox' );

/**
 * Работа с корзиной (SESSION)
 */
function automagazin_adv_get_cart() {
    if ( ! isset( $_SESSION['automagazin_adv_cart'] ) || ! is_array( $_SESSION['automagazin_adv_cart'] ) ) {
        $_SESSION['automagazin_adv_cart'] = [];
    }
    return $_SESSION['automagazin_adv_cart'];
}

function automagazin_adv_set_cart( $cart ) {
    $_SESSION['automagazin_adv_cart'] = $cart;
}

/**
 * Обработчик добавления/удаления в корзину
 */
function automagazin_adv_handle_cart_actions() {
    if ( ! is_user_logged_in() && ! isset( $_POST['automagazin_adv_add_to_cart'] ) && ! isset( $_GET['automagazin_adv_remove'] ) ) {
        // можно не проверять логин, корзина для всех
    }

    // Добавление
    if ( isset( $_POST['automagazin_adv_add_to_cart'] ) && isset( $_POST['product_id'] ) ) {
        $product_id = intval( $_POST['product_id'] );
        $qty        = isset( $_POST['quantity'] ) ? max( 1, intval( $_POST['quantity'] ) ) : 1;

        $cart = automagazin_adv_get_cart();
        if ( isset( $cart[ $product_id ] ) ) {
            $cart[ $product_id ] += $qty;
        } else {
            $cart[ $product_id ] = $qty;
        }
        automagazin_adv_set_cart( $cart );

        wp_safe_redirect( add_query_arg( 'added-to-cart', '1', get_permalink( $product_id ) ) );
        exit;
    }

    // Удаление
    if ( isset( $_GET['automagazin_adv_remove'] ) ) {
        $product_id = intval( $_GET['automagazin_adv_remove'] );
        $cart = automagazin_adv_get_cart();
        if ( isset( $cart[ $product_id ] ) ) {
            unset( $cart[ $product_id ] );
            automagazin_adv_set_cart( $cart );
        }
        wp_safe_redirect( remove_query_arg( [ 'automagazin_adv_remove' ] ) );
        exit;
    }
}
add_action( 'template_redirect', 'automagazin_adv_handle_cart_actions' );

/**
 * Подсчёт суммы корзины
 */
function automagazin_adv_calculate_cart_total( $cart ) {
    $total = 0;
    foreach ( $cart as $product_id => $qty ) {
        $price = floatval( get_post_meta( $product_id, '_automagazin_adv_price', true ) );
        $total += $price * $qty;
    }
    return $total;
}

/**
 * Шорткод для ссылки на корзину с количеством
 */
function automagazin_adv_cart_link_shortcode() {
    $cart = automagazin_adv_get_cart();
    $count = array_sum( $cart );
    $page = get_page_by_path( 'cart' );
    if ( $page ) {
        $url = get_permalink( $page->ID );
        return '<a href="' . esc_url( $url ) . '">Корзина (' . intval( $count ) . ')</a>';
    }
    return '';
}
add_shortcode( 'automagazin_cart_link', 'automagazin_adv_cart_link_shortcode' );

/**
 * Обработка оформления заказа
 */
function automagazin_adv_handle_checkout() {
    if ( isset( $_POST['automagazin_adv_checkout'] ) ) {
        $name    = sanitize_text_field( $_POST['customer_name'] ?? '' );
        $phone   = sanitize_text_field( $_POST['customer_phone'] ?? '' );
        $email   = sanitize_email( $_POST['customer_email'] ?? '' );
        $comment = sanitize_textarea_field( $_POST['customer_comment'] ?? '' );

        $cart = automagazin_adv_get_cart();
        if ( empty( $cart ) ) {
            wp_safe_redirect( add_query_arg( 'checkout', 'empty', wp_get_referer() ) );
            exit;
        }

        $lines = [];
        $total = 0;
        foreach ( $cart as $product_id => $qty ) {
            $title = get_the_title( $product_id );
            $price = floatval( get_post_meta( $product_id, '_automagazin_adv_price', true ) );
            $sum   = $price * $qty;
            $total += $sum;
            $lines[] = sprintf(
                "%s (ID: %d) — %d шт. × %.2f = %.2f грн",
                $title,
                $product_id,
                $qty,
                $price,
                $sum
            );
        }

        $body  = "Новый заказ с сайта «Автомагазин»:\n\n";
        $body .= "Имя: $name\nТелефон: $phone\nEmail: $email\nКомментарий: $comment\n\n";
        $body .= "Товары:\n" . implode( "\n", $lines ) . "\n\n";
        $body .= "Итого: " . number_format( $total, 2, ',', ' ' ) . " грн\n";

        $to      = get_option( 'admin_email' );
        $subject = 'Новый заказ с сайта Автомагазин';
        $headers = [ 'Content-Type: text/plain; charset=UTF-8' ];

        if ( wp_mail( $to, $subject, $body, $headers ) ) {
            // очищаем корзину
            automagazin_adv_set_cart( [] );
            wp_safe_redirect( add_query_arg( 'checkout', 'success', wp_get_referer() ) );
            exit;
        } else {
            wp_safe_redirect( add_query_arg( 'checkout', 'error', wp_get_referer() ) );
            exit;
        }
    }
}
add_action( 'template_redirect', 'automagazin_adv_handle_checkout', 11 );
