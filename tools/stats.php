<?php  include_once "../includes.php";  ?>
<?php
// expects URL in the get 
$url = htmlutil::ValueFromGet('url');
if (is_null($url)) return;
list($width, $height, $type, $attr) = getimagesize($url);
$result = array();
$result['width'] = $width;
$result['height'] = $height;
$result['mime'] = image_type_to_mime_type($type);
echo json_encode($result);
?>