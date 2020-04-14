/**
 * Created by Shaiful Islam on 14/04/20.
 */
$(document).ready(function ()
{
    //binds form submission with ajax
    $(document).on("submit", "form", function(event)
    {
        if($(this).is('[class*="report_form"]'))
        {
            window.open('','form_popup','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=1300,height=500,left = 10,top = 10,scrollbars=yes');
            this.target = 'form_popup';
            return;
        }
        if($(this).hasClass('system_ajax'))
        {
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
            event.preventDefault();
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
    //bind any anchor tag to ajax request
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

});