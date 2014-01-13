<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'library.php';

/**
 * Description of tag_class
 *
 * @author Person
 */

// build tags

class build_tags
{


    public static function tagIndex()
    {

        $db = new database();
        $sql = "SELECT substr(name,1,1) as letter,name FROM tag group by name";


        $all = $db->selectKeyValueArray($sql, 'letter', 'name');


        foreach ($all as $letter => $tags)
        {

            echo "<ul>\n";

            echo "<li>$letter</li>\n";

            echo "<li><ul>\n";
            foreach ($tags as $singleTag)
            {
                $singleTag = trim($singleTag);

                $tagLink = html::href(config::$webFindByTagPage.'?tag='.urlencode($singleTag), $singleTag);
                echo "<li>$tagLink</li>\n";
            }
            echo "</ul></li>\n";
            echo "</ul>\n";

            
        }
        
    }



    // build html for a single tag - for all images tagged with $tag_to_dsiplay
    public static function build_html($tag_to_dsiplay)
    {
        $result_html = array();

        $tag_filename = config::$realTagIndexSingle.$tag_to_dsiplay.'.tagIndex';
        $tagFiles = file($tag_filename);

        array_unique($tagFiles);

        foreach ($tagFiles as $key => $imageFilename)
        {
            $gi = new gallery_image($imageFilename, config::$thumbnailWidth, config::$thumbnailHeight);
            if ($gi->failed == FALSE) $result_html[] = $gi->html();
        }

        return $result_html;
    }



    public static function display_with_build($tag_to_dsiplay)
    {
        $html = self::build_html($tag_to_dsiplay);
        foreach ($array as $key => $value)  echo $value;
    }

    public static function tagFilenames()
    {
        $tagFiles =  file::folder_files(config::$realTagIndexFolder);
        
        $all_tags = array();
        foreach ($tagFiles as $tagKey => $tagValue)
        {

            $tagName = str_replace(config::$realTagIndexSingle, '', $tagKey);
            $tagName = str_replace(".tagIndex","",$tagName);
            
            $all_tags[$tagName] = $tagValue;
        }
        
        return $all_tags;
        
    }

    public static function tagCloud()
    {
        $db = new database();

        $sql = "select name,count(*) as 'tag_count' from tag group by name order by name";
        $tagFiles = $db->selectKeyValue($sql, 'name','tag_count');

        // get total count
        $total = 0;
        foreach ($tagFiles as $tagName => $tagCount)
        {
            $total = $total + $tagCount;
        }

        $htmlArr =  array();
        foreach ($tagFiles as $tagName => $tagCount)
        {

            if (strlen($tagName) <= 2 ) continue;
            $pcent = ($tagCount / $total) * 100;
            if ($pcent <= 0.1) continue;

            // if ($pcent < 5) $pcent = 5;

            $ic = "white";

            $fontSize =  $pcent * 30;

            if ($fontSize < 5) $fontSize = 5;

            if ($fontSize > 35 ) $ic = "green";


            if ($fontSize > 55 )
            { 
               $fontSize = 55;
               $ic = "red";
            }

            $sty=' style="color:'.$ic.'; font-size:'.$fontSize.'pt;  " '; 
            $tagLink = html::href(config::$webFindByTagPage.'?tag='.urlencode($tagName), $tagName,$sty);
            $tagHTML = html::div($tagLink , ' class="tag_cell" ');
            if (array_key_exists($tagName, $htmlArr) == FALSE) $htmlArr[$tagName] = array();
            $htmlArr[$tagName][$tagKey] = "$tagHTML\n";
        }

        $result = "";
        foreach ($htmlArr as $tag => $code)
        {
            $result .= implode('',$code)."\n";
        }

        //print_r($htmlArr);

        return $result;

    }


