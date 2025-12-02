/**
 * Maintenance Mode Check Script
 * Include this script in all pages that should be blocked during maintenance
 * Admin pages should NOT include this script
 */
(function() {
    'use strict';
    
    // Use relative path for same-origin requests (Docker port 80 or Laravel port 8000)
    const API_BASE = (location.protocol === 'http:' || location.protocol === 'https:') ? '/api' : 'http://127.0.0.1:8000/api';
    
    // Pages that are always allowed (login, maintenance page itself)
    const ALLOWED_PAGES = [
        '/frontend/login.html',
        '/frontend/maintenance.html',
        '/frontend/admin/', // Admin pages are always allowed
        '/quan-tri' // Admin route
    ];
    
    // Check if current page is allowed
    function isAllowedPage() {
        const path = window.location.pathname;
        return ALLOWED_PAGES.some(allowed => path.includes(allowed));
    }
    
    // Check if user is admin
    function isAdminUser() {
        try {
            const userData = localStorage.getItem('user_data');
            if (userData) {
                const user = JSON.parse(userData);
                return user.type === 'ADMIN';
            }
        } catch (e) {}
        return false;
    }
    
    // Check maintenance status from API
    async function checkMaintenanceStatus() {
        try {
            const response = await fetch(API_BASE + '/public/maintenance-status');
            if (response.ok) {
                const data = await response.json();
                return data;
            }
        } catch (e) {
            console.warn('[maintenance] Failed to check maintenance status:', e);
        }
        return { maintenance: false };
    }
    
    // Redirect to maintenance page
    function redirectToMaintenance(message) {
        // Store message for maintenance page to display
        sessionStorage.setItem('maintenance_message', message || 'Hệ thống đang được bảo trì. Vui lòng quay lại sau.');
        
        // Redirect to maintenance page
        window.location.href = '/frontend/maintenance.html';
    }
    
    // Main check function
    async function performMaintenanceCheck() {
        // Skip check for allowed pages
        if (isAllowedPage()) {
            console.log('[maintenance] Allowed page, skipping check');
            return;
        }
        
        // Skip check for admin users
        if (isAdminUser()) {
            console.log('[maintenance] Admin user, skipping check');
            return;
        }
        
        // Check maintenance status
        const status = await checkMaintenanceStatus();
        
        if (status.maintenance) {
            console.log('[maintenance] System is in maintenance mode, redirecting...');
            redirectToMaintenance(status.message);
        }
    }
    
    // Run check when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', performMaintenanceCheck);
    } else {
        performMaintenanceCheck();
    }
    
    // Also expose for manual checking
    window.MaintenanceCheck = {
        check: performMaintenanceCheck,
        isAllowed: isAllowedPage,
        isAdmin: isAdminUser
    };
})();
