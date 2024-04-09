$( document ).ready(function() {

	//Set our banner div as a var
	var bfbBanner = document.querySelector("#big-footer-banner");
	var closeBfbBanner = document.querySelector("#bfb-closer");
	var closeBfbButton = document.querySelector("#bfb-button");


    //Check to see if we have a cookie timeout var set somewhere. if not, use 3 days as a default
    let timeOutDays = 3;
    if (typeof bfbTimeout !== 'undefined') {
        timeOutDays = Number(bfbTimeout);
    }
    
	
    //Check to see if bfb cookie exists or not at page load. Then check session storage. If neither exists, no need to show banner
    if(!document.cookie.match(/^(.*;)?\s*bfb-dismiss\s*=\s*[^;]+(.*)?$/)) {
		if (sessionStorage.getItem("bfb") === null) {
			bfbBanner.style.display = "block";	
		}
    }


    //Add a new cookie if user clicks close on cookie banner or close button.
    closeBfbBanner.addEventListener("click", function(){
		closeAndSetCookie();
	});
	closeBfbButton.addEventListener("click", function(){
		closeAndSetCookie();
	});
									
									
	function closeAndSetCookie() {
		sessionStorage.setItem('bfb', 'closed');
        const d = new Date();
        d.setTime(d.getTime() + (timeOutDays * 24 * 60 * 60 * 1000));
        let expires = "expires="+d.toUTCString();
        document.cookie = "bfb-dismiss=true;" + expires + "path=/";
        bfbBanner.style.display = "none";
    };
});