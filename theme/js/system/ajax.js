/**
 * Created by Shaiful Islam on 14/04/20.
 */

function load_template(content)
{
    for(var i=0;i<content.length;i++)
    {
        $(content[i].id).html(content[i].html);

    }
}
function load_style(content)
{
    for(var i=0;i<content.length;i++)
    {
        if(content[i].style)
        {
            $(content[i].id).attr('style',content[i].style);
        }
        if(content[i].display)
        {
            $(content[i].id).show();
        }
        else
        {
            $(content[i].id).hide();
        }
    }
}
function display_message(message)
{
    console.log(message);
    //$("#system_message").html(message);
    //$("#system_message").animate({right:"100px"}).animate({right:"30px"}).delay(3000).animate({right:"100px"}).animate({right:"-5000px"});
}
$(document).ready(function ()
{
    $(document).ajaxStart(function()
    {
        $('#system_loading').show();
    });
    $(document).ajaxStop(function ()
    {

    });
    $(document).ajaxSuccess(function(event,xhr,options)
    {
        if(xhr.responseJSON)
        {
            if(xhr.responseJSON.system_content)
            {
                load_template(xhr.responseJSON.system_content);
            }
            if(xhr.responseJSON.system_style)
            {
                load_style(xhr.responseJSON.system_style);
            }
        }
    });
    $(document).ajaxComplete(function(event,xhr,options)
    {
        if(xhr.responseJSON)
        {
            if(xhr.responseJSON.system_redirect_url)
            {
                system_resized_image_files=[];
                window.location.replace(xhr.responseJSON.system_redirect_url);
            }
            if(xhr.responseJSON.system_page_url)
            {
                system_resized_image_files=[];
                window.history.pushState(null, "Search Results",xhr.responseJSON.system_page_url);
                //window.history.replaceState(null, "Search Results",xhr.responseJSON.system_page_url);
            }

            //$("#loading").hide();
            $("#system_loading").hide();
            if(xhr.responseJSON.system_message)
            {
                display_message(xhr.responseJSON.system_message);
            }
            if(xhr.responseJSON.system_page_title)
            {
                $('title').html(xhr.responseJSON.system_page_title);
            }

        }
        $('#system_loading').hide();
    });
    $(document).ajaxError(function(event,xhr,options)
    {

        $('#system_loading').hide();
        display_message("Internet/Server Error");

    });

});