<?php

namespace BannerOn;

use WP_User;


class LoggedIn extends Model
{

    public function UserMatchesTarget(WP_User $user): bool
    {
        
        if($user->exists()) return true;
        return false;
        
    }

}