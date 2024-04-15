<?php

namespace BannerOn;

use WP_User;


class School_UI extends Model
{

    public function UserMatchesTarget(WP_User $user): bool
    {
        foreach ($user->allcaps as $cap => $value) {
            if (strpos($cap, 'kcgm') !== false) return true;
        }
        if (user_can($user, 's2member_level2')) return true;
        return false;
    }

}