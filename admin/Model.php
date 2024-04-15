<?php

namespace BannerOn;

use WP_Post, WP_User, BannerOn\Logger;


class Model
{

    private $user;
    private $banner;

    function __construct(WP_Post $banner, WP_User $user)
    {
        $this->banner = $banner;
        $this->user = $user;

        $actioned_meta_field_name = 'banner_' . $this->banner->ID  . '_has_been_actioned';

        //$banner_actioned_status = MetaFields::GetMetaValue($this->banner->ID, $actioned_meta_field_name);
        $banner_target = MetaFields::GetMetaValue($this->banner->ID, 'banneron_target_users');

        // Transform the target type into a class name (extending thw UserIntersect Abstract class)
        if (empty($banner_target)) return;
        $converted_banner_target = "BannerOn\\" . str_replace("-", '', ucwords($banner_target, "-"));

        // This is ugly: can we fix?
        if(!class_exists($converted_banner_target)) {
            Logger::info("Class $converted_banner_target does not exist");
            return;
        
        };
        
        $show = new $converted_banner_target();
        if (!$show->UserIsOfType($user)) {
            Logger::info("Show method returned false. User is not of type $converted_banner_target");
        }
        

        new View($this->banner);
    }
}
