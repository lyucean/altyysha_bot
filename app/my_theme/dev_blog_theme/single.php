<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <!-- Заголовок поста -->
    <section class="post-hero-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="post-header text-center">

                        <!-- Заголовок -->
                        <h1 class="post-main-title"><?php the_title(); ?></h1>

                        <!-- Мета информация -->
                        <div class="post-meta-info">
                        <span class="post-date">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <?php echo get_the_date('d F Y'); ?>
                        </span>
                            <span class="post-reading-time">
                            <i class="fas fa-clock me-2"></i>
                            <?php echo ceil(str_word_count(get_the_content()) / 200); ?> мин чтения
                        </span>

                            <!-- Категория -->
                            <?php
                            $categories = get_the_category();
                            if (!empty($categories)) :
                                ?>
                                <span class="Create selector">
                            <i class="fas fa-tag me-2"></i><?php echo esc_html($categories[0]->name); ?>
                        </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Плавающие элементы -->
        <div class="post-floating-elements">
            <i class="fas fa-feather-alt floating-feather post-floating-item"></i>
            <i class="fas fa-heart floating-heart-post post-floating-item"></i>
            <i class="fas fa-star floating-star-post post-floating-item"></i>
        </div>
    </section>

    <!-- Содержимое поста -->
    <section class="post-content-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <article class="post-content-card">
                        <div class="post-content">
                            <?php the_content(); ?>
                        </div>

                        <!-- Теги -->
                        <?php if (has_tag()) : ?>
                            <div class="post-tags">
                                <h6 class="tags-title">
                                    <i class="fas fa-tags me-2"></i>Теги:
                                </h6>
                                <div class="tags-list">
                                    <?php the_tags('', '', ''); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </article>

                    <!-- Навигация между постами -->
                    <div class="post-navigation">
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                $prev_post = get_previous_post();
                                if ($prev_post) :
                                    ?>
                                    <a href="<?php echo get_permalink($prev_post->ID); ?>" class="nav-post nav-prev">
                                        <div class="nav-direction">
                                            <i class="fas fa-chevron-left me-2"></i>Предыдущий пост
                                        </div>
                                        <div class="nav-title"><?php echo get_the_title($prev_post->ID); ?></div>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <?php
                                $next_post = get_next_post();
                                if ($next_post) :
                                    ?>
                                    <a href="<?php echo get_permalink($next_post->ID); ?>" class="nav-post nav-next">
                                        <div class="nav-direction">
                                            Следующий пост<i class="fas fa-chevron-right ms-2"></i>
                                        </div>
                                        <div class="nav-title"><?php echo get_the_title($next_post->ID); ?></div>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Кнопка "Назад к блогу" -->
                    <div class="back-to-blog text-center">
                        <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="btn-back-blog">
                            <i class="fas fa-arrow-left me-2"></i>Назад к блогу
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
