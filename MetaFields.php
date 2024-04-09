<?php

namespace BannerOn;

use WP_Post;

abstract class MetaFields {


    public static function Add(): void 
    {

            add_meta_box( "bannertime_control",
             __( 'Banner options' ),
                [ self::class, 'Html'],
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
        $active = self::GetMetaValue($post->ID, 'banner_active');
        $users = self::GetMetaValue($post->ID, 'targewt_users');
        $nonce = wp_create_nonce( basename(__FILE__) );
       
        echo '<div class="banneron-wrap">';
        echo '<input type="hidden" name="banneron_nonce" value="' .$nonce . '">';  

        echo '<input type="checkbox" name="banneron_active"';
        echo 'id="banneron_active" class="postbox" ' . $active . '>';
        echo '<label for="banneron_active">Banner activated</label></div>';

        echo '<select name="banneron_target_users"';
        echo 'id="banneron_target_users" class="postbox">';
        echo '<option value="' . $users . '">' . $users . '</option>'; // TBD
        echo '<option value="xxxxx">xxxxxx</option>'; // TBD
        echo '<option value="yyyyy">yyyyyy</option>'; // TBD
        echo '<option value="zzzzz">zzzzzz</option>'; // TBD
        echo '</select>';

        echo '</div>';

       
    }


    /**
     * Return the meta value for a given field if set.
     *
     * @return array
     */
    public static function GetMetaValue(int $post_id, string $key): string
    {
        return get_post_meta( $post_id, $key, true );
    }



    public static function Style(): void
    {
        global $typenow;

        echo "<script>console.log('adding style. typenow is " . ucfirst($typenow) . "');</script>";
        echo "<script>console.log('adding style. bannertime_post_type is " . BANNERON_POST_TYPE . "');</script>";

        if( BANNERON_POST_TYPE === ucfirst($typenow) ) {
            wp_register_style( 'bannertime-styles',  plugins_url( 'assets/styles.css' , dirname(__FILE__) ));
            wp_enqueue_style('bannertime-styles');
        }
    }


    	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
     * TODO: Find a better return value.....
	 */
	public static function Save(Int $post_id, WP_Post $post ): Mixed
    {

        if($post->post_type != BANNERON_POST_TYPE) return $post_id;

		// Check if our nonce is set.
		if ( ! isset( $_POST['banneron_nonce'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['banneron_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'banneron_nonce' ) ) {
			return $post_id;
		}

		/*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		/* OK, it's safe for us to save the data now. */

		// Sanitize the user input.
		$mydata = sanitize_text_field( $_POST['myplugin_new_field'] );

		// Update the meta field.
		update_post_meta( $post_id, '_my_meta_value_key', $mydata );
	}


}