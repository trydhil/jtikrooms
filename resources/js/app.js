// resources/js/app.js
import './bootstrap';
import './rooms/booking';
import './rooms/search';

// Initialize Dasher System
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Dasher System Initialized');
    
    // Your existing JavaScript code will be integrated here
    if (typeof initializeDasher === 'function') {
        initializeDasher();
    }
});