<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'library.php';

/**
 * Description of file_class
 *
 * @author Person
 */
class file {

    private $filename;
    private $name;
    private $pathname;
    private $folder;
    private $size;
    private $exists = FALSE;
    private $extension;


    public function __construct($srcPathname)
    {
        $this->init($srcPathname);
    }

    private function init($srcPathname)
    {

        $this->pathname = $srcPathname;

        $this->exists = file::reallyExists($srcPathname);
        $this->folder = util::toLastSlash($srcPathname);

        //echo "this->folder = ".$this->folder." ---\n<br>";


        if ($this->exists)
        {
            
            $this->filename = basename($srcPathname);
            $this->extension = file::getFileExtension($srcPathname);
            $this->name = str_replace(".".$this->extension,"",$this->filename );
            $this->folder = util::toLastSlash($srcPathname);
            $this->size = filesize($srcPathname);

        }

    }

    public function debug()
    {

        $result = "";
        $result .= config::$newline."filename....:".$this->Filename();
        $result .= config::$newline."pathname....:".$this->Pathname();
        $result .= config::$newline."folder....:".$this->Folder();
        $result .= config::$newline."size....:".$this->Size();
        $result .= config::$newline."exists....:".$this->Exists();
        $result .= config::$newline."extension....:".$this->Extension();

        echo $result;

    }

    public function Pathname() {
        return $this->pathname;
    }
    public function Name() {
        return $this->name;
    }
    public function Filename() {
        return $this->filename;
    }
    public function Folder() {
        return $this->folder;
    }
    public function Size() {
        return $this->size;
    }
    public function Exists() {
        return $this->exists;
    }
    public function Extension() {
        return $this->extension;
    }

    public function setPathname($value) {
        init($value);
        return true;
    }

    // array of all folders


    public static function reallyDelete($Filename)
    {
        if (self::reallyExists($Filename) == FALSE) return TRUE; // it did not exists therefor delete is TRUE
        return unlink($Filename);
    }


    public static function reallyExists($Filename)
    {
        if (file_exists($Filename))
        {
            return TRUE;
        }

        $ss = str_replace('/', '\\', $Filename);

        if (file_exists($ss) == TRUE)
        {
            return TRUE;
        }


        return FALSE;
    }


    public static function getFileExtension($srcPathname, $ext_sep = "")
    {

        if ($ext_sep == "") $ext_sep = config::$fs_extension_sep;

        // find last extension sep - usually dot  .
        $pos = strrpos($srcPathname, $ext_sep);
        if ($pos === false)  return ""; // no sep - so no extension

        // very basic - get basename
        $result = substr($srcPathname,$pos + 1);

        return $result;
    }

    
    public static function Array2File($src,$dest)
    {
        
        $fh = fopen($dest, 'w') or die("can't write metadata to ($dest) ");
        
        foreach ($src as $key => $value)
        {
            $stringData = "$key,$value\n";
            fwrite($fh, $stringData);    
        }
        
        fclose($fh);
    }

        public static function ArrayValues2File($src,$dest)
    {

        $fh = fopen($dest, 'w') or die("can't write metadata to ($dest) ");

        foreach ($src as $value)
        {
            $stringData = "$value\n";
            fwrite($fh, $stringData);
        }

        fclose($fh);
    }


    public static function File2Array($srcFilename)
    {
        return file($srcFilename);

    }


    public static function file_sizes($fileArr, $divisor = 1)
    {
        
        $result = array();
        foreach ($fileArr as $key => $value)
        {
            $result[$key] = filesize($value) / $divisor;
        }
        
        return $result;
    }


    public static function betterFirstWebImage($path)
    {
        $img = self::firstWebImage($path);
        if ($img != "") return $img;

        $folders = self::folder_folders($path);

        // go into each folder and try to get an image
        // but the folder can't be called thumbnail.
        foreach ($folders as $Fkey => $Fvalue)
        {
            $fi = self::firstWebImage($Fvalue);
            if ($fi != "") return $fi;
        }

        return "";

    }

    // we can turn this into a sql query at some stage
    public static function firstWebImage($path)
    {
        $files = self::folder_files($path);

        foreach ($files as $key => $value)
        {
            $ext = strtolower(self::getFileExtension($value, config::$fs_extension_sep));
            if ($ext == "jpg") 
            {
                $tnpath = image_util::ThumbnailPathName($value, config::$thumbnailWidth, config::$thumbnailHeight);
                return $tnpath;
            }
        }

        // if we get here we can't find an image
        // - then try hadrer get the first folder by tree

        return "";

    }

    public static function folder_files($path)
    {
        $path = util::trim_end($path, config::$fs_folder_sep);
        $path = $path.config::$fs_folder_sep;

        $result = array();

        $dir_handle = @opendir($path) or die("Unable to open $path");
        while (false !== ($file = readdir($dir_handle))) {
            $dir = $path.$path_sep.$file;
            if(is_dir($dir) && $file != '.' && $file !='..' )
            {
            }
            elseif ($file != '.' && $file !='..')
            {
                $result[$dir] = $dir;
            }
        }

        //closing the directory
        closedir($dir_handle);

        

        return $result;
    }


    public static function folder_folders($path)
    {
        $path = util::trim_end($path, config::$fs_folder_sep);
        $path = $path.config::$fs_folder_sep;

        $result = array();

        $dir_handle = @opendir($path) or die("Unable to open $path");
        while (false !== ($file = readdir($dir_handle))) {
            $dir = $path.$path_sep.$file;
            if(is_dir($dir) && $file != '.' && $file !='..' )
            {
                $result[$dir] = $dir;
            }
            elseif ($file != '.' && $file !='..')
            {
            }
        }

        //closing the directory
        closedir($dir_handle);



        return $result;
    }



    public static function file_tree($startPath,$path_sep)
    {
        $startPath = util::trim_end($startPath,$path_sep);
        $result = array();
        self::list_dir($startPath,$result,$path_sep);
        return $result;
    }



    public static function list_dir($path, &$result,$path_sep)
    {
        $dir_handle = @opendir($path) or die("Unable to open $path");
        while (false !== ($file = readdir($dir_handle))) {
            $dir = $path.$path_sep.$file;
            if(is_dir($dir) && $file != '.' && $file !='..' )
            {
                $handle = @opendir($dir) or die("unable to open file $file");
                self::list_dir($dir, &$result,$path_sep);
            } 
            elseif ($file != '.' && $file !='..')
            {
                $result[$path.$path_sep.$file] = $path.$path_sep.$file;
            }
        }

        //closing the directory
        closedir($dir_handle);

    }






}
?>
