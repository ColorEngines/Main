<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'library.php';

ini_set("max_execution_time",60000);

$db = new database();
$db->deleteTable('tag');
unset($db);

echo "BUILD TAGS\n";
build_tags::run(config::$minimum_tag_count);

// build tag cloud
echo "BUILD TAG CLOUD - start\n";
$tagCloudHTML =  build_tags::tagCloud();
echo "BUILD TAG CLOUD - end\n";

// write Cloud HTML to a file.
echo "WRITE TAG CLOUD to ". config::$realTagCloudHTML. "\n";


$fh = fopen(config::$realTagCloudHTML, 'w') or die("can't open file ".config::$realTagCloudHTML);
fwrite($fh, $tagCloudHTML);
fclose($fh);




?>
