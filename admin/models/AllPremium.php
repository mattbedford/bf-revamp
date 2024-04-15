<?php

namespace BannerOn;

use WP_User;


class AllPremium extends Model
{

    public function UserMatchesTarget(WP_User $user): bool
    {
        if (user_can($user, 'access_s2member_level1')) return true;
        return false;
    }
}
