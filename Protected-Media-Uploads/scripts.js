jQuery.noConflict();
(function( $ ) {
    
$(function() {      
    $(document).ready(function()
    {      

        // Add events
        $('input[type=file]').on('change', prepareUpload);
        $('#form').on('submit', uploadFiles);
        

    });
});  

    $(document).on('change', '.btn-file :file', function() {
  var input = $(this),
      numFiles = input.get(0).files ? input.get(0).files.length : 1,
      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
  input.trigger('fileselect', [numFiles, label]);
});

$(document).ready( function() {
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
    
    
// Variable to store your files
var files;   
// Grab the files and set them to our variable
function prepareUpload(event)
{
  files = event.target.files;
}



// Catch the form submit and upload the files
function uploadFiles(event)
{
  event.stopPropagation(); // Stop stuff happening
    event.preventDefault(); // Totally stop stuff happening

    // START A LOADING SPINNER HERE

    // Create a formdata object and add the files
    var data = new FormData();
    $.each(files, function(key, value)
    {
        data.append(key, value);
    });
    data.append('action', 'PMU_Upload');
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
                
                console.log('success: ' + data);
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
    console.log('DATA: ' + data['fileName']);
    
    var sendData = {
			'action': 'PMU_create_thumbnail',
			'path': data['filePath'],
            'name': data['fileName'],        
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
                console.log('SUCCESS: ' + data);
            }
            else
            {
                // Handle errors here
                console.log('ERRORS: ' + data.error);
            }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            // Handle errors here
            console.log('ERRORS: ' + textStatus);
        },
        complete: function()
        {
            // STOP LOADING SPINNER
        }
    });
}

})(jQuery);