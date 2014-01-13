<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'library.php';

/**
 * Description of gallery_class
 *
 * @author Person
 */
class gallery {
    //put your code here

    private $galleryRoot;

    private $galleryFiles;
    private $galleryObjects;

    public static function webGalleryPath()
    {
        $wgp = trim($_GET["gp"],config::$fs_folder_sep);
        return $wgp;
    }

    public static function findColors()
    {
        return $_GET["colors"];
    }



    public function __construct($galleryStart = "")
    {
        ini_set("max_execution_time",6000);

        $this->galleryRoot = config::$realImagesFolder.$galleryStart;
        $this->galleryRoot = str_replace(config::$fs_folder_sep.config::$fs_folder_sep, config::$fs_folder_sep, $this->galleryRoot);
        $this->galleryFiles = folder::entries($this->galleryRoot);
        $this->galleryFiles = $this->removeExtraFilesAndFolders($this->galleryFiles);
        $this->galleryObjects = $this->getGalleryObjects();

    }

    public static function FromTag($tag)
    {

        $db = new database();
        $sql = "SELECT distinct filename FROM tag t where name = '$tag' order by filename";

        $tagged = $db->selectKeyValue($sql, 'filename', 'filename');

        $go = array();
        foreach ($tagged as $key => $value)
        {
            $sgo = new gallery_image($value,config::$thumbnailWidth, config::$thumbnailHeight);
            if ($sgo->failed == FALSE)
            {
                $go[$key] = new gallery_image($value,config::$thumbnailWidth, config::$thumbnailHeight);
            }
        }

        return $go;
    }


    private function removeExtraFilesAndFolders($galleryFiles)
    {
        // need to remove  image_util::$THUMBNAIL_FOLDER from  $this->galleryFiles
        // need to remove  config::$thumbsDB from  $this->galleryFiles

        $result = array();
        
        foreach ($galleryFiles as $key => $value) 
        {
            if ($key == image_util::$THUMBNAIL_FOLDER) continue;
            if ($key == config::$thumbsDB) continue;
            
            $result[$key] = $value;
        }

        return $result;
        

    }

    private function getGalleryObjects()
    {
        // loop thru the $imagesRoot and create array of image objects and folder objects

        ksort($this->galleryFiles);

        $go = array();

        foreach ($this->galleryFiles as $key => $value)
        {
            if (is_dir($value)== TRUE)
            {
                if ($this->containsExcluded($value) == FALSE )
                {
                    $go[$key] = new gallery_folder($value);
                }
            }
        }


        foreach ($this->galleryFiles as $key => $value)
        {
            
            if (!is_dir($value)== TRUE)
            {
                if ($this->containsExcluded($value) == FALSE )
                {
                    if ($this->allowedImageType($value))
                    {
                        $sgo = new gallery_image($value,config::$thumbnailWidth, config::$thumbnailHeight,TRUE);
                        if ($sgo->failed == FALSE)
                        {
                            $go[$key] = $sgo;
                        }
                    }
                }
            }

                
        }

        

        return $go;

    }

    private function allowedImageType($src)
    {
        $lc = strtolower($src);
        $pos = stripos($src, config::$imageExtensionAllowed);
        if ($pos === FALSE) return FALSE;

        return TRUE;
        
    }

    private function containsExcluded($src)
    {

        if (stripos($src, image_util::$THUMBNAIL_FOLDER) != FALSE ) 
        {
            return TRUE;
        }


        if (stripos($src, "metadata") != FALSE )
        {
            return TRUE;
        }


        return FALSE;
        
    }


    public function display()
    {

        echo $this->breadCrumbHTML();

        foreach ($this->galleryObjects as $key => $value)
        {
            echo $value->html("");
        }


    }


    private function breadCrumbHTML()
    {

        $pathForWeb = str_replace(config::$realImagesFolder,'', $this->galleryRoot);
        $pathForWeb = config::$fs_folder_sep.$pathForWeb;

        $pathForWeb = str_replace(config::$fs_folder_sep.config::$fs_folder_sep, config::$fs_folder_sep, $pathForWeb);

        $crumbArray = $this->getBreadCrumbArray($pathForWeb, config::$fs_folder_sep);

        $bcDisplay = $pathForWeb;


        // replace in $bcDisplay  folder name test with links         
        foreach ($crumbArray as $crumbFolder => $crumbFullFolder) 
        {
            $crumbHref = config::$webRootPage."?gp=".urlencode($crumbFullFolder);
            $crumbLink = html::href( $crumbHref, html::b(str_replace(config::$fs_folder_sep,'&nbsp;'.config::$fs_folder_sep.'&nbsp;',$crumbFolder))  );

            $bcDisplay = str_replace($crumbFolder,$crumbLink,$bcDisplay);
        }

        $sh = gallery::galleryHomeLink().html::div($bcDisplay,'id = "gallery_breadcrumb"');
        
        return html::div($sh, ' class="sub_header" ') ;


    }

    public static function galleryHomeLink()
    {
        $hl = html::href(config::$webRootPage, html::b(config::$gallerySub))."&nbsp;::&nbsp;";
        return html::div($hl, 'class="home_link"');
    }


    // convert $pathForWeb into an array
    // each entry is from startt to each $delim
    // plants/coloured/pink/ becomes
    // plants               = plants
    // plants/coloured      = plants/coloured
    // plants/coloured/pink = plants/coloured/pink
    //
    private function getBreadCrumbArray($pathForWeb, $delim)
    {

        $pathArray = explode($delim,$pathForWeb);

        $result = array();
        foreach ($pathArray as $ss)
        {
            $find = config::$fs_folder_sep.$ss;
            $pos = strpos($pathForWeb, $find);

            $fullPath = substr($pathForWeb,1,$pos + strlen($find) -1);

            $result[$find] = $fullPath;

        }

        unset($result[config::$fs_folder_sep]);

        return $result;

    }


}
?>
