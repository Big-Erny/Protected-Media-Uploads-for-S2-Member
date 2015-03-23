<?php 

include_once 'PMU_page_content.php';  

function switch_upload_directories( $param ){
    $param['basedir'] = '/home/zepd6429/public_html/afiadesign.com/wp-content/plugins/s2member-files';
    $param['baseurl'] = 'http://afiadesign.com/wp-content/plugins/s2member-files';
    $param['path'] = ABSPATH . 'wp-content/plugins/' . 's2member-files';
    $param['url'] = plugin_dir_url() . 's2member-files';
        
    error_log("path={$param['path']}");  
    error_log("url={$param['url']}");
    error_log("subdir={$param['subdir']}");
    error_log("basedir={$param['basedir']}");
    error_log("baseurl={$param['baseurl']}");
    error_log("error={$param['error']}");

    return $param;
}

function PMU_Upload_callback() {
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    } 
    if ( ! function_exists( 'wp_get_image_editor' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/class-wp-image-editor.php' );
        $get_image = 'added';
    } 
    
    add_filter('upload_dir', 'switch_upload_directories'); 
    
    $data = array();
    
        $error = false;
        $files = array();
        $upload_overrides = array( 'test_form' => false );
        $uploaddir = '../s2member-files/';
        foreach($_FILES as $file)
        {
            $testing[] = $file['name'];
            $handle = wp_handle_upload( $file, $upload_overrides );
            
            $testing ['image'] =  $image;
            if($handle)
            {
                $testing[] = $handle;
                $files[] = $uploaddir .$file['name'];
            }
            else
            {
                $error = true;
            }
        }
        //$data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);  

    $message['filePath'] = WP_PLUGIN_DIR . '/s2member-files/' .  $testing[0];   
    $message['fileName'] = $testing[0]; 
    
	wp_die(json_encode($message));
}

function PMU_Upload_callback_no_priv() {
    $message = 'You must be logged in to upload.';
	wp_die($message );
}


function PMU_create_thumbnail_callback_no_priv() {
    $message = 'You must be logged in to upload.';
	wp_die($message );
}

function PMU_create_thumbnail_callback() {
    if ( ! function_exists( 'wp_get_image_editor' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/class-wp-image-editor.php' );        
    } 
    
    $filePath = $_POST['path'];     
    $fileName = $_POST['name'];   
    
    $image = wp_get_image_editor( $filePath );
    if ( ! is_wp_error( $image ) ) {
        $image->resize( 150, 150, true );
        $image->save( '../wp-content/plugins/s2member-files/thumbmaster-' . $fileName );
    } 
    wp_die( $filePath . ' / ' . $fileName );
	
}

function PMU_admin_print_styles() {
    $handle = 'PMU-css';
    $src = PMU_PLUGIN_URL . '/css/styles.css';
    wp_register_style($handle, $src);
    wp_enqueue_style($handle);
}

function PMU_admin_menu_init_func() {
    $page_title = 'S2 Protected Media';
    $menu_title = 'S2 Protected Media';
    $capability = 'upload_files';
    $menu_slug = 's2-protected-media';
    $function = 'PMU_output_page_content';
    $PMU_admin_page = add_media_page($page_title, $menu_title, $capability, $menu_slug, $function);
    if($_GET['page'] === 's2-protected-media'){
               
    }
    add_action( 'load-' . $PMU_admin_page, 'load_PMU_admin_js' );
}

// This function is only called when our plugin's page loads!
function load_PMU_admin_js(){
    // Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it
    add_action( 'admin_enqueue_scripts', 'enqueue_PMU_admin_js' );
}

function enqueue_PMU_admin_js(){
    //wp_enqueue_script( 'jquery-ui-core' );//not needed now 
    //wp_enqueue_script( 'jquery-ui-tabs' );//not needed now
    // Isn't it nice to use dependencies and the already registered core js files?
    wp_enqueue_script( 'PMU-upload-script', PMU_PLUGIN_URL  . '/scripts.js', array( 'jquery' ) );
}

function PMU_plugin_action_links( $links ) {
   $links[] = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/upload.php?page=s2-protected-media">Start Uploading</a>';
   return $links;
}

function PMU_register_write_flush() {    
    PMU_custom_post_type_init();    
    flush_rewrite_rules();
}

function PMU_theme_switch_rewrite_flush(){
    flush_rewrite_rules();    
}

function PMU_custom_post_type_init() {
    
}

?>