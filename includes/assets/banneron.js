

// Wait for page load before triggering script
document.addEventListener("DOMContentLoaded", function() {
    
    function openBannerOn() {
        scrollLock.disablePageScroll();
    }

    const closeBannerOn = document.getElementById('closeBannerOn');
    closeBannerOn.addEventListener('click', function() {
            scrollLock.enablePageScroll();
    });

    openBannerOn();
});

