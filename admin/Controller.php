<?php

namespace BannerOn;

use WP_User, WP_Query, WP_Post, BannerOn\Model;


class Controller
{

    private $model = "";

    function __construct()
    {

        add_action('template_redirect', [$this, 'BannerIsAvailable']);
    }


    public function BannerIsAvailable(): void
    {

        // Too much going on in here: let's break it up some
        if (is_admin() || !is_user_logged_in()) return;

        $banners = new WP_Query(['post_type' => BANNERON_POST_TYPE]);
        if ($banners->found_posts !== 1) return;

        $banner = $banners->posts[0];
        $user = wp_get_current_user();
        $this->model = $this->GetModelName($banner);

        if (!$user instanceof WP_User || !class_exists($this->model)) return;
        new $this->model($banner, $user);
        
    }


    private function GetModelName(WP_Post $banner): string 
    {

        $target_users = MetaFields::GetMetaValue($banner->ID, 'banneron_target_users');
        $converted_banner_target = "BannerOn\\" . str_replace("-", '', ucwords((string)$target_users, "-"));
        return $converted_banner_target;
    
    }
}
