// Mobile Menu Toggle
function toggleMobileMenu() {
    const mobileNav = document.getElementById('mobileNav');
    const menuBtn = document.querySelector('.mobile-menu-btn i');
    
    mobileNav.classList.toggle('active');
    
    if (mobileNav.classList.contains('active')) {
        menuBtn.className = 'fas fa-times';
    } else {
        menuBtn.className = 'fas fa-bars';
    }
}

function closeMobileMenu() {
    const mobileNav = document.getElementById('mobileNav');
    const menuBtn = document.querySelector('.mobile-menu-btn i');
    
    mobileNav.classList.remove('active');
    menuBtn.className = 'fas fa-bars';
}

// Smooth Scrolling for Navigation Links
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('a[href^="#"]');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);
            
            if (targetSection) {
                const headerHeight = document.querySelector('.header').offsetHeight;
                const targetPosition = targetSection.offsetTop - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
            
            closeMobileMenu();
        });
    });
});

// Generate User ID
function generateUserId() {
    const timestamp = Date.now().toString().slice(-6);
    const random = Math.random().toString(36).substr(2, 4).toUpperCase();
    return `BT-${timestamp}-${random}`;
}

// Images Preview Function
let selectedFiles = [];

function previewImages(input) {
    const uploadArea = document.getElementById('uploadArea');
    const imagesPreview = document.getElementById('imagesPreview');
    const previewContainer = document.getElementById('previewContainer');
    
    if (input.files && input.files[0]) {
        // Add new files to existing ones, limit to 5 total
        const newFiles = Array.from(input.files);
        const remainingSlots = 5 - selectedFiles.length;
        const filesToAdd = newFiles.slice(0, remainingSlots);
        
        selectedFiles = [...selectedFiles, ...filesToAdd];
        
        previewContainer.innerHTML = '';
        
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewItem = document.createElement('div');
                previewItem.className = 'preview-item';
                previewItem.innerHTML = `
                    <img src="${e.target.result}" alt="Podgląd ${index + 1}">
                    <button type="button" class="preview-remove" onclick="removeImage(${index})">×</button>
                `;
                previewContainer.appendChild(previewItem);
            };
            
            reader.readAsDataURL(file);
        });
        
        if (selectedFiles.length > 0) {
            uploadArea.style.display = 'none';
            imagesPreview.style.display = 'flex';
        }
        
        // Update file input with selected files
        updateFileInput();
    }
}

function removeImage(index) {
    selectedFiles.splice(index, 1);
    
    if (selectedFiles.length === 0) {
        document.getElementById('uploadArea').style.display = 'flex';
        document.getElementById('imagesPreview').style.display = 'none';
    } else {
        // Re-render previews
        const previewContainer = document.getElementById('previewContainer');
        previewContainer.innerHTML = '';
        
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewItem = document.createElement('div');
                previewItem.className = 'preview-item';
                previewItem.innerHTML = `
                    <img src="${e.target.result}" alt="Podgląd ${index + 1}">
                    <button type="button" class="preview-remove" onclick="removeImage(${index})">×</button>
                `;
                previewContainer.appendChild(previewItem);
            };
            
            reader.readAsDataURL(file);
        });
    }
    
    updateFileInput();
}

function updateFileInput() {
    const input = document.getElementById('imageInput');
    const dt = new DataTransfer();
    
    selectedFiles.forEach(file => {
        dt.items.add(file);
    });
    
    input.files = dt.files;
}

function selectFromGallery() {
    const input = document.getElementById('imageInput');
    input.removeAttribute('capture');
    input.click();
}

function takePhoto() {
    const input = document.getElementById('imageInput');
    input.setAttribute('capture', 'environment');
    input.click();
}

// Check if device has camera capability
function isMobileDevice() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

