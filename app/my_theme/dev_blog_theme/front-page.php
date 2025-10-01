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

<!-- Блок "Навыки" -->
<section id="skills" class="skills-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-badge">
                <i class="fas fa-sparkles me-2"></i>Мои суперсилы
            </span>
            <h2 class="section-title">Навыки и технологии</h2>
            <p class="section-subtitle">
                Чем я создаю цифровую магию каждый день
            </p>
        </div>

        <!-- Инструменты и технологии -->
        <div class="tools-section mt-5">
            <div class="tools-grid">
                <div class="tool-item" data-tooltip="PHP 8+ с современными фичами">
                    <div class="tool-icon">
                        <img src="https://placehold.co/50x50/777bb4/ffffff?text=PHP" alt="PHP">
                    </div>
                    <span class="tool-name">PHP</span>
                    <div class="tool-experience">6+ лет</div>
                </div>

                <div class="tool-item" data-tooltip="Eloquent ORM, Artisan, Queue">
                    <div class="tool-icon">
                        <img src="https://placehold.co/50x50/ff2d20/ffffff?text=L" alt="Laravel">
                    </div>
                    <span class="tool-name">Laravel</span>
                    <div class="tool-experience">4+ лет</div>
                </div>

                <div class="tool-item" data-tooltip="MySQL оптимизация и индексы">
                    <div class="tool-icon">
                        <img src="https://placehold.co/50x50/4479a1/ffffff?text=SQL" alt="MySQL">
                    </div>
                    <span class="tool-name">MySQL</span>
                    <div class="tool-experience">5+ лет</div>
                </div>

                <div class="tool-item" data-tooltip="ES6+, async/await, modules">
                    <div class="tool-icon">
                        <img src="https://placehold.co/50x50/f7df1e/333333?text=JS" alt="JavaScript">
                    </div>
                    <span class="tool-name">JavaScript</span>
                    <div class="tool-experience">4+ лет</div>
                </div>

                <div class="tool-item" data-tooltip="Основная IDE для разработки">
                    <div class="tool-icon">
                        <img src="https://placehold.co/50x50/000000/ffffff?text=PS" alt="PHPStorm">
                    </div>
                    <span class="tool-name">PHPStorm</span>
                    <div class="tool-experience">Каждый день</div>
                </div>

                <div class="tool-item" data-tooltip="Контейнеризация и деплой">
                    <div class="tool-icon">
                        <img src="https://placehold.co/50x50/2496ed/ffffff?text=🐳" alt="Docker">
                    </div>
                    <span class="tool-name">Docker</span>
                    <div class="tool-experience">3+ лет</div>
                </div>

                <div class="tool-item" data-tooltip="API документация">
                    <div class="tool-icon">
                        <img src="https://placehold.co/50x50/85ea2d/ffffff?text=SW" alt="Swagger">
                    </div>
                    <span class="tool-name">Swagger</span>
                    <div class="tool-experience">2+ лет</div>
                </div>

                <div class="tool-item" data-tooltip="Git Flow, merge requests">
                    <div class="tool-icon">
                        <img src="https://placehold.co/50x50/f05032/ffffff?text=GIT" alt="Git">
                    </div>
                    <span class="tool-name">Git</span>
                    <div class="tool-experience">5+ лет</div>
                </div>

                <div class="tool-item" data-tooltip="PHPUnit, Feature тесты">
                    <div class="tool-icon">
                        <img src="https://placehold.co/50x50/366832/ffffff?text=TEST" alt="PHPUnit">
                    </div>
                    <span class="tool-name">PHPUnit</span>
                    <div class="tool-experience">3+ лет</div>
                </div>

                <div class="tool-item" data-tooltip="Composer пакеты и зависимости">
                    <div class="tool-icon">
                        <img src="https://placehold.co/50x50/885630/ffffff?text=📦" alt="Composer">
                    </div>
                    <span class="tool-name">Composer</span>
                    <div class="tool-experience">4+ лет</div>
                </div>

                <div class="tool-item" data-tooltip="REST API, JSON, HTTP">
                    <div class="tool-icon">
                        <img src="https://placehold.co/50x50/61dafb/333333?text=API" alt="REST API">
                    </div>
                    <span class="tool-name">REST API</span>
                    <div class="tool-experience">4+ лет</div>
                </div>

                <div class="tool-item" data-tooltip="Redis кеширование">
                    <div class="tool-icon">
                        <img src="https://placehold.co/50x50/dc382d/ffffff?text=R" alt="Redis">
                    </div>
                    <span class="tool-name">Redis</span>
                    <div class="tool-experience">2+ лет</div>
                </div>

                <div class="tool-item" data-tooltip="Nginx конфигурация">
                    <div class="tool-icon">
                        <img src="https://placehold.co/50x50/009639/ffffff?text=N" alt="Nginx">
                    </div>
                    <span class="tool-name">Nginx</span>
                    <div class="tool-experience">3+ лет</div>
                </div>

                <div class="tool-item" data-tooltip="Linux серверы, SSH">
                    <div class="tool-icon">
                        <img src="https://placehold.co/50x50/fcc624/333333?text=🐧" alt="Linux">
                    </div>
                    <span class="tool-name">Linux</span>
                    <div class="tool-experience">4+ лет</div>
                </div>

                <div class="tool-item" data-tooltip="Webpack, Vite сборка">
                    <div class="tool-icon">
                        <img src="https://placehold.co/50x50/8dd6f9/333333?text=⚡" alt="Build Tools">
                    </div>
                    <span class="tool-name">Build Tools</span>
                    <div class="tool-experience">2+ лет</div>
                </div>

                <div class="tool-item" data-tooltip="Postman, Insomnia">
                    <div class="tool-icon">
                        <img src="https://placehold.co/50x50/ff6c37/ffffff?text=📡" alt="API Testing">
                    </div>
                    <span class="tool-name">API Testing</span>
                    <div class="tool-experience">3+ лет</div>
                </div>

                <div class="tool-item" data-tooltip="SOLID, паттерны проектирования">
                    <div class="tool-icon">
                        <img src="https://placehold.co/50x50/6f42c1/ffffff?text=🏗️" alt="Architecture">
                    </div>
                    <span class="tool-name">Architecture</span>
                    <div class="tool-experience">3+ лет</div>
                </div>

                <div class="tool-item" data-tooltip="CI/CD, автоматизация">
                    <div class="tool-icon">
                        <img src="https://placehold.co/50x50/2088ff/ffffff?text=🚀" alt="DevOps">
                    </div>
                    <span class="tool-name">DevOps</span>
                    <div class="tool-experience">2+ лет</div>
                </div>
            </div>
        </div>


        <!-- Статистика достижений -->
        <div class="achievements-section mt-5">
            <div class="row text-center">
                <div class="col-md-3 col-6">
                    <div class="achievement-item">
                        <div class="achievement-number" data-count="27">0</div>
                        <div class="achievement-label">Проектов завершено</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="achievement-item">
                        <div class="achievement-number" data-count="6">0</div>
                        <div class="achievement-label">Лет опыта</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="achievement-item">
                        <div class="achievement-number" data-count="50+">0</div>
                        <div class="achievement-label">Мероприятий проведено</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="achievement-item">
                        <div class="achievement-number" data-count="100">0</div>
                        <div class="achievement-label">% вдохновения</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Декоративные элементы -->
    <div class="skills-decoration leaf-left"></div>
    <div class="skills-decoration leaf-right"></div>
    <div class="skills-decoration flower-top"></div>
</section>

<?php get_footer(); ?>
