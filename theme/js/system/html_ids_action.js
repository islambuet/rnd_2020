/**
 * Created by Shaiful Islam on 14/04/20.
 */
$(document).ready(function ()
{
    //input type password eye button
    $(document).on("click", ".eye_password", function(event)
    {
        var input=$(this).siblings('input');
        if(input.attr('type')=='password')
        {
            $(this).find('i').removeClass('fe-eye').addClass('fe-eye-off');
            input.attr('type','text');
        }
        else
        {
            $(this).find('i').removeClass('fe-eye-off').addClass('fe-eye');
            input.attr('type','password');
        }
    });

    //save button click form submit
    $(document).on("click", ".button_action_save", function(event)
    {

        $($(this).attr('data-target-element')).find(".system_save_new_status").val(0);
        $($(this).attr('data-target-element')).submit();
    });
    //clear button click form
    $(document).on("click", ".button_action_clear", function(event)
    {
        $($(this).attr('data-target-element')).trigger('reset');
    });



});