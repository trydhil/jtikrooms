// Animation utilities for Dasher system
class DasherAnimations {
    static init() {
        // Auto-initialize animations on page load
        this.animateDashboardElements();
        this.setupHoverEffects();
    }
    
    static animateDashboardElements() {
        // Animate stats cards and other elements
        const animatedElements = document.querySelectorAll('.stat-card, .feature-card, .booking-card');
        
        animatedElements.forEach((element, index) => {
            // Only animate if element doesn't have data-animated attribute
            if (!element.hasAttribute('data-animated')) {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    element.style.transition = 'all 0.6s ease';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                    element.setAttribute('data-animated', 'true');
                }, 100 + (index * 100));
            }
        });
    }
    
    static setupHoverEffects() {
        // Add ripple effects to buttons
        document.addEventListener('click', function(e) {
            if (e.target.matches('.btn, .action-btn, .stat-card')) {
                createRipple(e);
            }
        });
        
        function createRipple(event) {
            const button = event.currentTarget;
            const circle = document.createElement('span');
            const diameter = Math.max(button.clientWidth, button.clientHeight);
            const radius = diameter / 2;
            
            circle.style.width = circle.style.height = diameter + 'px';
            circle.style.left = (event.clientX - button.getBoundingClientRect().left - radius) + 'px';
            circle.style.top = (event.clientY - button.getBoundingClientRect().top - radius) + 'px';
            circle.classList.add('ripple');
            
            const ripple = button.getElementsByClassName('ripple')[0];
            if (ripple) {
                ripple.remove();
            }
            
            button.appendChild(circle);
        }
    }
    
    static showNotification(message, type = 'success') {
        // Create toast notification
        const toast = document.createElement('div');
        toast.className = `custom-toast ${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
}

// Initialize animations when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    DasherAnimations.init();
});