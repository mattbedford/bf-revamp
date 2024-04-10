<?php

namespace BannerOn;

class View {
    

    function __construct($banner_id) {
        write_log("Banner trying to show in View.php");
        echo "<h1>OUR BANNER ID IS: " . $banner_id . "</h1>";
    }



}