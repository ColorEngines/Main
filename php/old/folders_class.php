<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'library.php';


/**
 * Description of folders
 *
 * @author Person
 */
class folder {


    public static function entries($folder)
    {
        $folder = util::trim_end($folder, config::$fs_folder_sep);

        $handle = opendir("$folder");
        $retval = array();
        while (false !== ($file = readdir($handle))) {
            if (($file <> ".") && ($file <> "..")) {
                $retval[$file] = $folder.config::$fs_folder_sep.$file;
            }
        }
        closedir($handle);
        return $retval;
    }


    public static function reallyExists($webFilename)
    {
        if (file_exists($webFilename)) return TRUE;

        if (file_exists(realpath($webFilename))) return TRUE;

        return FALSE;
    }


    public static function create($folderName)
    {
        return mkdir($folderName);
    }


}

?>
