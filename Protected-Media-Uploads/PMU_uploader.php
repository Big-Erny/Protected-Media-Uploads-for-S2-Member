<?php

///***LETS GET THIS INTO admin jajax ****///
//**** Forget using require wp-load***///
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );

defined( 'ABSPATH' ) or die( 'No direct Access to files.' );

if ( ! function_exists( 'wp_handle_upload' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
}


add_filter('upload_dir', 'switch_upload_directories');

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

$data = array();

if(isset($_GET['files']))
{  
    $error = false;
    $files = array();
    $upload_overrides = array( 'test_form' => false );
    $uploaddir = '../s2member-files/';
    foreach($_FILES as $file)
    {
        //if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
        if(wp_handle_upload( $file, $upload_overrides ) )
        {
            $files[] = $uploaddir .$file['name'];
        }
        else
        {
            $error = true;
        }
    }
    $data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
}
else
{
    $data = array('success' => 'Form was submitted', 'formData' => $_POST);
}

echo json_encode($data);

?>