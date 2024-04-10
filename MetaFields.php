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
        $users = self::GetMetaValue($post->ID, 'target_users');
        $nonce = wp_create_nonce( basename(__FILE__) );
       
        echo '<div class="banneron-wrap">';
        echo '<input type="hidden" name="banneron_nonce" value="' .$nonce . '">';  

        echo '<select name="banneron_target_users"';
        echo 'id="banneron_target_users" class="postbox" multiple="multiple">';
        echo '<option value="' . $users . '">' . $users . '</option>'; // TBD
        echo '<option value="xxxxx">All logged-in users</option>'; // TBD
        echo '<option value="yyyyy">Only subscribers</option>'; // TBD
        echo '<option value="zzzzz">Only premium users</option>'; // TBD
        echo '<option value="zzzzz">Only school users</option>'; // TBD
        echo '<option value="zzzzz">Only school users</option>'; // TBD
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
    // Can we move this to avoid checking post type again?
    {
        global $typenow; //Deprecated?

        if( BANNERON_POST_TYPE === ucfirst($typenow) ) {
            wp_register_style( 'banneron-styles',  plugin_dir_path( __FILE__ ) . 'assets/styles.css' );
            wp_enqueue_style('banneron-styles');
            wp_enqueue_style( 'select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css' );
	        wp_enqueue_script( 'select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array( 'jquery' ) );
            
        }
    }


    	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
     * TODO: Find a better return value.....
	 */
	public static function Save(Int $post_id ): Mixed
    {

        $post = get_post($post_id);
        if($post->post_type !== strtolower(BANNERON_POST_TYPE)) return $post_id;

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
		$target = sanitize_text_field( $_POST['target_users'] );

		// Update the meta field.
		update_post_meta( $post_id, 'target_users', $target );
	}


    public static function InitSelect2(): void
    {
        echo "<script>jQuery(document).ready(function() { console.log('wtf man...'); jQuery('#banneron_target_users').select2(); });</script>";
    }


}