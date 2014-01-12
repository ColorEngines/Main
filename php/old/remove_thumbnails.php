<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



include_once 'library.php';

cleanFiles("tn_");
cleanFiles(".metadata");
cleanFiles(".DS_Store");
cleanFiles(".tag");


cleanFolders("thumbnails");


function cleanFiles($filter)
{
    $tn = file_manager::file_tree_filtered("/home6/adamfake/www/vnh/images/","/",$filter);

    echo "FILES: [$filter] = ".count($tn)."\n";

    foreach ($tn as $filename) {
        unlink($filename);
    }

}

function cleanFolders($filter)
{
    $tn = file_manager::file_tree("/www/vnh/images/","/");

    // print_r($tn);

    $tn = file_manager::arrayFilter($tn, $filter);

    echo "FOLDERS: [$filter] = ".count($tn)."\n";

    foreach ($tn as $filename) {

        // echo "Folders: $filename\n";
        if (is_dir($filename)) rmdir ($filename);
    }

}

?>
