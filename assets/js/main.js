document.addEventListener('DOMContentLoaded', () => {
    // Inject helper CSS (spinner, ripple) if not already present
    if (!document.getElementById('ui-helpers-css')) {
        const style = document.createElement('style');
        style.id = 'ui-helpers-css';
        style.textContent = `.loading-spinner{display:inline-block;width:1rem;height:1rem;border:2px solid rgba(255,255,255,0.6);border-top-color:rgba(255,255,255,1);border-radius:50%;animation:spin 0.8s linear infinite;vertical-align:middle}@keyframes spin{to{transform:rotate(360deg)}}.ripple{position:absolute;border-radius:50%;background:rgba(255,255,255,0.4);transform:scale(0);animation:ripple 0.6s linear;pointer-events:none}@keyframes ripple{to{transform:scale(2);opacity:0}}`;
        document.head.appendChild(style);
    }
    // Enhanced page load animations
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.2}s`;
        card.classList.add('animate-slide-up');
    });
    
    // Add parallax effect to background elements
    const backgroundElements = document.querySelectorAll('[data-parallax]');
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        backgroundElements.forEach(element => {
            const speed = element.dataset.parallax || 0.5;
            element.style.transform = `translateY(${scrolled * speed}px)`;
        });
    });
    
    // Enhanced hover effects for interactive elements
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', (e) => {
            e.target.style.transform = 'translateY(-2px)';
            e.target.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
        });
        
        button.addEventListener('mouseleave', (e) => {
            e.target.style.transform = 'translateY(0)';
            e.target.style.boxShadow = '';
        });
        
        // Add ripple effect on click
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // Floating animation for decorative elements
    const floatingElements = document.querySelectorAll('.animate-float');
    floatingElements.forEach((element, index) => {
        element.style.animationDelay = `${index * 0.5}s`;
    });
    
    // Auto-hide alerts after 5 seconds with enhanced animation
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateX(100%) scale(0.8)';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
    
    // Enhanced form validation (excluding appointment forms which have their own handling)
    const forms = document.querySelectorAll('form:not([action*="schedule_appointment"])');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        
        inputs.forEach(input => {
            input.addEventListener('blur', validateField);
            input.addEventListener('input', clearFieldError);
            
            // Add focus animations
            input.addEventListener('focus', function() {
                const parent = this.closest('.form-field') || this.parentNode;
                parent.classList.add('form-field-focus');
            });
            
            input.addEventListener('blur', function() {
                const parent = this.closest('.form-field') || this.parentNode;
                parent.classList.remove('form-field-focus');
            });
        });
        
        form.addEventListener('submit', (e) => {
            let isValid = true;
            inputs.forEach(input => {
                if (!validateField.call(input)) {
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                // Shake animation for invalid form
                form.style.animation = 'shake 0.5s ease-in-out';
                setTimeout(() => {
                    form.style.animation = '';
                }, 500);
            }
        });
    });
    
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-slide-up');
                entry.target.style.opacity = '1';
            }
        });
    }, observerOptions);
    
    // Observe elements that should animate on scroll
    const animateOnScroll = document.querySelectorAll('[data-animate-on-scroll]');
    animateOnScroll.forEach(element => {
        element.style.opacity = '0';
        observer.observe(element);
    });
    
    // Mobile menu toggle with animation
    const mobileMenuButton = document.querySelector('[data-mobile-menu-button]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
            mobileMenu.classList.toggle('animate-slide-up');
        });
    }
    
    // Enhanced loading states applied on form submit (safer than click handler)
    const formsForLoading = document.querySelectorAll('form:not([action*="schedule_appointment"])');
    formsForLoading.forEach(form => {
        form.addEventListener('submit', (e) => {
            // If the browser prevented submission due to validation, don't show loading
            if (!form.checkValidity()) return;

            // Find the primary submit button in the form
            const button = form.querySelector('button[type="submit"]:not([data-no-js-handling])');
            if (!button) return;

            // Prevent duplicate submission
            if (button.dataset.submitted === '1') return;
            button.dataset.submitted = '1';

            // Save original content so we can restore if needed
            button.dataset.originalHtml = button.innerHTML;
            button.innerHTML = '<div class="loading-spinner mr-2"></div>Processing...';
            button.disabled = true;

            // As a safeguard, if the page doesn't navigate within 12s, restore button
            setTimeout(() => {
                if (button.dataset.submitted === '1') {
                    button.innerHTML = button.dataset.originalHtml || 'Submit';
                    button.disabled = false;
                    delete button.dataset.submitted;
                }
            }, 12000);
        });
    });
    
    // Add smooth scrolling for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const target = document.querySelector(link.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add typing effect for dynamic text (if needed)
    const typingElements = document.querySelectorAll('[data-typing]');
    typingElements.forEach(element => {
        const text = element.textContent;
        element.textContent = '';
        let i = 0;
        
        function typeWriter() {
            if (i < text.length) {
                element.textContent += text.charAt(i);
                i++;
                setTimeout(typeWriter, 50);
            }
        }
        
        // Start typing when element is visible
        const typingObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    typeWriter();
                    typingObserver.unobserve(element);
                }
            });
        });
        
        typingObserver.observe(element);
    });
});

// Field validation function
function validateField() {
    const field = this;
    const value = field.value.trim();
    const fieldType = field.type;
    const fieldName = field.name;
    
    // Remove existing error
    clearFieldError.call(field);
    
    let isValid = true;
    let errorMessage = '';
    
    // Required field validation
    if (field.hasAttribute('required') && !value) {
        isValid = false;
        errorMessage = `${getFieldLabel(field)} is required`;
    }
    
    // Email validation
    if (fieldType === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            isValid = false;
            errorMessage = 'Please enter a valid email address';
        }
    }
    
    // Password validation
    if (fieldType === 'password' && value && value.length < 6) {
        isValid = false;
        errorMessage = 'Password must be at least 6 characters';
    }
    
    // Phone validation
    if (fieldName === 'phone' && value) {
        const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
        if (!phoneRegex.test(value.replace(/[\s\-\(\)]/g, ''))) {
            isValid = false;
            errorMessage = 'Please enter a valid phone number';
        }
    }
    
    if (!isValid) {
        showFieldError(field, errorMessage);
    }
    
    return isValid;
}

// Clear field error
function clearFieldError() {
    const field = this;
    const errorElement = field.parentNode.querySelector('.field-error');
    if (errorElement) {
        errorElement.remove();
    }
    field.classList.remove('border-red-500');
    field.classList.add('border-gray-300');
}

// Show field error
function showFieldError(field, message) {
    field.classList.remove('border-gray-300');
    field.classList.add('border-red-500');
    
    const errorElement = document.createElement('p');
    errorElement.className = 'field-error text-red-500 text-sm mt-1';
    errorElement.textContent = message;
    
    field.parentNode.appendChild(errorElement);
}

// Get field label
function getFieldLabel(field) {
    const label = field.parentNode.querySelector('label');
    return label ? label.textContent.replace('*', '').trim() : field.name;
}

// Utility functions
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} fixed top-4 right-4 z-50 max-w-sm animate-slide-up`;
    notification.innerHTML = `
        <div class="flex items-center">
            <span class="flex-1">${message}</span>
            <button onclick="this.parentNode.parentNode.remove()" class="ml-2 text-lg">&times;</button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Loading utility
function showLoading(element) {
    const spinner = document.createElement('div');
    spinner.className = 'loading-spinner';
    element.appendChild(spinner);
}

function hideLoading(element) {
    const spinner = element.querySelector('.loading-spinner');
    if (spinner) {
        spinner.remove();
    }
}