<?php

namespace BannerOn;


class RestController
{

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'RestRoutes']);
    }


    public function RestRoutes(): void
    {
        // site_url() . '/wp-json/banner-on/action-completed'
        register_rest_route('banner-on', '/action-completed', array(
            'methods'  => 'POST',
            'callback' => [self::class, "ActionCompleted"],
            'permission_callback' => function () {
                return is_user_logged_in();
            }
        ));
    }


    public static function ActionCompleted($request): mixed
    {
        if (!is_user_logged_in() || !isset($request['id'])) return "No dice baby";
        
        $current_user = wp_get_current_user();
        $key = self::GetMetaKeyName((int)$request['id']); 

        $updated = update_user_meta($current_user->ID, $key, true);
        return $updated;
        
    }


    public static function GetMetaKeyName(int $id): string
    {
        return 'banner_' . $id . '_has_been_actioned';
    }

}
