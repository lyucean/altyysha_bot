</main>
<?php wp_footer(); ?>
<footer class="py-5 mt-5 w-100 footer-custom">
    <div class="container">
        <div class="row">
            <div class="col-12">

                <!-- Добавляем описание -->
                <div class="text-center mb-4">
                    <p class="footer-description">
                        Блог ИТ-директора о том, как не поехать кукухой в мире технологий.
                        <br/>
                        Практические советы и откровенные истории без воды и розовых соплей.
                    </p>
                </div>

                <!-- Социальные сети  -->
                <div class="d-flex justify-content-center gap-4 mb-4">
                    <a href="https://www.instagram.com/altyysha"
                       class="social-icon instagram-icon"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>

                    <a href="https://t.me/altyysha"
                       class="social-icon telegram-icon"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="Telegram">
                        <i class="bi bi-telegram"></i>
                    </a>

                    <a href="mailto:forever19192@yandex.ru"
                       class="social-icon email-icon"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="Email">
                        <i class="bi bi-envelope"></i>
                    </a>

                    <a href="<?php bloginfo('rss2_url'); ?>"
                       class="social-icon rss-icon"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="RSS Feed">
                        <i class="bi bi-rss"></i>
                    </a>
                </div>

                <div class="text-center">
                    <p class="footer-copyright">
                        © Валентин Панченко, <?php echo date('Y'); ?>
                    </p>
                </div>

            </div>
        </div>
    </div>
</footer>
</body>
</html>
