<?php 

/***EXTERNAL FUNCTIONS***/

    

function PMU_admin_print_styles() {
    $handle = 'PMU-css';
    $src = PMU_PLUGIN_URL . 'css/styles.css';
    wp_register_style($handle, $src);
    wp_enqueue_style($handle);
}

function PMU_admin_menu_init_func() {
    $page_title = 'S2 Protected Media';
    $menu_title = 'S2 Protected Media';
    $capability = 'upload_files';
    $menu_slug = 's2-protected-media';
    $function = 'PMU_output_page_content';
    add_media_page($page_title, $menu_title, $capability, $menu_slug, $function);	 
}

function PMU_plugin_action_links( $links ) {
   $links[] = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/upload.php?page=s2-protected-media">Start Uploading</a>';
   return $links;
}


function PMU_output_page_content(){
 ?>
<h1>PMU_PLUGIN_NAME</h1>
<?php
   
}

?>