// Show/hide mobile photo options based on device
document.addEventListener('DOMContentLoaded', function() {
    const mobilePhotoOptions = document.querySelector('.mobile-photo-options');
    if (mobilePhotoOptions && !isMobileDevice()) {
        mobilePhotoOptions.style.display = 'none';
        // Enable the main upload area on desktop
        const fileUpload = document.querySelector('.file-upload');
        if (fileUpload) {
            fileUpload.style.pointerEvents = 'auto';
            const uploadArea = fileUpload.querySelector('.upload-area');
            if (uploadArea) {
                uploadArea.style.opacity = '1';
            }
        }
    }
});
// FAQ Toggle Function
function toggleFAQ(button) {
    const faqItem = button.parentElement;
    const isActive = faqItem.classList.contains('active');
    
    // Close all FAQ items
    document.querySelectorAll('.faq-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Open clicked item if it wasn't active
    if (!isActive) {
        faqItem.classList.add('active');
    }
}

// Form Submission Handlers
document.addEventListener('DOMContentLoaded', function() {
    // Quote Form Handler
    const quoteForm = document.getElementById('quoteForm');
    if (quoteForm) {
        quoteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Wysyłanie...</span>';
            submitBtn.disabled = true;
            
            // Simulate form submission
            fetch('send_quote.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide form and show success message
                    document.querySelector('.quote-form').style.display = 'none';
                    document.getElementById('quoteSuccess').style.display = 'block';
                } else {
                    throw new Error(data.message || 'Wystąpił błąd podczas wysyłania');
                }
            })
            .catch(error => {
                alert('Wystąpił błąd: ' + error.message);
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Contact Form Handler
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Wysyłanie...</span>';
            submitBtn.disabled = true;
            
            // Simulate form submission
            fetch('send_contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide form and show success message
                    document.querySelector('.contact-form').style.display = 'none';
                    document.getElementById('contactSuccess').style.display = 'block';
                } else {
                    throw new Error(data.message || 'Wystąpił błąd podczas wysyłania');
                }
            })
            .catch(error => {
                alert('Wystąpił błąd: ' + error.message);
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});

// Reset Contact Form Function
function resetContactForm() {
    document.querySelector('.contact-form').style.display = 'block';
    document.getElementById('contactSuccess').style.display = 'none';
    document.getElementById('contactForm').reset();
    
    // Reset button state
    const submitBtn = document.querySelector('#contactForm button[type="submit"]');
    if (submitBtn) {
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> <span>Wyślij wiadomość</span>';
        submitBtn.disabled = false;
    }
}

// Reset Quote Form Function
function resetQuoteForm() {
    document.querySelector('.quote-form').style.display = 'block';
    document.getElementById('quoteSuccess').style.display = 'none';
    document.getElementById('quoteForm').reset();
    
    // Reset images
    selectedFiles = [];
    document.getElementById('uploadArea').style.display = 'flex';
    document.getElementById('imagesPreview').style.display = 'none';
    document.getElementById('previewContainer').innerHTML = '';
    
    // Reset button state
    const submitBtn = document.querySelector('#quoteForm button[type="submit"]');
    if (submitBtn) {
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> <span>Wyślij zgłoszenie</span>';
        submitBtn.disabled = false;
    }
}

// Header Background on Scroll
window.addEventListener('scroll', function() {
    const header = document.querySelector('.header');
    if (window.scrollY > 100) {
        header.style.background = 'rgba(255, 255, 255, 0.98)';
    } else {
        header.style.background = 'rgba(255, 255, 255, 0.95)';
    }
});

// Update URL hash on navigation
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('a[href^="#"]');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId && targetId !== '#') {
                // Update URL hash
                history.pushState(null, null, targetId);
            }
        });
    });
    
    // Handle back/forward browser buttons
    window.addEventListener('popstate', function() {
        const hash = window.location.hash;
        if (hash) {
            const targetSection = document.querySelector(hash);
            if (targetSection) {
                const headerHeight = document.querySelector('.header').offsetHeight;
                const targetPosition = targetSection.offsetTop - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        }
    });
});

// Intersection Observer for Animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe elements for animation
document.addEventListener('DOMContentLoaded', function() {
    const animatedElements = document.querySelectorAll('.benefit-card, .process-step, .feature-card, .faq-item');
    
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});