<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of utils_class
 *
 * @author Person
 */
class util {
    //put your code here


    /**
 * Explode any single-dimensional array into a full blown tree structure,
 * based on the delimiters found in it's keys.
 *
 * @author    Kevin van Zonneveld <kevin@vanzonneveld.net>
 * @author    Lachlan Donald
 * @author    Takkie
 * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
 * @version   SVN: Release: $Id: explodeTree.inc.php 89 2008-09-05 20:52:48Z kevin $
 * @link      http://kevin.vanzonneveld.net/
 *
 * @param array   $array
 * @param string  $delimiter
 * @param boolean $baseval
 *
 * @return array
 */

    public static function debug($str)
    {
        echo config::$newline.$str.config::$newline;
    }

    public static function explodeTree($array, $delimiter = '_', $baseval = false)
    {
        if(!is_array($array)) return false;
        $splitRE   = '/' . preg_quote($delimiter, '/') . '/';
        $returnArr = array();
        foreach ($array as $key => $val) {
            // Get parent parts and the current leaf
            $parts    = preg_split($splitRE, $key, -1, PREG_SPLIT_NO_EMPTY);
            $leafPart = array_pop($parts);

            // Build parent structure
            // Might be slow for really deep and large structures
            $parentArr = &$returnArr;
            foreach ($parts as $part) {
                if (!isset($parentArr[$part])) {
                    $parentArr[$part] = array();
                } elseif (!is_array($parentArr[$part])) {
                    if ($baseval) {
                        $parentArr[$part] = array('__base_val' => $parentArr[$part]);
                    } else {
                        $parentArr[$part] = array();
                    }
                }
                $parentArr = &$parentArr[$part];
            }

            // Add the final part to the structure
            if (empty($parentArr[$leafPart])) {
                $parentArr[$leafPart] = $val;
            } elseif ($baseval && is_array($parentArr[$leafPart])) {
                $parentArr[$leafPart]['__base_val'] = $val;
            }
        }
        return $returnArr;
    }


    public static function replaceInKey($srcArray,$find_str,$replace_str)
    {
        $result = array();

        foreach ($srcArray as $index => $filename)
        {

            $newKey = str_replace($find_str, $replace_str, $index);
            $result[$newKey] = $filename;
        }

        return $result;
    }


    public static function toLastSlash($src, $slashType = "")
    {
        if ($slashType == "") $slashType = config::$fs_folder_sep;
        return self::toLastChar($src, $slashType);
    }


    public static function toLastChar($src, $charType = "")
    {
        
        $pos = strrpos($src, $charType);
        if ($pos === false) return $src;
        $result = substr($src,0,$pos);
        return $result;
    }

    public static function fromLastSlash($src, $slashType = "")
    {
        if ($slashType == "") $slashType = config::$fs_folder_sep;
        return self::fromLastChar($src, $slashType);
    }


    public static function fromLastChar($src, $charType = "")
    {
        
        $pos = strrpos($src, $charType);
        if ($pos === false) return $src;

        return substr($src,$pos + 1);
    }

    public static function contains($in, $find)
    {
        $pos = strpos( $find, $in);
        if ($pos === false) return FALSE;
        return TRUE;
    }


    public static function round($fileArr, $places = 2)
    {
        $result = array();
        foreach ($fileArr as $key => $value)
        {
            $result[$key] = round($value,$places);
        }

        return $result;
    }


    public static function normalise($fileArr, $min, $max)
    {
        $result = array();
        foreach ($fileArr as $key => $value)
        {
            $result[$key] = round($value,$places);
        }

        return $result;
    }


    public static function arrayElements($srcArr, $start = 0, $end = -1)
    {
        if ($end == -1) $end = count($srcArr);

        $elemCount = 0;

        $result = array();
        foreach ($srcArr as $value)
        {
            if ( ($elemCount >=  $start) && ($elemCount < $end ) )
            {
               $result[$elemCount] =  $value;
            }

            $elemCount++;
        }

        return $result;
    }

    public static function trim_end($toTrim,$trimOff)
    {
        $lastChar = substr($toTrim,count($toTrim) - 2,1);
        if ($lastChar != $trimOff) return $toTrim;
        return substr($toTrim,0,count($toTrim) - 2);
    }

    public static function last_char($str)
    {
        $lastChar = substr($str,count($toTrim) - 1,1);
        return $lastChar;
    }

    public static function last_element($array)
    {
        $lastIndex = count($array);

        $vals = array_values($array);

        return $vals[$lastIndex - 1];
    }

    public static function first_element($array)
    {
        $vals = array_values($array);

        return $vals[0];
    }



}
?>

