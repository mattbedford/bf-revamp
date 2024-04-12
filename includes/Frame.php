<?php

namespace BannerOn;


class Frame
{

    private $contents = [];

    function __construct()
    {

        add_action('wp_enqueue_scripts', [$this, 'DoScripts']);
    }


    public function Create(array $contents): void
    {

        $this->contents = $contents;
        add_action('wp_footer', [$this, 'Display'], 20, 1);
    }


    public function Display(): void
    {

        echo "<div class='banneron_overlay'>";
        echo "<div class='banner'>";
        echo "<button class='close' id='closeBannerOn'>&#x2715;</button>";
        echo "<h1>{$this->contents['headline']}</h1>";
        echo "<img src='{$this->contents['image']}' />";
        echo "<p>{$this->contents['content']}</p>";
        echo "</div>";
        echo "</div>";
    }


    public function DoScripts(): void
    {

        wp_enqueue_script('scroll-lock');
        wp_enqueue_style('banneron-styles', plugin_dir_url(__FILE__) . 'assets/banneron.css');

        $rest_args = array(
            'rest_base' => site_url() . "/wp-json/bannertime-api",
            'rest_nonce' => wp_create_nonce('wp_rest'),
        );

        wp_register_script(
            'scroll-lock',
            'https://cdnjs.cloudflare.com/ajax/libs/scroll-lock/2.1.2/scroll-lock.min.js',
            [],
            false,
            false
        );
        wp_register_script('banneron-js', plugin_dir_url(__FILE__) . 'assets/banneron.js', ['scroll-lock'], '', true);


        wp_register_script('frontend_rest_api_vars', false);
        wp_localize_script('frontend_rest_api_vars', 'nonce_object', $rest_args);
        wp_enqueue_script('frontend_rest_api_vars');
        wp_enqueue_script('banneron-js');
    }
}
