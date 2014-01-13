<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'library.php';

$tagFilenames = build_tags::tagFilenames();

//$tagFilenames = array();
//$tagFilenames['birds'] = 'artistic';
//$tagFilenames['amaryllidaceae'] = 'amaryllidaceae';
//$tagFilenames['plants'] = 'plants';
//$tagFilenames['f'] = 'f';
//$tagFilenames['apocynaceae'] = 'apocynaceae';
//$tagFilenames['ex hort cairns'] = 'ex hort cairns';
//$tagFilenames['snakes'] = 'snakes';
//$tagFilenames['forbs'] = 'forbs';
//$tagFilenames['beaches'] = 'beaches';
//$tagFilenames['kewarra beach'] = 'kewarra beach';



echo "Get Index tag files ". implode("\n",$tagFilenames) . "\n";

foreach ($tagFilenames as $tag => $tagIndexFilename)
{

    $tip = strpos($tagIndexFilename,".tagIndex");
    if ($tip == FALSE) continue;


    $tagHTMLFilename = config::$realTagIndexSingle.$tag.'.php';

    if (file_exists($tagHTMLFilename) == TRUE) continue;

    $tagsHTML = build_tags::build_html($tag);

    echo "Writing tag html: $tagHTMLFilename\n";

    $fh = fopen($tagHTMLFilename, 'w') or die("can't write to file ".$tagHTMLFilename);
    fwrite($fh, implode("\n",$tagsHTML) );
    fclose($fh);

}


?>
