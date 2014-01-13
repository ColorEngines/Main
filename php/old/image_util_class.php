<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of image_util_class
 *
 * @author Person
 */
class image_util {
    //put your code here

    public static $THUMBNAIL_FOLDER = "thumbnails";
    public static $THUMBNAIL_PREFIX = "tn_";

    public static $THUMBNAIL_STRING_COMPARE = "thumbnails\tn_";  // if a pathname has this in it then it must be a thumb nail

    /*
     * $srcWebpath : web server path to image - must exist
     * returns: path to thumbnail (this may or may not exist)
     */


    public static function ThumbnailPathName($srcWebpath,$width = 0 ,$height = 0)
    {
        if ($width == 0 ) $width = config::$thumbnailWidth;
        if ($height == 0 ) $height = config::$thumbnailHeight;

        $f = new file($srcWebpath);

        $result  = $f->Folder().config::$fs_folder_sep;                   // the folder the image is in
        $result .= image_util::$THUMBNAIL_FOLDER.config::$fs_folder_sep;  // one level down to thumb nail folder
        $result .= image_util::$THUMBNAIL_PREFIX.$width.'x'.$height.'_';  // thumb pre fix plus width and height
        $result .= $f->Name().config::$fs_extension_sep.$f->Extension();  // name + extension

        // /images/animals/thumbnails/tn_elephant.jpg

        return $result;

    }

    public static function ThumbnailFolder($srcWebpath)
    {

        $f = new file($srcWebpath);

        $tnf = $f->Folder();

        $result  = $tnf.config::$fs_folder_sep;                   // the folder the image is in
        $result .= image_util::$THUMBNAIL_FOLDER;

        return $result;

    }


    /*
     * Create thumbnail from a webpath
     * return: Webpath to thumbnail.
     * failed: FALSE
     */
    public static function ThumbnailCreate($srcpath,$toWidth,$toHeight)
    {
        if (file_exists($srcpath) == FALSE) return FALSE;

        ini_set("memory_limit" , "1024M");

        $tnpn = image_util::ThumbnailPathName($srcpath,$toWidth,$toHeight);
        $thumbExists = image_util::ThumbnailExists($srcpath,$toWidth,$toHeight);  // return file if exists or FALSE if it does not exist

        if ($thumbExists == TRUE) return $tnpn; // if the have a thumb nail then return path to it.

        // we don't have one - we have to create it

        // create folder under it's folder
        $tnFolder = image_util::ThumbnailFolder($srcpath);
        if (folder::reallyExists($tnFolder) == FALSE) mkdir($tnFolder,0777);

        // New way is to use ImageMagick   convert -sample $toWidth $tnpn
        $convert_command = "convert \"".$srcpath."\" -sample '$toWidthx$toHeight' \"$tnpn\"";
        exec($convert_command);

        if (!file::reallyExists($tnpn)) return FALSE; // check to see if we wrote it ok

        return $tnpn; // - image filename  // all ok return "file to "webpath" to it
    }


    public static function ThumbnailExists($srcWebpath,$width,$height)
    {
        $tfn = image_util::ThumbnailPathName($srcWebpath,$width,$height);
        $f = new file($tfn);
        return $f->Exists();
    }

    /* 
     * Does the srcWebpath contain the thumbnail string compare
     */
    public static function isThumbnail($srcWebpath)
    {
        $pos = strpos($srcWebpath, image_util::$THUMBNAIL_STRING_COMPARE);

        if ($pos === FALSE) return FALSE; // not a thumb nail
        return TRUE; // is a thumbnail
    }



    public static function real2web($srcWebpath)
    {
        $result = str_replace(config::$realImagesFolder, config::$webImagesFolder, $srcWebpath);
        $result = str_replace(config::$fs_folder_sep, config::$web_folder_sep, $result );
        return $result;
    }



}
?>
