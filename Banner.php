<?php

namespace BannerOn;

// TODO: I don't like how this is set up... let's try to streamline while still creating proper encapsulation and separation of elements.
use BannerOn\CheckUser, BannerOn\View;

class Banner {

    private int $banner_id;

    function __construct() 
    {
        add_action( 'init', [ $this, 'Trigger' ] );

    }

    public function Trigger() {

        write_log("Triggered in Banner.php");

        if($this->OneAndOneOnlyBannersExist() !== true) return;
        $current_user = new CheckUser();

        if(!empty($current_user->GetUserType()) && $this->BannerMatchesUserType()) {

            new View($this->banner_id);

        }

    }


    private function OneAndOneOnlyBannersExist(): bool
    {
        return true;
    }


    private function BannerMatchesUserType(): bool
    {

        $this->banner_id = 39;
        return true;
    
    }


}