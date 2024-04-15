<?php

namespace BannerOn;

use WP_User;

class Free extends Model
{

    public function UserMatchesTarget(WP_User $user): bool
    {

        if (
           user_can($user, 'subscriber')
            && !user_can($user, 'edit_posts')
            && !user_can($user, 'access_s2member_level1')
        ) return true;
        return false;
    }
}
