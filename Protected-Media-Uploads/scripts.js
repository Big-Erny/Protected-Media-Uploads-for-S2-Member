jQuery.noConflict();
(function( $ ) {
    
$(function() {      
    $(document).ready(function()
    {      

        // Add events
        $('input[type=file]').on('change', prepareFileUpload);
        $('input[type=text]').on('change', prepareTextUpload);
        $('#form').on('submit', uploadFiles);
        
        $('.PMU_delete').on('click', function(event){
            
            var PMU_postID = $(this).data('postid'); 
            var PMU_fileName = $(this).data('postcontent'); 
            console.log(event.relatedTarget);
            console.log(PMU_fileName);
            $( event.target ).closest( ".currentpostdiv" ).fadeOut( "slow" );
            deleteImagesPost(PMU_postID,PMU_fileName);
        });       
        
        
        $('#PMU_Modal').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget); 
          var code = button.data('code'); 
          
          var modal = $(this);
          modal.find('.modal-title').text('Copy/Paste into post or page');
          modal.find('.modal-body p').html(code);
        });
        
        $(document).on('change', '.btn-file :file', function() {
            var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
        });
        
        $('.btn-file :file').on('fileselect', function(event, numFiles, label) {        
            var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;
        
            if( input.length ) {
                input.val(log);
            } else {
                if( log ) alert(log);
            }
        
        });
        

    });
});  

    
    
    
// Variable to store your files
var files;   
var text;
// Grab the files and set them to our variable
function prepareFileUpload(event){
    files = event.target.files;
    console.log('@@@: '+ files)
}
function prepareTextUpload(event){  
    text = $('#name').val();
    console.log('@@@: '+ text)
}



// Catch the form submit and upload the files
function uploadFiles(event)
{
  event.stopPropagation(); // Stop stuff happening
    event.preventDefault(); // Totally stop stuff happening

    // START A LOADING SPINNER HERE
    $('#PMU_loader').show();
    $("#form :input").prop("disabled", true);
    // Create a formdata object and add the files
    var data = new FormData();
    $.each(files, function(key, value)
    {
        data.append(key, value);
    });
    data.append('action', 'PMU_Upload');
    data.append('wp-name', text);
    $.ajax({
       // url: 'http://afiadesign.com/wp-content/plugins/Protected-Media-Uploads/PMU_uploader.php?files',
        url: ajaxurl,
        type: 'POST',
        data: data,
        cache: false,
        //dataType: 'Json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function(data, textStatus, jqXHR)
        {
            if(typeof data.error === 'undefined')
            {
                // Success so call function to process the form
                
                console.log('successFile: ' + data);
                PMU_CreateThumbnail(JSON.parse(data));                
            }
            else
            {
                // Handle errors here
                console.log('ERRORS1: ' + data.error);
            }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            // Handle errors here
            console.log('ERRORS2: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}    
    
    function PMU_CreateThumbnail(data)
{
    console.log('EVENT: ' + event);
    console.log('urlDATA: ' + data['url']);
    console.log('typeDATA: ' + data['type']);
    console.log('pathDATA: ' + data['file']);
    console.log('nameDATA: ' + data['wp-name']);
    console.log('pathOldDATA: ' + data['filePath']);
    console.log('nameOldDATA: ' + data['fileName']);
    
    var sendData = {
			'action': 'PMU_create_thumbnail',
			'path': data['file'],
            'type': data['type'],
            'wp-name': data['wp-name']
		};

    
    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: sendData,
        cache: false,
        //dataType: 'json',
        success: function(data, textStatus, jqXHR)
        {
            
            if(typeof data.error === 'undefined')
            {
                // Success so call function to process the form
                console.log('SUCCESSThumb: ' + data);
                $('#PMU_success').show();
                $('#PMU_success').delay(1500).fadeOut("slow");
                $("#form :input").prop("disabled", false);
                
            }
            else
            {
                $('#PMU_warning').show();
                $('#PMU_warning').delay(1500).fadeOut("slow");
                console.log('ERRORSthum: ' + data.error);
            }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            $('#PMU_warning').show();
                $('#PMU_warning').delay(1500).fadeOut("slow");
            console.log('ERRORSthum2: ' + textStatus);
        },
        complete: function()
        {
            $('#PMU_loader').hide();
            // STOP LOADING SPINNER
        }
    });
}
    
    
// DELETE FILES AND POST
function deleteImagesPost(PMU_postID,PMU_fileName)
{
  event.stopPropagation(); // Stop stuff happening
    event.preventDefault(); // Totally stop stuff happening

    
    var data = new FormData();
    data.append('action', 'PMU_Delete');
    data.append('PMU_postID', PMU_postID);
    data.append('PMU_fileName', PMU_fileName);
    
    $.ajax({
       // url: 'http://afiadesign.com/wp-content/plugins/Protected-Media-Uploads/PMU_uploader.php?files',
        url: ajaxurl,
        type: 'POST',
        data: data,
        cache: false,
        //dataType: 'Json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function(data, textStatus, jqXHR)
        {
            if(typeof data.error === 'undefined')
            {
                // Success so call function to process the form                
                console.log('successDeleteFile: ' + data);                            
            }
            else
            {
                // Handle errors here
                console.log('ERRORS1: ' + data.error);
            }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            // Handle errors here
            console.log('ERRORS2: ' + textStatus);
            // STOP LOADING SPINNER
        }
    });
}    

})(jQuery);