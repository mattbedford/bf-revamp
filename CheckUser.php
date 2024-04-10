<?php

namespace BannerOn;

use WP_User;


class CheckUser {


    private WP_User $user;
    private Mixed $user_type = null;

    function __construct()
    {
        write_log("Checking user in CheckUser.php");
        if(!is_user_logged_in()) return;
        $this->user = wp_get_current_user();
        $this->user_type = $this->EvaluateUserType();

    }


    private function EvaluateUserType(): string
    {

        return "monthly";        

    }


    public function GetUserType(): mixed
    {

        return $this->user_type;

    }


}