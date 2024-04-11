<?php

namespace BannerOn;

use WP_User, WP_Query, BannerOn\Model;


class Controller {


    function __construct()
    {

        // This may not be the right hook.... needs to be before enqueue scripts
        add_action( 'template_redirect', [ $this, 'BannerIsAvailable' ] );   
        
    }


    public function BannerIsAvailable(): void
    {
        
        if(is_admin() || !is_user_logged_in()) return;

        $banners = new WP_Query( [ 'post_type' => BANNERON_POST_TYPE ] );
        if($banners->found_posts !== 1) return;

        $banner = $banners->posts[0];
        $user = wp_get_current_user();
        if(!$user instanceof WP_User) return;

        new Model($banner, $user); 
        
    }

}