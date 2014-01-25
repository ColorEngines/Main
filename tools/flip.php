<?php  include_once "../includes.php";  ?>
<?php
// expects URL in the get 
$url = htmlutil::ValueFromGet('url');
if (is_null($url)) return;

echo $url;

?>