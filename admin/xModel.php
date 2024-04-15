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
        

        

        // This is ugly: can we fix?
        if(!class_exists($converted_banner_target)) {
            Logger::info("Class $converted_banner_target does not exist");
            return;
        
        };
        
        new $converted_banner_target($this->banner);
        
    }
}
