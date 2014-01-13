<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'library.php';


ini_set("max_execution_time",600000);

$suffix = config::$realImagesFolder;

if (isset($argv[1]))
    $suffix .= "/".$argv[1]."/";

$image_list_filename = "all_files.txt";

if (!file_exists($image_list_filename))
{
    $filenames = file_manager::file_tree_filtered($suffix);
    $filenames = file_manager::arrayFilterOut($filenames, "tn_");
    $filenames = file_manager::arrayFilterOut($filenames, "meta");
    $filenames = file_manager::arrayFilterOut($filenames, "png");
    $filenames = file_manager::arrayFilterOut($filenames, "nef");
    $filenames = file_manager::arrayFilterOut($filenames, "NEF");
    $filenames = file_manager::arrayFilterOut($filenames, "bmp");
    $filenames = file_manager::arrayFilterOut($filenames, "xls");




    file_put_contents($image_list_filename, join("\n",$filenames));
}

$filenames = file($image_list_filename);

$allCount = count($filenames);

echo "Filecount = $allCount\n";

$done_list = "images_done.txt";

$done = array();
if (file_exists($done_list)) $done = file($done_list);
$done = array_flip($done);

// $tmp = @"/home3/adamfake/www/vnh/images/library/Bushfires";
//$filenames = file::file_tree($tmp,config::$fs_folder_sep);

// if we have to many files to create we need to split it up 
// look at top level ? 


for ($index = 0; $index < count($filenames); $index++) {

    $value = $filenames[$index];

    if (array_key_exists($value, $done)) continue; // if it's in done list then don't do again.

    echo "[$index / $allCount] $value\n";
    $gi = new gallery_image($value, config::$thumbnailWidth, config::$thumbnailHeight,TRUE); // FALSE = dont rewrite Meta use TRUE to write

    $gi->__destruct();

    unset($gi);
    
    file_put_contents($done_list, $value."\n",FILE_APPEND);
    
}


?>
