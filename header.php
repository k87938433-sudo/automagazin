<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="site-header">
    <div class="container">
        <div class="site-branding">
            <div class="site-logo">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Автомагазин</a>
            </div>
            <div class="site-header-cart">
                <?php echo do_shortcode('[automagazin_cart_link]'); ?>
            </div>
        </div>
        <nav class="main-nav" aria-label="Основное меню">
            <?php
            wp_nav_menu( [
                'theme_location' => 'main_menu',
                'container'      => false,
                'fallback_cb'    => false,
            ] );
            ?>
        </nav>
    </div>
</header>
<div class="container site-wrapper">
