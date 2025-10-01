
    <!-- Мобильное меню -->
    <div class="front-menu-mobile offcanvas-md offcanvas-start d-md-none bg-body-tertiary" tabindex="-1" id="mobileSidebarMenu" aria-labelledby="mobileSidebarMenuLabel" style="width: 280px;">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="mobileSidebarMenuLabel">Меню</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#mobileSidebarMenu" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <?php
            // Проверяем, зарегистрировано ли меню
            if (has_nav_menu('sidebar-menu')) : ?>
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
            <?php endif; ?>

            <?php
            // Проверяем, зарегистрировано ли дополнительное меню
            if (has_nav_menu('additional-menu')) : ?>
                <!-- Дополнительное меню с заголовком для мобильной версии -->
                <br/>
                <strong class="ps-3 mb-2 mt-4">Полезное</strong>
                <nav class="nav flex-column sidebar-nav">
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
            <?php endif; ?>

            <?php
            // Проверяем, зарегистрировано ли блоковое меню
            if (has_nav_menu('block-menu')) : ?>
                <!-- Блоковое меню для мобильной версии -->
                <br/>
                <nav class="nav flex-column sidebar-nav py-2 px-2 rounded-3 block-menu">
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
            <?php endif; ?>
        </div>
    </div>
