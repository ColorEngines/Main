<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'image_detail_class.php';
include_once 'image_histogram_class.php';
include_once 'image_metadata_class.php';
include_once 'image_rgb_class.php';
include_once 'image_util_class.php';

/**
 * Description of image
 *
 * @author Person
 */
class image {
    //put your code here

    private $filepath;

    private $metadata;
    private $histogram;
    private $rgb;
    private $recorded = FALSE;  // if we have this data in DB then set to TRUE

    private $width;
    private $height;
    private $type;
    private $attr;

    public function __construct($pathToImage, $getExtendedData = FALSE)
    {
        
        $this->init($pathToImage, $getExtendedData);
        
    }

    public function  __destruct()
    {
        unset( $this->filepath);
        unset( $this->metadata);
        unset( $this->histogram);
        unset( $this->rgb);
        unset( $this->recorded);
        unset( $this->width);
        unset( $this->height);
        unset( $this->type);
        unset( $this->attr);
    }


    private function init($pathToImage, $getExtendedData = FALSE)
    {
        
        $pathToImage = trim($pathToImage);

        $this->filepath = new file($pathToImage);
        $this->tags = array();

        $this->metadata = array();

        if ($this->filepath->Exists())
        {
            
            list($this->width, $this->height, $this->type, $this->attr) = getimagesize($pathToImage);

            if ($getExtendedData == TRUE)
            {
                $this->metadata  = new metadata($this);  // add folder structure as tags insdide here
                //$this->rgb       = new image_rgb($this);
            }
        }

        

    }

    public function debug()
    {
        echo(config::$newline);
        echo("Name.........:".$this->Name().config::$newline);
        echo("pathname.....:".$this->Pathname().config::$newline);
        echo("folder.......:".$this->Folder().config::$newline);
        echo("extension....:".$this->Extension().config::$newline);
        echo("width........:".$this->width.config::$newline);
        echo("height.......:".$this->height.config::$newline);
        echo("type.........:".$this->type.config::$newline);
        echo("attr.........:".$this->attr.config::$newline);

        if (isset($this->metadata)) $this->metadata->debug();
        

    }


    public function store()
    {
        

        $this->metadata->store();
        $this->histogram->store();
        $this->rgb->store();
    }

    public function html($extras = "")
    {
        // because this is html must remove real section from path
        $web_pn = str_replace(config::$realImagesFolder, config::$webImagesFolder, $this->Pathname());

        $web_pn = str_replace(config::$fs_folder_sep, config::$web_folder_sep, $web_pn);



        $img = html::img($web_pn,$extras);
        return $img;
    }



    public function Pathname()
    {

        return $this->filepath->Pathname();
    }

    public function Name()
    {
        return $this->filepath->Name();
    }

    public function Folder()
    {
        return $this->filepath->Folder();
    }

    public function Extension()
    {
        return $this->filepath->Extension();
    }


    public function Width()      {return $this->width;}
    public function Height()     {return $this->height;}
    public function Type()       {return $this->type;}
    public function Attributes() {return $this->attr;}

    public function Metadata()
    {
        return $this->metadata;

    }

    public function Histogram()  {return $this->histogram;}
    public function RGB()        {return $this->rgb;}


    public function Summary()
    {
        return $this->metadata->Summary() ;
    }


    public static function forFolder($path)
    {
        $img = file::betterFirstWebImage($path);
        return $img;
    }


    public static function fromTag($tag)
    {
        $db = new database();
        $sql = 'SELECT * FROM tag where name = "'.$tag.'" limit 1';
        $result = $db->selectKeyValue($sql, 'name', 'filename');

        $values = array_values($result);

        return $values[0];
    }


}
?>
