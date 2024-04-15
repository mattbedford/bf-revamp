# Banner On
A framework to handle developer-created banners inside WP.

## Create a post
Add your text, any buttons or CTAs, and any scripts you need inside the __content__ area.
It'll need to look something like this in the content area:

```javascript
And this is our important message for you! Please click the link for ultimate satisfaction.

&nbsp;

<a id="BannerOnActionButton" href="#" class="banneron_button">This is my link text</a>


<script defer>
document.addEventListener("DOMContentLoaded", function() {

    let BannerOnBanner = document.querySelector("#BannerOnBanner");
    let BannerOnCloseIcon = document.querySelector("#closeBannerOn");
    let BannerOnActionButton = document.querySelector("#BannerOnActionButton");

    // Banner close icon
    BannerOnCloseIcon.addEventListener('click', function(){
        BannerOnActioned();
    });

    // Banner action button
    BannerOnActionButton.addEventListener('click', function(){
        BannerOnActioned();
    });

    async function BannerOnActioned(){
        scrollLock.enablePageScroll();
        BannerOnBanner.style.display = "none";
        let BannerOnId = BannerOnBanner.dataset.id;

        const url = '/wp-json/banner-on/action-completed';
        const headers = {
            credentials: 'same-origin',
            'Content-Type': 'application/json',
            'X-WP-Nonce': banneron_object.rest_nonce,
        };
        // 'banner_' . $banner_id  . '_has_been_actioned'
        const data = JSON.stringify({ id: `${BannerOnId}` });
        fetch(url, { method: 'POST', headers, body: data });
    }

});
</script>

```