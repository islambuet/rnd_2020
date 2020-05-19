/**
 * Created by Shaiful Islam on 14/04/20.
 */

//number format function like php
function number_format(number, decimals, dec_point, thousands_sep)
{
    number = (number + '')
        .replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + (Math.round(n * k) / k)
                .toFixed(prec);
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
        .split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '')
        .length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1)
            .join('0');
    }
    return s.join(dec);
}

function get_dropdown_with_select(items,selected_value = '',select_label='Select')
{
    var dropdown_html='<option value="">'+select_label+'</option>';
    for(var i=0;i<items.length;++i)
    {
        dropdown_html+='<option value="'+items[i].value+'"';
        if(items[i].value==selected_value)
        {
            dropdown_html+=' selected';
        }
        dropdown_html+='>'+items[i].text+'</option>';
    }
    return dropdown_html;
}
var header_render=function (text, align)
{
    var words = text.split(" ");
    var label=words[0];
    var count=words[0].length;
    for (i = 1; i < words.length; i++)
    {
        if((count+words[i].length)>10)
        {
            label=label+'</br>'+words[i];
            count=words[i].length;
        }
        else
        {
            label=label+' '+words[i];
            count=count+words[i].length;
        }

    }
    return '<div style="margin: 5px;">'+label+'</div>';
};