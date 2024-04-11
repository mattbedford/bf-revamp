<?php

namespace BannerOn;


class Model {
    
    private $user;
    private $banner;
    
    function __construct($banner, $user)
    {
        $this->banner = $banner;
        $this->user = $user;
        $banner_target = get_post_meta($this->banner->ID, 'banneron_target_users', false);
        foreach($banner_target as $target){
            if($this->IntersectUserCapsPostTargets($target)) {
                //DisplayBanner();
                return;
            }
        }
        

    }
    
    private function IntersectUserCapsPostTargets($target): bool 
    {
        if($target === "school") {
            foreach($this->user->allcaps as $cap => $value){
                if(strpos($cap, 'kcgm') !== false) return true;
            }
            if(user_can($this->user, 's2member_level2')) return true;
            return false;
        }
        if($target === "monthly") {
            if(user_can($this->user, 'monthlystripe')) return true;
            return false;
        }
        if($target === "annual") {
            if(user_can($this->user, 'stripeannual')) return true;
            return false;
        }
    }


    private function EvaluateUserType()
    {
/*
        $user_type = [];
        $this->user = wp_get_current_user();
        if(user_can($this->user, 's2member_level2')) $user_type[] = "school";
        if(user_can($this->user, 'edit_posts')) return "staff";
        if(user_can($this->user, 's2member_level1')) {
            foreach($this->user->allcaps as $cap => $value){
                if(strpos($cap, 'kcgm') !== false) return "school";
            }
            return "premium";
            // Check for monthly/Annual!
        }
        return "subscriber"; 
*/
    }
    
   /* private function DisplayBanner(): void
    {
        
        echo "<div class='banner'>";
        echo "<img src='{$this->banner->image_url}' alt='{$this->banner->alt_text}'>";
        echo "</div>";
        
    }*/
    
}