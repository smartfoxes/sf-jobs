<?php

/**
* Add custom post types
*/
add_action( 'init', 'sf_jobs_create_post_type' );

function sf_jobs_create_post_type() {
    register_post_type( 'jobs',
		array(
			'labels' => array(
				'name' => __( 'Job Postings' ),
				'singular_name' => __( 'Job Posting' ),
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Job Posting',    		    
			),
			'register_meta_box_cb' => 'sf_jobs_metaboxes',
    		
    		'public' => true,
    		'has_archive' => false,
    		'show_ui' => true,
    		'supports' => array(
    		    'title', "editor"
    	    ),
    	    'rewrite' => array(
    	        'slug' => 'careers',
    	        'with_front' => false,
    	        'ep_mask' => EP_POSTS 
    	    ),
    	    'capability_type' => 'post'
		)
	);	
}

function sf_jobs_metaboxes() {
    add_meta_box(
        'sf_jobs_options',
        __( 'Position Options', 'sf_jobs_textdomain' ),
        'sf_jobs_metabox_html',
        "jobs",
        "normal"
    );
}

function sf_jobs_metabox_html($post) {
    
    wp_nonce_field( 'sf_jobs_options', 'sf_jobs_options_metabox_nonce' );
    
    $department = get_post_meta( $post->ID, '_sf_jobs_department', true );
    $location = get_post_meta( $post->ID, '_sf_jobs_location', true );
    ?>
    <p>
    <label for="sf_jobs_department">Department</label>
        <input type=text class="widefat" type="text" id="sf_jobs_department" name="sf_jobs_department" value="<?php echo esc_attr($department);?>">                  
    </p>
    
    <p>
        <label for="sf_jobs_location">Location</label>
        <input type=text class="widefat" type="text" id="sf_jobs_location" name="sf_jobs_location" value="<?php echo esc_attr($location);?>">          
    </p>
    <?php		
}

/**
 * When the post is saved, saves our custom data.
 * @param int $post_id The ID of the post being saved.
 */
function sf_jobs_save_postdata( $post_id ) {
    // Check if our nonce is set.
    
    if ( ! isset( $_POST['sf_jobs_options_metabox_nonce'] ) ) {
        return $post_id;
    }
    $nonce = $_POST['sf_jobs_options_metabox_nonce'];
    if ( ! wp_verify_nonce( $nonce, 'sf_jobs_options' ) ) {
        return $post_id;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    // Check the user's permissions.
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;    
    }
    $department = sanitize_text_field( $_POST['sf_jobs_department'] );
    update_post_meta( $post_id, '_sf_jobs_department', $department );    
    
    $location = sanitize_text_field( $_POST['sf_jobs_location'] );
    update_post_meta( $post_id, '_sf_jobs_location', $location );    
}
add_action( 'save_post', 'sf_jobs_save_postdata' );
