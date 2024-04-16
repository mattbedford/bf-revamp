<?php

namespace BannerOn;

use WP_User, WP_Query, WP_Post, BannerOn\Model;


class DisplayController
{

    function __construct()
    {

        add_action('template_redirect', [$this, 'HandleBanner']);
    }


    public function HandleBanner(): void
    {

        if (is_admin() || !is_user_logged_in()) return;
        $banner = $this->GetBanner();
        $user = wp_get_current_user();
        $model = $this->GetModelName($banner);

        if (!$user instanceof WP_User || !class_exists($model)) return;
        new $model($banner, $user);
    }


    private function GetBanner(): WP_Post
    {
        $banners = get_posts(['post_type' => BANNERON_POST_TYPE, 'numberposts' => 1]);
        if (count($banners) !== 1) return null;
        else return $banners[0];
    }


    private function GetModelName(WP_Post $banner): string
    {

        $target_users = MetaFields::GetMetaValue($banner->ID, 'banneron_target_users');
        $converted_banner_target = "BannerOn\\" . str_replace("-", '', ucwords((string)$target_users, "-"));
        return $converted_banner_target;
    }
}
