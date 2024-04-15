<?php

namespace BannerOn;

use WP_Post;

abstract class MetaFields
{


    public static function Add(): void
    {

        add_meta_box(
            "bannertime_control",
            __('Banner options'),
            [self::class, 'Html'],
            BANNERON_POST_TYPE,
            'normal',
            'low'
        );
    }


    /**
     * Display the meta box HTML to the user.
     *
     * @param WP_Post $post   Post object. 
     */
    public static function Html(WP_Post $post): void
    {

        self::Style();
        $saved_user_type = self::GetMetaValue($post->ID, 'banneron_target_users');
        echo "<script>console.log(" . json_encode($saved_user_type) . ")</script>";
        $nonce = wp_create_nonce("banneron_nonce");

        $possible_values = BANNERON_USER_TYPES;

        echo '<div class="banneron-wrap">';
        echo '<input type="hidden" name="banneron_nonce" value="' . $nonce . '">';
        echo '<label>';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path d="M256 464c-114.69 0-208-93.31-208-208S141.31 48 256 48s208 93.31 208 208-93.31 208-208 208zm0-384c-97 0-176 79-176 176s79 176 176 176 176-78.95 176-176S353.05 80 256 80z"/><path d="M323.67 292c-17.4 0-34.21-7.72-47.34-21.73a83.76 83.76 0 0 1-22-51.32c-1.47-20.7 4.88-39.75 17.88-53.62S303.38 144 323.67 144c20.14 0 38.37 7.62 51.33 21.46s19.47 33 18 53.51a84 84 0 0 1-22 51.3C357.86 284.28 341.06 292 323.67 292zm55.81-74zm-215.66 77.36c-29.76 0-55.93-27.51-58.33-61.33-1.23-17.32 4.15-33.33 15.17-45.08s26.22-18 43.15-18 32.12 6.44 43.07 18.14 16.5 27.82 15.25 45c-2.44 33.77-28.6 61.27-58.31 61.27zm256.55 59.92c-1.59-4.7-5.46-9.71-13.22-14.46-23.46-14.33-52.32-21.91-83.48-21.91-30.57 0-60.23 7.9-83.53 22.25-26.25 16.17-43.89 39.75-51 68.18-1.68 6.69-4.13 19.14-1.51 26.11a192.18 192.18 0 0 0 232.75-80.17zm-256.74 46.09c7.07-28.21 22.12-51.73 45.47-70.75a8 8 0 0 0-2.59-13.77c-12-3.83-25.7-5.88-42.69-5.88-23.82 0-49.11 6.45-68.14 18.17-5.4 3.33-10.7 4.61-14.78 5.75a192.84 192.84 0 0 0 77.78 86.64l1.79-.14a102.82 102.82 0 0 1 3.16-20.02z"/></svg>';
        echo 'Select all user types to whom the banner should be shown</label>';
        echo '<select name="banneron_target_users"';
        echo 'id="banneron_target_users" class="postbox">';

        foreach ($possible_values as $val) {
            echo "<option value='" . $val . "'";
            if ($val === $saved_user_type) echo " selected";
            echo ">";
            echo $val;
            echo "</option>";
        }
        echo '</select>';
        echo '</div>';
    }


    /**
     * Return the meta value for a given field if set.
     *
     * @return array
     */
    public static function GetMetaValue(int $post_id, string $key): String
    {
        $meta = get_post_meta($post_id, $key, true);
        if (is_array($meta) || $meta === false) return "";
        return strval($meta);
    }



    public static function Style(): void
    // Can we move this to avoid checking post type again?
    {
        global $typenow; //Deprecated?

        if (BANNERON_POST_TYPE === ucfirst($typenow)) {
            wp_register_style('banneron-styles',  plugin_dir_url(__FILE__) . 'assets/styles.css');
            wp_enqueue_style('banneron-styles');
        }
    }


    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     * TODO: Find a better return value.....
     */
    public static function Save(Int $post_id): Mixed
    {

        $post = get_post($post_id);
        if ($post->post_type !== strtolower(BANNERON_POST_TYPE)) return $post_id;

        // Check if our nonce is set.
        if (!isset($_POST['banneron_nonce'])) {
            return $post_id;
        }

        $nonce = $_POST['banneron_nonce'];

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($nonce, 'banneron_nonce')) {
            return $post_id;
        }

        /*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Check the user's permissions.
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } else {
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }
        }

        /* OK, it's safe for us to save the data now. */
        $target = sanitize_text_field($_POST['banneron_target_users']);

        // Update the meta field.
        update_post_meta($post_id, 'banneron_target_users', $target);
        return "ok";
    }
}
