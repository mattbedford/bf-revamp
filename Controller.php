<?php

namespace BannerOn;

use WP_User, WP_Post, WP_Query, BannerOn\Model;


class Controller {


    private WP_User $user;
    private WP_Post $banner;


    function __construct()
    {

        add_action( 'wp_head', [ $this, 'BannerIsAvailable' ] );   
        
        if($this->banner && $this->user) {
           new Model($this->banner, $this->user);
        }

    }


    private function BannerIsAvailable(): void
    {
        
        if(is_admin() || !is_user_logged_in()) return;

        $banners = new WP_Query( [ 'post_type' => BANNERON_POST_TYPE ] );
        if($banners->found_posts !== 1) return;

        $this->banner = $banners[0];
        $this->user = wp_get_current_user();
        
    }

}