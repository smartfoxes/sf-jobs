<?php

add_shortcode( 'jobs' , "sf_jobs_shortcode");

function sf_jobs_shortcode($atts, $content="") {
    $class = isset($atts['class']) ? $atts['class'] : null;
    ob_start();    
?>
    <table class="table jobs <?php echo $class; ?>">        
        <thead>
            <tr>
                <th>Job Posting</th>
                <th>Department</th>
                <th>Location</th>                
            </tr>
        </thead>
        <tbody>
            
        <?php 
        $args = array( 
            'post_type' => 'jobs',             
            'posts_per_page' => 1000,
            'order' => 'ASC'
        );
        
        $loop = new WP_Query( $args );
        while ( $loop->have_posts() ) : $loop->the_post();
            $department = get_post_meta( get_the_id(), '_sf_jobs_department', true );
            $location = get_post_meta( get_the_id(), '_sf_jobs_location', true );
            ?>
            <tr>
                <td><a href="<?php the_permalink();?>"><?php the_title();?></a></td>
                <td><?php echo $department;?></td>
                <td><?php echo $location;?></td>
            </tr>
            <?php
        endwhile;
        
        ?>
        </tbody>
    </table>        
<?php
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}
