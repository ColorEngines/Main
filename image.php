<?php 
include_once "includes.php";
ob_start();
$filename = $config['images']['src']."/".htmlutil::ValueFromGet('i');

if (!file_exists($filename)) 
{
    echo "BROKEN:: $filename";
    exit();
}
$im = file_get_contents($filename);
header("Content-type: image/jpeg");
echo $im;
exit();

<?php
$size = getimagesize($filename);
$fp = fopen($filename, "rb");
if ($size && $fp) {
    header("Content-type: {$size['mime']}");
    fpassthru($fp);
    exit;
} else {
    // error
}
?>

?>