

// Wait for page load before triggering script
document.addEventListener("DOMContentLoaded", function() {
    console.log("Our script is loaded!");

    scrollLock.disablePageScroll();

    const $scrollableElement = document.querySelector('body');

    //Pass the element to the argument and disable scrolling on the page
    disablePageScroll($scrollableElement);
});