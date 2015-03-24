<?php
function PMU_output_page_content(){
    
 ?>

                
<h1><?php echo PMU_PLUGIN_NAME; ?></h1><p>(Reload Page to view new uploads)</p>
<div class="row">
<form id="form" class="navbar-form navbar-left">
  <div class="form-group">           
          
          <!--<input type="file" class="btn btn-default"  name="protected_file_upload" id="protected_file_upload"  multiple="false" />-->
      <div class="input-group">
                <span class="input-group-btn">
                    <span class="btn btn-primary btn-file">
                        Browse&hellip; <input type="file" name="protected_file_upload" id="protected_file_upload"  multiple="false"/>
                    </span>
                </span>
                <input type="text" class="form-control" readonly>
            </div>
      <input placeholder="File Name (Optional)" class="form-control" type="text" name="name" id="name"  length="30"/>
  </div>
  <button type="submit" id="submit_protected_file_upload" name="submit_protected_file_upload" class="btn btn-default">Upload</button>
</form><img id="PMU_loader" src="<?php echo plugins_url() .'/'. PMU_PLUGIN_NAME .'/ajax-loader.gif' ?>"/>
</div>
<div id="PMU_success" class="alert alert-success col-sm-6" role="alert">Green is good :) Upload was successful!</div>
<div id="PMU_warning" class="alert alert-warning col-sm-6" role="alert">
    Only images, docs, videos, or audio are allowed by wordpress. Please check file type and/or path you loaded and try agian.
    Contact the plugin developer if the problem keeps occuring.
</div>
<hr>
<h2> File List </h2>
<div class="row">
<?php 
    
    $args = array('post_type' => 'protected-attachment', 'posts_per_page' =>-1);
    $my_query = new WP_Query( $args ); 
    while ( $my_query->have_posts() ) : $my_query->the_post(); 
?>    
    
  <div class="col-sm-4 col-md-3 currentpostdiv">
    <div class="thumbnail">
        
        
        
        
        <?php         
            $fileTypeArray = explode("/", get_post_mime_type() ); 
            if($fileTypeArray[0] !== 'image'){
            $img = wp_mime_type_icon( get_post_mime_type() );
        ?>
        
        <img src="<?php echo $img ?>" style="height:150px;" alt="Broken">
        
        <?php                
            }else{                
        ?>
        
        <img src="<?php echo plugins_url(); ?>/s2member-files/thumb-<?php echo get_the_content(); ?>" alt="Broken">
        
        <?php
                
            }     
        
        ?>  
      
      <div class="caption">
        <h3><?php if(get_the_title() !== 'undefined' ){echo get_the_title();}else{echo get_the_content();} ?></h3>        
        <p>           
            <button data-code="[s2File download='<?php echo get_the_content(); ?>' /]" type="button" class="btn btn-primary" data-toggle="modal" data-target="#PMU_Modal">
                ShortCode
            </button>
            <button data-code="<?php echo get_site_url(); ?>/?s2member_file_download=<?php echo get_the_content(); ?>" type="button" class="btn btn-default" data-toggle="modal" data-target="#PMU_Modal">
                URL
            </button>
            <button data-postid="<?php echo get_the_ID(); ?>" data-postcontent="<?php echo get_the_content(); ?>" type="button" class="btn btn-danger PMU_delete">
                Delete
            </button>  
        </p>
      </div>
    </div>
  </div>
    
    <?php endwhile; ?>


    
</div>


<div class="modal fade" id="PMU_Modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <p id="PMU_snippet"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>        
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
}

?>