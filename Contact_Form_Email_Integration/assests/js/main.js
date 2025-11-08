document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.querySelector('.navbar');
    const hamburger = document.querySelector('.hamburger');
    const closeBtn = document.querySelector('.offcanvas-close');
    const offcanvasMenu = document.querySelector('.offcanvas-menu');
    const userMenu = document.querySelector('.user-menu');

    // ====================================
    // 1. Navbar Toggle (Offcanvas - Right Side)
    // ====================================
    function toggleOffcanvas() {
        offcanvasMenu.classList.toggle('open');
        document.body.style.overflow = offcanvasMenu.classList.contains('open') ? 'hidden' : '';
    }

    hamburger.addEventListener('click', toggleOffcanvas);
    closeBtn.addEventListener('click', toggleOffcanvas);

    document.querySelectorAll('.offcanvas-nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            if (offcanvasMenu.classList.contains('open')) {
                toggleOffcanvas();
            }
        });
    });

    // ====================================
    // 2. User Dropdown Toggle
    // ====================================
    if (userMenu) {
        userMenu.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu.classList.toggle('active');
        });

        window.addEventListener('click', (e) => {
            if (userMenu.classList.contains('active') && !userMenu.contains(e.target)) {
                userMenu.classList.remove('active');
            }
        });
    }

    // ====================================
    // 3. Sticky Navbar
    // ====================================
    function handleScroll() {
        if (window.scrollY > 50) {
            navbar.classList.add('sticky');
        } else {
            navbar.classList.remove('sticky');
        }
    }

    window.addEventListener('scroll', handleScroll);
    handleScroll();

    // ====================================
    // 4. Scroll Fade-In Animation (Intersection Observer)
    // ====================================
    const faders = document.querySelectorAll('.fade-in');

    const observerOptions = {
        root: null, 
        threshold: 0.1, 
        rootMargin: "0px"
    };

    const appearanceObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target); 
            }
        });
    }, observerOptions);

    faders.forEach(fader => {
        appearanceObserver.observe(fader);
    });

    // ====================================
    // 5. Current Year
    // ====================================
    let date = new Date().getFullYear();
    const yearElem = document.getElementById('Year');
    if (yearElem) {
        yearElem.innerText = `${date}`;
    }

    // ====================================
    // 6. Password Toggle
    // ====================================
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    if (passwordInput && togglePassword) {
        const icon = togglePassword.querySelector('i');
        togglePassword.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            icon.classList.toggle('fa-eye', isHidden);
            icon.classList.toggle('fa-eye-slash', !isHidden);
        });
    }

    const confirmPasswordInput = document.getElementById('confirm_password');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    let confirmIcon = null;

    if (toggleConfirmPassword) {
        confirmIcon = toggleConfirmPassword.querySelector('i');
    }

    if (confirmPasswordInput && toggleConfirmPassword && confirmIcon) {
        toggleConfirmPassword.addEventListener('click', () => {
            const isHidden = confirmPasswordInput.type === 'password';
            confirmPasswordInput.type = isHidden ? 'text' : 'password';
            confirmIcon.classList.toggle('fa-eye', isHidden);
            confirmIcon.classList.toggle('fa-eye-slash', !isHidden);
        });
    }
    // ====================================
    // 8. FAQ Section
    // ====================================
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        question.addEventListener('click', () => {
            const isActive = item.classList.contains('active');
            faqItems.forEach(otherItem => {
                otherItem.classList.remove('active');
            });
            if (!isActive) {
                item.classList.add('active');
            }
        });
    });

    // ====================================
    // 9. Trigger Scroll Fade-In on Form
    // ====================================
    const loginCard = document.getElementById('loginFormCard');
    if (loginCard) {
        loginCard.classList.add('visible');
    }
});


