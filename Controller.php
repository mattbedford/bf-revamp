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
        $this->user_type = $this->user->roles[0];
        if(user_can($this->user, 'administrator')) return "admin";

        if (is_user_logged_in()) {
            if(current_user_can('s2member_level2')){
               echo '<li><a>School admin</a></li>';
            } elseif(current_user_can('s2member_level1')){
                echo '<li><a>Premium member</a></li>';
            } elseif(current_user_can('edit_posts')){
                echo '<li><a>Staff user</a></li>';
            } else {
                echo '<li><a href="/membership/">Upgrade today</a></li>';
            }
       }

        return "monthly";        

    }


    public function GetUserType(): mixed
    {

        return $this->user_type;

    }


}