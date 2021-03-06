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
    //input type datepicker handler
    $(document).on("click", ".datepicker_handeler", function(event)
    {

        var input=$(this).siblings('input');
        if(input.datepicker( "widget" ).is(":visible"))
        {
            input.datepicker('hide');
        }
        else
        {
            input.datepicker('show');
        }

    });

    //save button click form submit
    $(document).on("click", ".button_action_save", function(event)
    {

        $($(this).attr('data-target-element')).find(".system_save_new_status").val(0);
        $($(this).attr('data-target-element')).submit();
    });

    //save button click form submit
    $(document).on("click", ".button_action_save_new", function(event)
    {

        $($(this).attr('data-target-element')).find(".system_save_new_status").val(1);
        $($(this).attr('data-target-element')).submit();
    });


    //clear button click form
    $(document).on("click", ".button_action_clear", function(event)
    {
        $($(this).attr('data-target-element')).trigger('reset');
    });

    $(document).on("click", ".button_jqx_action", function(event)
    {

        var jqx_grid_id=$(this).attr('data-target-element');
        var selected_row_indexes = $(jqx_grid_id).jqxGrid('getselectedrowindexes');
        if (selected_row_indexes.length > 0)
        {
            if($(this).is('[data-message-confirm]'))
            {
                var sure = confirm($(this).attr('data-message-confirm'));
                if(!sure)
                {
                    return;
                }
            }
            var selectedRowData = $(jqx_grid_id).jqxGrid('getrowdata', selected_row_indexes[selected_row_indexes.length-1]);//only last selected
            console.log(selectedRowData);
            $.ajax({
                url: $(this).attr('data-action-link'),
                type: 'POST',
                dataType: "JSON",
                data:selectedRowData,
                success: function (data, status)
                {

                },
                error: function (xhr, desc, err)
                {
                    console.log("error");

                }
            });
        }
        else
        {
            alert(ALERT_SELECT_ONE_ITEM);
        }

    });
    $(document).on("click", ".system_jqx_column_handler", function(event)
    {
        var jqx_grid_id=$(this).attr('data-target-element');
        $(jqx_grid_id).jqxGrid('beginupdate');
        if($(this).is(':checked'))
        {
            $(jqx_grid_id).jqxGrid('showcolumn', $(this).val());
        }
        else
        {
            $(jqx_grid_id).jqxGrid('hidecolumn', $(this).val());
        }
        $(jqx_grid_id).jqxGrid('endupdate');

    });
    $(document).on("click", ".button_jqx_action_download", function(event)
    {
        var jqx_grid_id=$(this).attr('data-target-element');

        var gridContent = $(jqx_grid_id).jqxGrid('exportdata', 'html');
        var newWindow = window.open('', '', 'width=800, height=500,menubar=yes,toolbar=no,scrollbars=yes'),
            document = newWindow.document.open(),
            pageContent =
                '<!DOCTYPE html>\n' +
                    '<html>\n' +
                    '<head>\n' +
                    '<meta charset="utf-8" />\n' +
                    '<title>'+$(this).attr('data-title')+'</title>\n' +
                    '</head>\n' +
                    '<body>\n' + gridContent + '\n</body>\n</html>';
        document.write(pageContent);
        document.close();
        if($(this).is('[data-print]'))
        {
            if($(this).attr('data-print')==true)
            {
                newWindow.print();
            }
        }


    });
    /*number format input box*/
    $(document).on("input", ".float_positive", function(event)
    {
        this.value = this.value.replace(/[^0-9.]/g, '').replace('.', 'x').replace(/\./g,'').replace('x','.');
    });
    $(document).on("input", ".integer_positive", function(event)
    {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    $(document).on("input", ".float_all", function(event)
    {
        this.value = this.value.replace(/[^0-9.-]/g, '').replace('.', 'x').replace(/\./g,'').replace('x','.').replace(/(?!^)-/g, '');
    });
    $(document).on("input", ".integer_all", function(event)
    {
        this.value = this.value.replace(/[^0-9-]/g, '').replace(/(?!^)-/g, '');
    });
    $(document).on("click",'.select_all',function()
    {
        if($(this).is(':checked'))
        {
            $('.'+$(this).attr('data-type')).prop('checked', true);
        }
        else
        {
            $('.'+$(this).attr('data-type')).prop('checked', false);
        }
    });




});