<?php
function PMU_output_page_content(){
    
 ?>

<h1><?php echo PMU_PLUGIN_NAME; ?></h1>

<pre>
<form id="form"  >
	<input type="file" name="protected_file_upload" id="protected_file_upload"  multiple="false" />
	<input id="submit_protected_file_upload" name="submit_protected_file_upload" type="submit" value="Upload Ajax" />
</form>
</pre>

<?php
    
    
if ( 
	isset( $_POST['protected_file_upload_nonce'], $_POST['post_id'] ) 
	&& wp_verify_nonce( $_POST['protected_file_upload_nonce'], 'protected_file_upload' )
	&& current_user_can( 'edit_post', $_POST['post_id'] )
) {
	// The nonce was valid and the user has the capabilities, it is safe to continue.	
	// Let WordPress handle the upload.
	// Remember, 'protected_file_upload' is the name of our file input in our form above.
	//$attachment_id = media_handle_upload( 'protected_file_upload', $_POST['post_id'] );
	
	if ( is_wp_error( $attachment_id ) ) {
		// There was an error uploading the image.
	} else {
		// The image was uploaded successfully!
	}

} else {

	// The security check failed, maybe show the user an error.
}
    
    
}

?>