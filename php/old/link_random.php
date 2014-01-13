<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'library.php';
ini_set("max_execution_time",600000);

$suffix = config::$realImagesFolder;

$full_list = 'full_list.txt';

if (file_exists($full_list))
	$filenames = file($full_list);
else 
{

    $filenames = file_manager::file_tree_filtered($suffix);
    $filenames = file_manager::arrayFilterOut($filenames, "tn_");
    $filenames = file_manager::arrayFilterOut($filenames, "meta");
    $filenames = file_manager::arrayFilterOut($filenames, "png");
    $filenames = file_manager::arrayFilterOut($filenames, "nef");
    $filenames = file_manager::arrayFilterOut($filenames, "NEF");
    $filenames = file_manager::arrayFilterOut($filenames, "txt");
    $filenames = file_manager::arrayFilterOut($filenames, "xls");
    $filenames = file_manager::arrayFilterOut($filenames, "bmp");
    $filenames = file_manager::arrayFilterOut($filenames, ".db");

    $filenames = array_values($filenames);

    file_put_contents($full_list,join("\n",$filenames));

}


$allCount = count($filenames);


$randArray = array();
for ($i=0; $i < 10; $i++)
{
    $rnd = rand(0,$allCount);
    $randArray[] = $filenames[$rnd];
}


//$randWeb = array();
for ($index = 0; $index < count($randArray); $index++) {

    $value = $randArray[$index];

    // echo "[$index / ".count($randArray)."] $value\n";
    $gi = new gallery_image($value, config::$thumbnailWidth, config::$thumbnailHeight,FALSE); // FALSE = dont rewrite Meta use TRUE to write

    $a = htmlutil::img_raw($gi->thumbWebPath(),'style="float:left; padding: 8px; border: 1px solid DarkOrange;"');
    $a = '<a href="http://viewnaturalhistory.com/gallery/index.php" target="_new"  border="0">'.$a.'</a>';
    echo htmlutil::div( $a , "", 'height:200px; width:200px; margin:10px; float:left; overflow:hidden;')."\n" ;

    $gi->__destruct();

    unset($gi);
    
}

echo htmlutil::brRowBreak();

//print_r($randWeb);

?>
