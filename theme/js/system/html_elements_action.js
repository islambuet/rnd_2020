/**
 * Created by Shaiful Islam on 14/04/20.
 */
$(document).ready(function ()
{
    //binds form submission with ajax
    $(document).on("submit", "form", function(event)
    {
        if($(this).hasClass('system_ajax'))
        {
            event.preventDefault();

            if($(this).is('[data-confirm-message]'))
            {
                var sure = confirm($(this).attr('data-confirm-message'));
                if(!sure)
                {
                    return;
                }
            }
            var form_data=new FormData(this);
            var file;
            for(var i=0;i<system_resized_image_files.length;i++)
            {
                file=system_resized_image_files[i];
                if(form_data.has(file.key))
                {
                    form_data.set(file.key,file.value,file.name);
                }
            }

            $.ajax({
                url: $(this).attr("action"),
                type: $(this).attr("method"),
                dataType: "JSON",
                data: form_data,
                processData: false,
                contentType: false,
                success: function (data, status)
                {

                },
                error: function (xhr, desc, err)
                {


                }
            });
        }
        //else do its own behaviour

    });
    //binds any anchor tag to ajax request
    $(document).on("click", "a", function(event)
    {
        //if link href not found
        if(($(this).attr('href')=='#')||($(this).attr('href')==''))
        {
            event.preventDefault();
            return;
        }
        //system_ajax
        if($(this).hasClass('system_ajax'))
        {
            event.preventDefault();
            //if link has confirm message
            if($(this).is('[data-message-confirm]'))
            {
                var sure = confirm($(this).attr('data-message-confirm'));
                if(!sure)
                {
                    return;
                }
            }
            $.ajax({
                url: $(this).attr("href"),
                type: 'POST',
                dataType: "JSON",
                success: function (data, status)
                {

                },
                error: function (xhr, desc, err)
                {
                    console.log("error");

                }
            });
        }
        //else return or do its own behaviour
    });

    //file tag
    $(document).on("change", ":file", function(event)
    {
        if(($(this).is('[class*="file_external"]')))
        {
            return;
        }
        if(this.files && this.files[0])
        {
            var input_file=$(this);
            var container=$(this).attr('data-preview-container');
            var file=this.files[0];
            var file_type=file.type;
            if(file_type && file_type.substr(0,5)=="image")
            {
                var key=input_file.attr('name');
                var file_name=file.name.replace(/\.[^/.]+$/,"");
                //var file_name=file.name;
                var preview_height=200;
                if($(this).attr('data-preview-height'))
                {
                    preview_height=$(this).attr('data-preview-height');
                }
                var path=URL.createObjectURL(file);
                if(container)
                {
                    var img_tag='';
                    if($(this).attr('data-preview-width'))
                    {
                        var preview_width=$(this).attr('data-preview-width');
                        img_tag='<img width="'+preview_width+'" src="'+path+'" >';
                        $(container).html(img_tag);
                    }
                    else
                    {
                        img_tag='<img height="'+preview_height+'" src="'+path+'" >';
                        $(container).html(img_tag);
                    }
                }
                //if filesize is lower(less than 1.3mb) no need to resize
                var minimum_size_to_resize=SYSTEM_IMAGE_SIZE_TO_RESIZE;
                if($(this).attr('data-resize-size'))
                {
                    minimum_size_to_resize=$(this).attr('data-resize-size');
                }
                if(file.size>minimum_size_to_resize)
                {
                    var MAX_WIDTH = SYSTEM_IMAGE_MAX_WIDTH;
                    if($(this).attr('data-resize-width'))
                    {
                        MAX_WIDTH=$(this).attr('data-resize-width');
                    }
                    var MAX_HEIGHT = SYSTEM_IMAGE_MAX_HEIGHT;
                    if($(this).attr('data-resize-height'))
                    {
                        MAX_HEIGHT=$(this).attr('data-resize-height');
                    }

                    var img=new Image();
                    img.src=path;
                    img.onload=function()
                    {
                        var width = img.naturalWidth;
                        var height = img.naturalHeight;

                        if((width>MAX_WIDTH)||(height>MAX_HEIGHT))
                        {
                            if((width/height)>(MAX_WIDTH/MAX_HEIGHT))
                            {
                                height *= MAX_WIDTH / width;
                                width = MAX_WIDTH;
                            }
                            else
                            {
                                width *= MAX_HEIGHT / height;
                                height = MAX_HEIGHT;
                            }
                            var canvas = document.createElement("canvas");
                            canvas.width = width;
                            canvas.height = height;
                            var context = canvas.getContext("2d");
                            context.drawImage(img, 0, 0, width, height);
                            canvas.toBlob(function(blob)
                            {
                                system_resized_image_files[system_resized_image_files.length]={
                                    key:key,
                                    value:blob,
                                    name:file_name+'.png'
                                };
                                //saveAs(blob, file.name);
                                input_file.val(null);
                                //input_file.parent().find('.badge').remove();
                            });
                            //console.log('with resize');

                        }
                        //console.log('without resize');
                    };
                }
            }
            else if(container)
            {
                $(container).html('Not A Picture');
            }
            $(this).next('.custom-file-label').html(file.name);//show the file name on label

        }
        else
        {
            console.log('no file attached');
        }
    });

});