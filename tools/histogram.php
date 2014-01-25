<?php  include_once "../includes.php";  ?>
<?php
$url = htmlutil::ValueFromGet('url');
if (is_null($url)) return;
$CE = new ColorEngine($ini);

echo $url."<br>";

$histogram = $CE->ImageHistogram($CE->URL2Tempfolder($url));

$max = array_util::Maximum($histogram);
$width = count($histogram);

$divHeight = 200;
$cellWidth = 4;

echo "<img src='{$url}' style='width:200px; height: 200px;' /><br>";

echo "<div style='width: {($width * ($cellWidth + 1))}px; height: {$divHeight}px; margin: 0px; padding: 0px;'>";
foreach ($histogram as $hex => $count) 
{
    $height = round(($count / $max) * $divHeight,0);
    $recipHeight = $divHeight-$height;
    
    echo "<div style='width: {$cellWidth}px; height: {$divHeight}px; float: left; background-color:white; margin-right: 1px;'>"
        ."<div style='width: {$cellWidth}px; height: {$recipHeight}px;  background-color:white;'></div>"
        ."<div style='width: {$cellWidth}px; height: {$height}px; background-color:{$hex};'></div>"
        ."</div>\n";
    
 
}

echo "</div>";


?>