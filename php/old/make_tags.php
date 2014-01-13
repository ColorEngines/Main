<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'library.php';

ini_set("max_execution_time",6000);

$handle = @fopen("only_images.txt", "r");


if ($handle) {
    while (!feof($handle)) {
        $buffer = fgets($handle);
        if ($buffer != "")
        {
            $buffer = trim($buffer,'.');

            $filepath = trim($buffer);

            $buffer = util::toLastChar($buffer, '.');
            $tagsArray = processSingleFile($buffer);

            //echo "\n$filepath,".join(', ',$tagsArray);
            buildTagGrid($filepath, $tagsArray);
        }

    }
    fclose($handle);
}

function buildTagGrid($filepath, $tagsArray)
{
    // for each file place the file name in a tags_file that is in the $tagsArray

    foreach ($tagsArray as $key => $value) {
        storeToSingleTag($filepath, $value);
    }

}


function storeToSingleTag($filepath, $singleTag)
{

    echo "TAGS For $filepath ... $singleTag\n";

    $fn = config::$realTagIndexSingle .$singleTag.'.txt';
    $fh = fopen($fn, 'a') or die("can't write to ".$fn);
    $stringData  = $filepath."\n";
    fwrite($fh, $stringData);
    fclose($fh);

}



function processSingleFile($value)
{
    $value = trim($value, '/');
    $tags = getTagsFromFolders($value);

    $tags = array_unique($tags);

    $result = array();
    foreach ($result as $key => $value)
    {
        $result[$key] = trim($value);
    }


    return $tags;
}

function getTagsFromFolders($pathname)
{
    $folders = split('/',$pathname);

    // look at eachh part and try to remove "extra stuff"
    $result = array();

    // all but the filename
    for ($index = 0; $index < count($folders) - 1; $index++)
    {
        $result['folders_'.$index] = $folders[$index];
    }

    // process filename
    $fn =  $folders[count($folders)-1];

    // split filename by different ways and try to remove NON-TAGS
    $result['filename_space'] = withNumberLook($fn, ' ');
    
    return $result;
    
}


function withNumberLook($str, $delim)
{
    $numLook = explode($delim,$str);
    $numLookResult = "";
    foreach ($numLook as $single_num_look)
    {
        $add = $single_num_look + 1;
        if ($add == 1)
        {
            // no number
            $numLookResult .= $single_num_look." ";
        }
    }

    return $numLookResult;
}

?>
