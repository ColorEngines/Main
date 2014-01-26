<?php  include_once "../includes.php";  ?>
<html>
<head>
</head>    
<body>
<?php
    $url = htmlutil::ValueFromGet('url');
    if (is_null($url)) return;
    $CE = new ColorEngine($ini);
    echo $CE->VisualiseImageHistogramFromURL($url);
    unset($CE);
?>    
</body>
</html>