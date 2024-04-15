<?php

namespace BannerOn;

use WP_User;


abstract class UserIntersect
{

    abstract public function UserIsOfType(WP_User $user): bool;

}
