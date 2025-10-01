<?php get_header(); ?>

<!-- Главный блок -->
<section id="home" class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                      <span class="hero-badge">
                          <i class="fas fa-code me-2"></i>PHP Developer
                      </span>

                    <h1 class="hero-title">
                        Привет! Я Дашенька
                    </h1>

                    <p class="hero-subtitle">
                        Создаю волшебный код и воплощаю мечты в реальность
                    </p>

                    <p class="hero-description">
                        PHP-разработчик с душой творца. Пишу стихи, увлекаюсь аниме, играю на гитаре,
                        танцую, веду мероприятия и создаю цифровые решения, которые делают
                        мир немного лучше и добрее.
                    </p>

                    <a href="#about" class="btn-nature">
                        <i class="fas fa-heart"></i>
                        Узнать больше
                    </a>

                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number">6+</span>
                            <span class="stat-label">Лет в IT</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">10+</span>
                            <span class="stat-label">Проектов</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number large-text">∞</span>
                            <span class="stat-label">Вдохновения</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="hero-image text-center">
                    <div class="hero-image-container">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/first-page-1.jpg" alt="Дашенька" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Декоративные природные элементы -->
    <div class="nature-decoration leaf-1"></div>
    <div class="nature-decoration leaf-2"></div>
    <div class="nature-decoration cloud"></div>
</section>

<!-- Блок "Обо мне" -->
<section id="about" class="about-section py-5">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6 order-lg-2">
                <div class="about-content">
                    <span class="section-badge">
                        <i class="fas fa-star me-2"></i>Моя история
                    </span>

                    <h2 class="section-title">
                        Обо мне
                    </h2>

                    <p class="about-text">
                        Привет! Меня зовут Даша, и я из славного города Кольчугино
                        (да-да, того самого во Владимирской области, где делают те самые
                        подстаканники и клевых девчонок вроде меня). Сначала думала стать юристом —
                        поехала в Дубну учиться, магистратуру во Владимире закончила.
                        Даже поработала пару лет нотариусом, заверяла всякие важные бумажки.
                    </p>

                    <p class="about-text">
                        Волей судьбы меня тянуло в другое и я осознала, что хочу делать клёвые штуки сама.
                        И IT как раз для этого подходит. Сама выучилась на PHP-программиста! Сначала набивала руку в Галлере,
                        потом попала в офигенную компанию "Ешь-Деревенское" (да, ИТ-директор был там что надо, его пришлось забрать с собой).
                        Кстати, он сейчас клепает мне этот сайт на день рождения — мимими!
                    </p>

                    <p class="about-text">
                        Потом была эпичная работа над Госуслугами (теперь вы знаете,
                        кто виноват в том, что они иногда работают 😄). Сейчас ваяю
                        проекты на Laravel в другой компании и жду пополнения в семью.
                        И нет, это не кот! Хотя кота тоже не против.
                    </p>

                    <div class="about-highlights">
                        <div class="highlight-item">
                            <i class="fas fa-heart highlight-icon"></i>
                            <div>
                                <h5>Любовь и Laravel</h5>
                                <p>Два самых важных "Л" в моей жизни</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 order-lg-1">
                <div class="about-image-grid">
                    <div class="image-card main-image">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/ed.jpg" alt="За работой" class="img-fluid">
                        <div class="image-overlay">
                            <span>Превращаю кофе в код</span>
                        </div>
                    </div>
                    <div class="image-card secondary-image">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/art.jpg" alt="Путь в IT" class="img-fluid">
                        <div class="image-overlay">
                            <span>Из Кольчугино с любовью</span>
                        </div>
                    </div>
                    <div class="floating-badge">
                        <i class="fas fa-coffee"></i>
                        <span>Работаю на кофе</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<?php get_footer(); ?>
