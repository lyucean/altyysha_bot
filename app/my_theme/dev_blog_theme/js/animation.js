// Скрыть прелоадер
window.addEventListener('load', function() {
    const loader = document.getElementById('loader');
    loader.style.opacity = '0';
    setTimeout(() => {
        loader.style.display = 'none';
    }, 500);
});

console.log(12);

// Плавная прокрутка
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Параллакс эффект для декоративных элементов
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const decorations = document.querySelectorAll('.nature-decoration');

    decorations.forEach((element, index) => {
        const speed = 0.2 + (index * 0.1);
        element.style.transform = `translateY(${scrolled * speed}px)`;
    });
});

// Изменение навигации при скролле
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.style.background = 'rgba(255, 255, 255, 0.98)';
        navbar.style.boxShadow = '0 2px 20px rgba(74, 124, 89, 0.15)';
    } else {
        navbar.style.background = 'rgba(255, 255, 255, 0.95)';
        navbar.style.boxShadow = '0 2px 20px rgba(74, 124, 89, 0.1)';
    }
});

// Анимация прогресс-баров и счетчиков
const observerOptions = {
    threshold: 0.3,
    rootMargin: '0px 0px -50px 0px'
};

// Анимация прогресс-баров
const progressObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const progressBars = entry.target.querySelectorAll('.skill-progress');
            progressBars.forEach((bar, index) => {
                const width = bar.getAttribute('data-width');
                setTimeout(() => {
                    bar.style.width = width;
                }, index * 200);
            });
        }
    });
}, observerOptions);

// Анимация счетчиков достижений
const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const counters = entry.target.querySelectorAll('.achievement-number');
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-count'));
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    counter.textContent = target === 100 ? Math.floor(current) + '%' : Math.floor(current) + (target === 100 ? '' : '+');
                }, 40);
            });
        }
    });
}, observerOptions);

document.addEventListener('DOMContentLoaded', () => {
    const skillsSection = document.querySelector('.skills-category');
    const achievementsSection = document.querySelector('.achievements-section');

    if (skillsSection) {
        progressObserver.observe(skillsSection);
    }

    if (achievementsSection) {
        counterObserver.observe(achievementsSection);
    }

    // Простые тултипы для инструментов
    const toolItems = document.querySelectorAll('.tool-item[data-tooltip]');
    toolItems.forEach(item => {
        item.addEventListener('mouseenter', (e) => {
            const tooltip = document.createElement('div');
            tooltip.className = 'custom-tooltip';
            tooltip.textContent = e.target.getAttribute('data-tooltip');
            document.body.appendChild(tooltip);

            const rect = e.target.getBoundingClientRect();
            tooltip.style.left = rect.left + rect.width / 2 - tooltip.offsetWidth / 2 + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
        });

        item.addEventListener('mouseleave', () => {
            const tooltip = document.querySelector('.custom-tooltip');
            if (tooltip) {
                tooltip.remove();
            }
        });
    });
});
// Анимация прогресс-баров в увлечениях
const hobbiesObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const progressBars = entry.target.querySelectorAll('.progress-fill');
            progressBars.forEach((bar, index) => {
                const width = bar.getAttribute('data-width');
                setTimeout(() => {
                    bar.style.width = width;
                }, index * 300);
            });
        }
    });
}, {
    threshold: 0.5,
    rootMargin: '0px 0px -50px 0px'
});

// Параллакс для декоративных элементов
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const hobbiesDecorations = document.querySelectorAll('.hobbies-decoration');

    hobbiesDecorations.forEach((element, index) => {
        const speed = 0.1 + (index * 0.05);
        element.style.transform = `translateY(${scrolled * speed}px)`;
    });

    // Параллакс для плавающих листьев в цитате
    const floatingLeaves = document.querySelectorAll('.floating-leaf');
    floatingLeaves.forEach((leaf, index) => {
        const speed = 0.05 + (index * 0.02);
        leaf.style.transform += ` translateY(${scrolled * speed}px)`;
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const hobbiesSection = document.querySelector('.hobbies-section');
    if (hobbiesSection) {
        hobbiesObserver.observe(hobbiesSection);
    }

    // Добавляем эффект наведения для карточек
    const hobbyCards = document.querySelectorAll('.hobby-card');
    hobbyCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px) scale(1.02)';
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0) scale(1)';
        });
    });
});

// Обработка формы контактов
document.addEventListener('DOMContentLoaded', () => {
    const contactForm = document.getElementById('contactForm');
    const successMessage = document.getElementById('successMessage');

    if (contactForm) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();

            // Анимация отправки
            const submitBtn = contactForm.querySelector('.submit-btn');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Отправляем...';
            submitBtn.disabled = true;

            // Имитация отправки (замените на реальную логику)
            setTimeout(() => {
                // Скрываем форму и показываем сообщение об успехе
                contactForm.style.display = 'none';
                successMessage.style.display = 'block';

                // Анимация появления сообщения
                successMessage.style.opacity = '0';
                successMessage.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    successMessage.style.transition = 'all 0.5s ease';
                    successMessage.style.opacity = '1';
                    successMessage.style.transform = 'translateY(0)';
                }, 100);

                // Возвращаем форму через 5 секунд
                setTimeout(() => {
                    contactForm.style.display = 'block';
                    successMessage.style.display = 'none';
                    contactForm.reset();
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 5000);

            }, 2000);
        });
    }

    // Анимация полей при фокусе
    const formControls = document.querySelectorAll('.form-control');
    formControls.forEach(control => {
        control.addEventListener('focus', () => {
            control.parentElement.classList.add('focused');
        });

        control.addEventListener('blur', () => {
            if (!control.value) {
                control.parentElement.classList.remove('focused');
            }
        });
    });

    // Параллакс для декоративных элементов
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const decorations = document.querySelectorAll('.contacts-decoration');

        decorations.forEach((element, index) => {
            const speed = 0.1 + (index * 0.03);
            element.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });
});