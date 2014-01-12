<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'library.php';
include_once 'image_swatch.php';

/**
 * Description of gallery_image_class
 *
 * @author Person
 */
class gallery_image {
    //put your code here

    // store link to image and thumbnail
    private $image;
    private $thumb;
    private $medium;  // medium - to display in detai, window / popup
    private $tiny;  // this is an image that will be very tiny - to form colours swatch

    private $swatch;

    private $metadata;
    //private $histogram;
    //private $rgb;

    public $matchedColor;

    public $failed = FALSE;


    public function __construct($filepath,$tnWidth = 0, $tnHeight = 0, $restore = FALSE) {

        if ($tnWidth == 0 )  $tnWidth = config::$thumbnailWidth;
        if ($tnHeight == 0 ) $tnWeight = config::$thumbnailHeight;

        // echo "gallery_image ... $filepath\n";


        $filepath = trim($filepath);

        $this->failed = FALSE;

        if (file_exists($filepath) == FALSE)
        {
            $this->failed = TRUE;
            return;
        }

        if (filesize($filepath) == 0 )
        {
            $this->failed = TRUE;
            return;
        }

      
        // from $filepath create image and thumb - which is really just two images.
        $this->image = new image($filepath, TRUE);

        // echo "after new image ... $filepath\n";


        $tinyImage = image_util::ThumbnailCreate($filepath, config::$tinyWidth, config::$tinyHeight);
        $this->tiny = new image($tinyImage,FALSE);
        $this->swatch = new image_swatch($this); // relies on tiny image
        $this->swatch->storeInIndex($restore);
        // want to add swatch to metadata
        $this->metadata = $this->image->Metadata();
        // add swatch data to metadata;

        if (isset($this->swatchRGBs))
        {
            $swatchCount = 0;
            foreach ($this->swatchRGBs() as $key => $value)
            {
                $this->metadata->addTag('swatch_'.$swatchCount,$key);
                $swatchCount++;
            }
            $this->metadata->store($restore); // store swatch data as well
        }


        $tnwp = image_util::ThumbnailCreate($filepath, $tnWidth, $tnHeight);
        $this->thumb = new image($tnwp,FALSE);

        // need to calc medium image as a percent of full image
        $mediumWidth  = config::$mediumWidth;
        $mediumHeight = config::$mediumHeight;


        $mediumImage = image_util::ThumbnailCreate($filepath, config::$mediumWidth, config::$mediumHeight);

        $this->medium = new image($mediumImage,FALSE);

    }

    public function processHistogram($resolution = 5)
    {
        $this->histogram = new histogram($this->image, $resolution);
        $this->histogram->store();

    }



    public function __destruct()
    {

        unset($this->image);
        unset($this->thumb);
        unset($this->medium);
        unset($this->tiny);
        //unset($this->swatch);
        //unset($this->metadata);

    }


    public function swatchRGBs()
    {
        return $this->swatch->swatchRGBs();
    }

    public function SwatchColours()
    {
        return $this->swatch->SwatchColours();
    }
    public function RoundedColours()
    {
        return $this->swatch->RoundedColours();
    }


    public function Width()      {return $this->image->Width;}
    public function Height()     {return $this->image->Weight;}


    // return HTML for thumbnail
    public function html($extras = "")
    {


        $result = "";

        //$rc = trim( join(',',$this->RoundedColours()), ',');
        $sc = trim( join(',',$this->SwatchColours() ), ',');

        $colors = 'colors='.urlencode(trim($sc,','));

        $matchImage = str_replace(config::$realImagesFolder, '', $this->imagePath());

        $matchImageQS = 'match='.urlencode($matchImage);
        
        $findHref = config::$webFindByColorPage.'?'.$colors.'&'.$matchImageQS;
        
        $thumbIMG  = $this->thumb->html('class="gallery_thumb_img"');

	$content = '<div class="thumbnail_text">'.str_replace("tn_300x300_","",$this->thumb->Name()).'</div>';

        $result .= '<div class="gallery_thumb_container" '.$extras.'>';
        $result .= $content;
        $result .= '<a class="gallery_thumb_href" href="'. $findHref .'" >'.$thumbIMG.'</a>';
        $result .= $findButton;
        $result .= "</div>";

        return $result ;
    }



    public function tinyPath()   
    {
        if ($this->failed == TRUE) return FALSE;
        return $this->tiny->Pathname();
    }

    public function thumbPath()  
    {
        if ($this->failed == TRUE) return FALSE;
        return $this->thumb->Pathname();
    }

    public function mediumPath()
    {
        if ($this->failed == TRUE) return FALSE;
        return $this->medium->Pathname();
    }

    public function imagePath()
    {
        if ($this->failed == TRUE) return FALSE;
        return $this->image->Pathname();
    }

    public function tinyWebPath()   
    {
        if ($this->failed == TRUE) return FALSE;
        $result = str_replace(config::$realImagesFolder, config::$webImagesFolder, $this->tiny->Pathname());
        $result = str_replace(config::$fs_folder_sep,config::$web_folder_sep,$result);
        return $result;
    }

    public function thumbWebPath()  
    {
        if ($this->failed == TRUE) return FALSE;
        $result = str_replace(config::$realImagesFolder, config::$webImagesFolder, $this->thumb->Pathname());
        $result = str_replace(config::$fs_folder_sep,config::$web_folder_sep,$result);
        return $result;
    }

    public function mediumWebPath()
    {
        if ($this->failed == TRUE) return FALSE;
        $result = str_replace(config::$realImagesFolder, config::$webImagesFolder, $this->medium->Pathname());
        $result = str_replace(config::$fs_folder_sep,config::$web_folder_sep,$result);
        return $result;
    }

    public function imageWebPath()
    {
        if ($this->failed == TRUE) return FALSE;
        $result = str_replace(config::$realImagesFolder, config::$webImagesFolder, $this->image->Pathname());
        $result = str_replace(config::$fs_folder_sep,config::$web_folder_sep,$result);
        return $result;
    }


    public function Tiny()   
    {
        if ($this->failed == TRUE) return FALSE;
        return $this->tiny;
    }

    public function Thumb()  
    {
        if ($this->failed == TRUE) return FALSE;
        return $this->thumb;
    }

    public function Medium() 
    {
        if ($this->failed == TRUE) return FALSE;
        return $this->medium;
    }

    public function Image()
    {
        if ($this->failed == TRUE) return FALSE;
        return $this->image;
    }   // this might not want to be public

    public function Metadata()   
    {
        if ($this->failed == TRUE) return FALSE;
        return $this->metadata;
    }

    public function Histogram()  
    {
        if ($this->failed == TRUE) return FALSE;
        return $this->image->Histogram;
    }

    public function RGB()
    {
        if ($this->failed == TRUE) return FALSE;
        return $this->image->RGB;
    }


    public function Summary()
    {
        if ($this->failed == TRUE) return FALSE;

        $result = $this->image->Summary();
        
        return $result;
    }




}
?>
