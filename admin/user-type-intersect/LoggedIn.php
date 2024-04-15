<?php

namespace BannerOn;

use WP_User;


class LoggedIn extends UserIntersect
{

    public function UserIsOfType(WP_User $user): bool
    {
        
        if($user->exists()) return true;
        return false;
        
    }

}