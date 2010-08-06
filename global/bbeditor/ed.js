/*****************************************/
// Name: Javascript Textarea BBCode Markup Editor
// Version: 1.3
// Author: Balakrishnan
// Last Modified Date: 25/jan/2009
// License: Free
// URL: http://www.corpocrat.com
/******************************************/

document.write("<link href=\"global/bbeditor/styles.css\" rel=\"stylesheet\" type=\"text/css\">");


function AddBBCodeToolbar(obj)
{
    var html = '<div class="toolbar">'
             + '<img class="button" src="global/bbeditor/images/bold.gif" name="BoldButton" title="Bold" onClick="AddTags(\'[b]\', \'[/b]\', \'' + obj + '\');">'
             + '<img class="button" src="global/bbeditor/images/italic.gif" name="ItalicButton" title="Italic" onClick="AddTags(\'[i]\', \'[/i]\', \'' + obj + '\');">'
             + '<img class="button" src="global/bbeditor/images/underline.gif" name="UnderlineButton" title="Underline" onClick="AddTags(\'[u]\',\'[/u]\', \'' + obj + '\');">'
             + '<img class="button" src="global/bbeditor/images/link.gif" name="LinkButton" title="Insert URL Link" onClick="AddURL(\'' + obj + '\');">'
             + '<img class="button" src="global/bbeditor/images/picture.gif" name="PictureButton" title="Insert Image" onClick="AddImage(\'' + obj + '\');">'
             + '<img class="button" src="global/bbeditor/images/quote.gif" name="QuoteButton" title="Quote" onClick="AddTags(\'[quote]\', \'[/quote]\', \'' + obj + '\');">'
             + '<img class="button" src="global/bbeditor/images/code.gif" name="CodeButton" title="Code" onClick="AddTags(\'[code]\', \'[/code]\', \'' + obj + '\');">'
             + '</div>';

    document.write(html);
}

function AddImage(obj)
{
    var textarea = document.getElementById(obj);
    var url = prompt('Enter the Image URL:', 'http://');
    var scrollTop = textarea.scrollTop;
    var scrollLeft = textarea.scrollLeft;

    if (url != '' && url != null)
    {

        if (document.selection)
        {
            textarea.focus();
            var sel = document.selection.createRange();
            sel.text = '[img]' + url + '[/img]';
        }
        else
        {
            var len = textarea.value.length;
            var start = textarea.selectionStart;
            var end = textarea.selectionEnd;

            var sel = textarea.value.substring(start, end);
            //alert(sel);
            var rep = '[img]' + url + '[/img]';
            textarea.value = textarea.value.substring(0, start) + rep + textarea.value.substring(end, len);


            textarea.scrollTop = scrollTop;
            textarea.scrollLeft = scrollLeft;
        }
    }

}

function AddURL(obj)
{
    var textarea = document.getElementById(obj);
    var url = prompt('Enter the URL:', 'http://');
    var scrollTop = textarea.scrollTop;
    var scrollLeft = textarea.scrollLeft;

    if (url != '' && url != null)
    {

        if (document.selection)
        {
            textarea.focus();
            var sel = document.selection.createRange();

            if (sel.text == "")
            {
                sel.text = '[url]' + url + '[/url]';
            }
            else
            {
                sel.text = '[url=' + url + ']' + sel.text + '[/url]';
            }

            //alert(sel.text);
        }
        else
        {
            var len = textarea.value.length;
            var start = textarea.selectionStart;
            var end = textarea.selectionEnd;

            var sel = textarea.value.substring(start, end);

            if (sel == "")
            {
                var rep = '[url]' + url + '[/url]';
            }
            else
            {
                var rep = '[url=' + url + ']' + sel + '[/url]';
            }
            //alert(sel);
            textarea.value = textarea.value.substring(0, start) + rep + textarea.value.substring(end, len);


            textarea.scrollTop = scrollTop;
            textarea.scrollLeft = scrollLeft;
        }
    }
}

function AddTags(opentag, closetag, obj)
{
    var textarea = document.getElementById(obj);
    // Code for IE
    if (document.selection)
    {
        textarea.focus();
        var sel = document.selection.createRange();
        //alert(sel.text);
        sel.text = opentag + sel.text + closetag;
    }
    else
    { // Code for Mozilla Firefox
        var len = textarea.value.length;
        var start = textarea.selectionStart;
        var end = textarea.selectionEnd;


        var scrollTop = textarea.scrollTop;
        var scrollLeft = textarea.scrollLeft;


        var sel = textarea.value.substring(start, end);
        //alert(sel);
        var rep = opentag + sel + closetag;
        textarea.value = textarea.value.substring(0, start) + rep + textarea.value.substring(end, len);

        textarea.scrollTop = scrollTop;
        textarea.scrollLeft = scrollLeft;
    }
}