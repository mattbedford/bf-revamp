<?php

namespace BannerOn;

use WP_User, WP_Post, BannerOn\View, BannerOn\RestController;


abstract class Model
{

    abstract public function UserMatchesTarget(WP_User $user): bool;


    public function __construct(WP_Post $banner, WP_User $user)
    {

        if (!$this->UserMatchesTarget($user) || $this->BannerAlreadyActioned($user->ID, $banner->ID)) return;
        new View($banner);
    }


    public function BannerAlreadyActioned(int $user_id, int $banner_id): bool
    {

        $banner_actioned_status = boolval(get_user_meta($user_id, 'banner_' . $banner_id  . '_has_been_actioned', true));
        if ($banner_actioned_status !== false) return true;
        return false;
    }
}
