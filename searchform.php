<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label>
        <span class="screen-reader-text">Поиск:</span>
        <input type="search" class="search-field" placeholder="Поиск по сайту…" value="<?php echo get_search_query(); ?>" name="s">
    </label>
    <button type="submit" class="button">Найти</button>
</form>
