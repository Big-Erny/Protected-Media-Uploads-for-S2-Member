<?php 

include_once 'PMU_page_content.php';  

//Switch the upload directory from uploads to s2 Member filesystem_method
//needs path fixes to match different domain and innstalls
function switch_upload_directories( $param ){
    $param['basedir'] = '/home/zepd6429/public_html/afiadesign.com/wp-content/plugins/s2member-files';
    $param['baseurl'] = 'http://afiadesign.com/wp-content/plugins/s2member-files';
    $param['path'] = ABSPATH . 'wp-content/plugins/' . 's2member-files';
    $param['url'] = plugin_dir_url() . 's2member-files';
        
    /*error_log("path={$param['path']}");  
    error_log("url={$param['url']}");
    error_log("subdir={$param['subdir']}");
    error_log("basedir={$param['basedir']}");
    error_log("baseurl={$param['baseurl']}");
    error_log("error={$param['error']}");*/

    return $param;
}

function PMU_Upload_callback() {
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    } 
    
    //filter wp core upload directory from uploads folder to S2 Member folder
    add_filter('upload_dir', 'switch_upload_directories'); 
    
    $data = array();
    
    $error = false;
    $upload_overrides = array( 'test_form' => false );
    $uploaddir = '../s2member-files/';
    foreach($_FILES as $file)
    {
        $testing[] = $file['name'];
        $handle = wp_handle_upload( $file, $upload_overrides );

        $testing['image'] =  $image;
        if($handle)
        {
            $testing[] = $handle;
            $type = $handle['type'];
            $url = $handle['url'];
            $file = $handle['file'];
        }
        else
        {
           wp_die($handle['error']);
        }
    }
        
    $message['filePath'] = WP_PLUGIN_DIR . '/s2member-files/' .  $testing[0];   
    $message['fileName'] = $testing[0]; 
    $message['type'] = $type;
    $message['url'] = $url;
    $message['file'] = $file;
    $message['wp-name'] = $_POST['wp-name'];
	wp_die( json_encode($message) );
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
    $fileType = $_POST['type'];
    $fileTypeArray = explode("/", $_POST['type']);
    $pathInfo = pathinfo($filePath);
    $fileName = $pathInfo['filename']. '.' .$pathInfo['extension'];
    $fileNiceName = $_POST['wp-name'];
    
    if($fileNiceName === ''){
        $fileNiceName = $pathInfo['filename'];
    }     
    
  $attachment_feilds = array(
      'ID' => 0,
      'post_type' => 'protected-attachment',
      'post_mime_type' => $fileType,
      'post_title' => $fileNiceName,
      'post_content' => $fileName      
  );


  $post = wp_insert_post( $attachment_feilds );
    
    //mimes application are pdf doc, images are image, videos are video
    if($fileTypeArray[0] !== 'image')
    {
        
        if($fileTypeArray[0] === 'application' || $fileTypeArray[0] === 'text' )
        {
            wp_die(  'application thumbnail: '.$fileName );
        }else if($fileTypeArray[0] === 'video'){
            wp_die(  'video thumbnail: '.$fileName );
        }else if($fileTypeArray[0] === 'audio'){
            wp_die(  'audio thumbnail: '.$fileName );   
        }
        wp_die(  $post .' / '.$fileTypeArray[0] );
    }
    
    ///watching for uppecase extension issue
    //sometimes files are uploaded with with uppercase extensions which wordpress will change to lower case
    //the file path is passed here before the ext is lowercased
    //$fileNameFix = pathinfo($filename);  
    //$filename = $fileNameFix['filename'] . '.' .strtolower($fileNameFix['extension']); 
        
    
    //$filePathFix = pathinfo($filePath);
    //$filePath =  $filePathFix['dirname'] . '/' . $filePathFix['filename'] . '.' . strtolower($filePathFix['extension']);
    
    $image = wp_get_image_editor( $filePath );
    
    if ( !is_wp_error( $image ) ) {
        $image->resize( 150, 150, true );        
        $image->save( $pathInfo['dirname'] . '/thumb-' . $pathInfo['filename'] . '.' . $pathInfo['extension'] );        
    } else {
        wp_die( 'File is invalid for thumbnail creating: '.$filePath );
    }
    wp_die(  'Thumbnail created: '. $post .' / '.$fileName );
	
}

function PMU_Delete_no_priv() {
    $message = 'You must be logged in to upload.';
	wp_die($message );
}
    
function PMU_Delete() {
    $postID = $_POST['PMU_postID']; 
    $fileName = $_POST['PMU_fileName'];
    
    
    $postDeleted = wp_delete_post($postID);
    $message = $postDeleted . ' / ' . $postID . ' / ' . $fileName;
    if($postDeleted){
        //$message = 'Deleted post: $postID' ;  
        $fileDeleted =   unlink(WP_PLUGIN_DIR . '/s2member-files/'.$fileName);
        $thumbDeleted = unlink(WP_PLUGIN_DIR . '/s2member-files/thumb-'.$fileName);
    }else{
        //$message = 'Deleted: failed' ; 
    }
    
	wp_die($message );
}

function PMU_admin_print_styles() {
    $handleStyles = 'PMU-css';
    $src = PMU_PLUGIN_URL . '/css/styles.css';
    wp_register_style($handleStyles, $src);
    wp_enqueue_style($handleStyles);
    
    $handleBoot = 'PMU-Bootstrap-css';
    wp_register_style($handleBoot, '//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css');
    wp_enqueue_style($handleBoot);
    
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
    wp_enqueue_script( 'PMU-upload-script', PMU_PLUGIN_URL  . '/scripts.js', array( 'jquery' ) );
    wp_enqueue_script( 'PMU-Bootstrap-script', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js', array('PMU-upload-script' ) );
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