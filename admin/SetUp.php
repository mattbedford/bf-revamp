<?php


namespace BannerOn;


abstract class SetUp
{


    public static function init()
    {

        self::SetConstants();
        self::LoadRequires();
        self::LoadAdminActionHooks();
    }


    public static function SetConstants(): void
    {

        if (!defined('BANNERON_POST_TYPE')) {
            define('BANNERON_POST_TYPE', 'Banner');
        }
        if(!defined('BANNERON_USER_TYPES')) {
            define('BANNERON_USER_TYPES', ['school', 'free', 'logged-in', 'all-premium']); 
            // later on maybe: 'all-premium-individual', 'premium-monthly', 'premium-annual'
            // kebab-case user types become pascal case in their respective classes (see models/LoggedIn.php for example)
        }
    }


    public static function LoadRequires(): void
    {

        require_once plugin_dir_path(__FILE__) . 'Logger.php';
        require_once plugin_dir_path(__FILE__) . 'MetaFields.php';
        require_once plugin_dir_path(__FILE__) . 'models/Model.php';
        foreach ( glob( plugin_dir_path(__FILE__) . "models/*.php" ) as $filename )
        {
            require_once $filename;
        }
        require_once plugin_dir_path(__FILE__) . 'controllers/DisplayController.php';
        require_once plugin_dir_path(__FILE__) . 'controllers/RestController.php';
        require_once plugin_dir_path(__DIR__) . 'includes/Frame.php';
        require_once plugin_dir_path(__DIR__) . 'includes/View.php';
    }


    public static function LoadAdminActionHooks(): void
    {
        if (is_admin()) {
            add_action('admin_print_styles-post-new.php', ['BannerOn\MetaFields', 'Style']);
            add_action('admin_print_styles-post.php', ['BannerOn\MetaFields', 'Style']);
            add_action('save_post', ['BannerOn\MetaFields', 'Save']);
            add_action('manage_posts_extra_tablenav', [self::class, 'CreateOnSwitch'], 20, 1);
            add_action('wp_loaded', [self::class, 'CreatePostType']);
            add_action('add_meta_boxes', ['BannerOn\MetaFields', 'Add']);
        }
    }


    public static function CreatePostType(): void
    {
        $wp_admin_ui = true;

        $post_type_vars = array(
            'labels' => array(
                'name' => __(BANNERON_POST_TYPE . 's'),
                'singular_name' => __(BANNERON_POST_TYPE),
            ),
            'public' => false,
            'has_archive' => false,
            'show_ui' => $wp_admin_ui,
            'show_in_menu' => $wp_admin_ui,
            'show_in_nav_menus' => $wp_admin_ui,
            'show_in_admin_bar' => $wp_admin_ui,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-grid-view',
            'supports' => array('title', 'editor', 'thumbnail'),
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability_type' => 'post',
            'show_in_rest' => false,
        );

        register_post_type(BANNERON_POST_TYPE, $post_type_vars);
    }


    public static function CreateOnSwitch($where): void
    {
        // TODO: Maybe just make this a warning and not showing any banners if more than one is switched on at a time?
        global $post_type;

        if ($post_type === strtolower(BANNERON_POST_TYPE) && $where === 'top') {
            echo '<div class="alignleft actions">';
            echo '<select name="banneron_post_type" id="banneron_post_type">';
            echo '<option value="all">All post types</option>';
            echo '<option value="post">Posts</option>';
            echo '<option value="page">Pages</option>';
            echo '<option value="banner">Banners</option>';
            echo '</select>';
            echo '<input type="submit" id="post-query-submit" class="button" value="Filter">';
            echo '</div>';
        }
    }
}
