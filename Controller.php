<?php

namespace BannerOn;

use WP_User;


class Controller {


    private WP_User $user;
    private Mixed $user_type = null;

    function __construct()
    {

        if(is_admin() || !is_user_logged_in()) return;
        add_action( 'wp_head', [ $this, 'EvaluateUserType' ] );

    }


    private function EvaluateUserType(): string
    {

        $this->user = wp_get_current_user();
        

        return "monthly";        

    }


    public function GetUserType(): mixed
    {

        return $this->user_type;

    }


}