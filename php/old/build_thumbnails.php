<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('library.php');

ini_set("max_execution_time",6000);

echo "Build Thumbnails from Command Line\n";

echo "Read File containing images to process\n";

echo "For each file get gallery image\n";

// $prefix = '/home3/adamfake/www/vnh/images';
$prefix = '';

$handle = @fopen("images_to_process.txt", "r");
if ($handle) {
    while (!feof($handle)) {
        $buffer = fgets($handle);
        if ($buffer != "")
            processSingleFile($prefix,$buffer);
    }
    fclose($handle);
}

function processSingleFile($prefix,$value)
{
     $value = trim($value);
    $value = trim($value,'.');

    $value = $prefix.$value;

    if (file_exists($value) == FALSE)
    {
        echo "\n$value does not exist\n";
        return;
    }

    $pos = strpos($value, 'thumbnails');
    if ($pos !== FALSE) return;

    $ig = new gallery_image($value, config::$thumbnailWidth, config::$thumbnailHeight);

    if ($ig->failed == FALSE)
    {
        echo "\nProcessed ".$ig->imageWebPath()."\n";
    }
    else
    {
        echo "\nFAILED: ".$value."\n";
    }

    $ig->__destruct();
    unset($ig);

}






?>
