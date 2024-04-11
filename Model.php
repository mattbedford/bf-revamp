<?php

namespace BannerOn;

use WP_Post, WP_User;


class Model {
    
    private $user;
    private $banner;
    
    function __construct(WP_Post $banner, WP_User $user)
    {
        $this->banner = $banner;
        $this->user = $user;

        $actioned_meta_field_name = 'banner_' . $this->banner->ID  . '_has_been_actioned';

        //$banner_actioned_status = MetaFields::GetMetaValue($this->banner->ID, $actioned_meta_field_name);
        $banner_target = MetaFields::GetMetaValue($this->banner->ID, 'banneron_target_users');
        $matched_caps = $this->IntersectUserCapsPostTargets($banner_target);
        
        if(count($matched_caps) <= 0) {
            write_log("No caps found for user {$this->user->ID} and banner {$this->banner->ID}");
            return;
        }
        if(count($matched_caps) > 1) {
            write_log("Multiple caps found for user {$this->user->ID} and banner {$this->banner->ID}");
            return;
        }
        if(count($matched_caps) === 1) write_log("Single cap found for user {$this->user->ID} and banner {$this->banner->ID}");

        new View($this->banner);  

    }
    
    private function IntersectUserCapsPostTargets(Array $target): Array 
    {

        $count_true_caps_found = [];

        foreach($target as $t){
            if($target === "school") {
                foreach($this->user->allcaps as $cap => $value){
                    if(strpos($cap, 'kcgm') !== false) $count_true_caps_found[] = $cap;
                }
                if(user_can($this->user, 's2member_level2')) $count_true_caps_found[] = 's2member_level2';
                continue;
            }
            if($target === "monthly") {
                if(user_can($this->user, 'monthlystripe')) $count_true_caps_found[] = 'monthlystripe';
                continue;
            }
            if($target === "annual") {
                if(user_can($this->user, 'stripeannual')) $count_true_caps_found[] = 'stripeannual';
                continue;
            }
            if($target === "free") {
                if(user_can($this->user, 's2member_level0')) $count_true_caps_found[] = 's2member_level0';
                continue;
            }
        }

        return ["admin"];
        return $count_true_caps_found;

    }
   
}