    public static function run($minimum_tag_count)
    {

        $keywords = self::build_keywords();

        $tags = array();
        // process filename - keywords into single files
        foreach ($keywords as $filename => $keywords)
        {
            foreach ($keywords as $singleKeyword)
            {
                if (array_key_exists($singleKeyword, $tags) == TRUE)
                {
                    $tags[$singleKeyword][] = $filename;
                }
                else
                {
                    echo "Added keyword: $singleKeyword\n";
                    $tags[$singleKeyword] = array();
                    $tags[$singleKeyword][] = $filename;
                }
            }
        }

        // get unique list filenames for each keyword.
        // for each set of filenames if the list of files is less than 2 then remove it.
        ksort($tags);

        // remoave tags with small number of entries
        $resultTags = array();
        foreach ($tags as $keyword => $fileList)
        {
            if (count($fileList) >= $minimum_tag_count)
            {
                $resultTags[$keyword] = $fileList;
            }
        }

        // copy tags where and alias exists


        $db = new database();

        // store tags
        foreach ($resultTags as $keyword => $fileList)
        {
            if ($keyword == "copy of") continue;

            // heere lookthru an array for unwanted tags

            $tagName = self::translateKeyword($keyword);

            echo "Store tag: $tagName \n";

            // write tage filenamne as constant with $fileList as an array
                                   //$srcArray, $table,              $constantValue,$constantColumn,$valueColumn
            $db->insertConstantValue($fileList, config::$dbTableTag, $tagName,      'name',         'filename');
            
        }

        unset($db);

    }

    public static function translateKeyword($keyword)
    {
        return $keyword;
    }

    public static function build_keywords()
    {
        $pathname_prefix = config::$realImagesFolder;

        $files = file::file_tree($pathname_prefix,config::$fs_folder_sep);
        $cleaned = self::cleanFiles($files);

        // after cleaing -  values become keyard arrays
        $keywords = array();
        foreach ($cleaned as $jpgKey => $jpgValue)
        {
            $singleKeywords = self::toKeywords($jpgValue,$pathname_prefix);
            $keywords[$jpgKey] = $singleKeywords;
        }

        return $keywords;

    }
    
    public static function toKeywords($incomingFilename,$pathname_prefix = "")
    {
        $delim = ' ';
        // convert $incomingFilename to keywoards
        $clean = trim($incomingFilename);

        $clean = str_replace($pathname_prefix, '', $clean);
        $clean = strtolower($clean);

        $clean = str_replace(' ' , '~', $clean); // savesa the spaces - we will get them back later

        $clean = str_replace('/' , $delim, $clean);
        $clean = str_replace('\\', $delim, $clean);
        $clean = str_replace('_' , $delim, $clean);
        $clean = str_replace('.' , $delim, $clean);
        $clean = str_replace('.' , $delim, $clean);
        $clean = str_replace('-' , $delim, $clean);
        $clean = str_replace(',' , $delim, $clean);
        $clean = str_replace('(' , $delim, $clean);
        $clean = str_replace(')' , $delim, $clean);
        $clean = str_replace('[' , $delim, $clean);
        $clean = str_replace(']' , $delim, $clean);
        $clean = str_replace('{' , $delim, $clean);
        $clean = str_replace('}' , $delim, $clean);
        $clean = str_replace('+' , $delim, $clean);
        $clean = str_replace('&' , $delim, $clean);
        $clean = str_replace('1' , $delim, $clean);
        $clean = str_replace('2' , $delim, $clean);
        $clean = str_replace('3' , $delim, $clean);
        $clean = str_replace('4' , $delim, $clean);
        $clean = str_replace('5' , $delim, $clean);
        $clean = str_replace('6' , $delim, $clean);
        $clean = str_replace('7' , $delim, $clean);
        $clean = str_replace('8' , $delim, $clean);
        $clean = str_replace('9' , $delim, $clean);
        $clean = str_replace('0' , $delim, $clean);
        $clean = str_replace('jpg' , $delim, $clean);
        $clean = str_replace('dsc' , $delim, $clean);
        $clean = str_replace('dscf' , $delim, $clean);
        $clean = trim($clean);
        $clean = trim($clean,'~');

        $cleanKeywords = array();
        foreach (explode($delim,$clean) as $value)
        {
            $value = trim($value);
            if ($value == "") continue;
            if (($value + 1) > 1 ) continue;

            $cleanedWord = trim(str_replace('~',' ',$value));

            $cleanKeywords[$value] = $cleanedWord;
        }



        return array_unique($cleanKeywords) ;

    }

    public static function cleanFiles($files)
    {

        $image_files = array();
        foreach ($files as $fnKey => $fnValue)
        {
            // extract any unwanted filenames - if not contains  thumbnail, meta
            $tnPos = strpos($fnKey,'thumbnail');
            if ($tnPos != FALSE) continue; // contains thumbnail

            $extension = strtolower(util::fromLastChar($fnKey, '.')); //

            if ($extension != "jpg") continue;

            //must only end in .jpg
            $image_files[$fnKey] = $fnValue;

        }

        return $image_files;

    }

}


?>
