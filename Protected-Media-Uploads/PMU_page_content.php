<?php
function PMU_output_page_content(){
    
 ?>

                
<h1><?php echo PMU_PLUGIN_NAME; ?></h1>
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
</form>
</div>

<div class="row">
  <div class="col-sm-6 col-md-4">
    <div class="thumbnail">
      <img src="http://afiadesign.com/wp-content/plugins/s2member-files/thumb-ps.jpg" alt="Broken">
      <div class="caption">
        <h3>Thumbnail from php label</h3>        
        <p><a href="#" class="btn btn-default" role="button">URL</a><a href="#" class="btn btn-default" role="button">ShortCode</a></p>
      </div>
    </div>
  </div>
</div>


<!--<form   >
	<input type="file" name="protected_file_upload" id="protected_file_upload"  multiple="false" />
    <input type="text" name="name" id="name"  length="30"/>
	<input id="submit_protected_file_upload" name="submit_protected_file_upload" type="submit" value="Upload Ajax" />
</form>-->


<?php
}

?>