<?php
// Проверяем, зарегистрировано ли меню
if (has_nav_menu('sidebar-menu')) : ?>
    <!-- Десктопное меню -->
    <div class="front-menu-desktop">
        <nav class="nav flex-column sidebar-nav">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'sidebar-menu',
                'container'      => false,
                'menu_class'     => 'nav flex-column',
                'fallback_cb'    => false,
                'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
                'depth'          => 1,
                'walker'         => new Bootstrap_5_Nav_Walker()
            ));
            ?>
        </nav>
    </div>
<?php endif; ?>

<?php
// Проверяем, зарегистрировано ли дополнительное меню
if (has_nav_menu('additional-menu')) : ?>
    <!-- Дополнительное меню с заголовком -->
    <div class="front-menu-desktop">
        <br/>
        <nav class="nav flex-column sidebar-nav">
            <strong class="ps-3 mb-2 fw-bold">Полезное</strong>
            <?php
            wp_nav_menu(array(
                'theme_location' => 'additional-menu',
                'container'      => false,
                'menu_class'     => 'nav flex-column',
                'fallback_cb'    => false,
                'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
                'depth'          => 1,
                'walker'         => new Bootstrap_5_Nav_Walker()
            ));
            ?>
        </nav>
    </div>
<?php endif; ?>


<?php
// Проверяем, зарегистрировано ли блоковое меню
if (has_nav_menu('block-menu')) : ?>
    <!-- Дополнительное меню с заголовком -->
    <div class="block-menu-desktop">
        <br/>
        <nav class="nav flex-column sidebar-nav py-2 px-2 rounded-3">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'block-menu',
                'container'      => false,
                'menu_class'     => 'nav flex-column',
                'fallback_cb'    => false,
                'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
                'depth'          => 1,
                'walker'         => new Bootstrap_5_Nav_Walker()
            ));
            ?>
        </nav>
    </div>
<?php endif; ?>
