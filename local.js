
function imageaction(set,image)
{
    window.location.href = "selected.php?set=" + encodeURIComponent(set) + "&image=" + encodeURIComponent(image);
}

function toolaction(tool,url)
{
    //alert(tool + " " + url );
    window.location.href = "tools/" + tool + ".php?url=" + encodeURIComponent(url);
}
