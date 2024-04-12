<?php

namespace BannerOn;

use WP_Post, BannerOn\Frame;


class View
{


    private $contents = [];


    function __construct(WP_Post $banner, $frame = new Frame())
    {

        $this->LoadContents($banner->ID);
        if (!empty($this->contents)) $frame->Create($this->contents);
    }


    public function LoadContents($banner_id): void
    {

        $this->contents = [
            'headline' => get_the_title($banner_id),
            'image' => get_the_post_thumbnail_url($banner_id),
            'content' => get_the_content(null, false, $banner_id)
        ];
    }
}
