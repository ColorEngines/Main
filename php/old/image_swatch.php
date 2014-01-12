<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of image_swatch
 *
 * @author Person
 */
class image_swatch
{
    //put your code here

    private $galleryImage;

    private $swatchImage;

    private $histogram;  // histogram for the swatch - should be very small - 4 to 9 rows

    private $swatchRGBs;
    private $matchRGBs;

    private $roundedColours;
    private $swatchColours;

    // given a gallery image object - get the swatch and all it's bits
    public function __construct($srcGalleryImage)
    {
        $this->galleryImage = $srcGalleryImage;

        $this->swatchImage = $this->galleryImage->Tiny(); //. this is the image we are going to use for the swatch

        $this->histogram = new histogram($this->swatchImage); // get the histogram of the tiny image

        $content = "";

        foreach ($this->histogramData() as $key => $value)
        {
            $cc = $value->ColorCode();
            $this->swatchRGBs[$cc] = $value;
        }

        $this->getSwatchColours();
        $this->getRoundedColours();


    }

    public function  __destruct()
    {
//        unset( $this->swatchImage);
//        unset( $this->histogram);
//        unset( $this->swatchRGBs);
//        unset( $this->matchRGBs);
//        unset( $this->roundedColours);
//        unset( $this->swatchColours);
    }

    // we are going to take each RGB value and then convert to decimal and round to closest 10
    // convert back to hex then place in $this->roundedColours array
    private function getRoundedColours()
    {

        if (!isset($this->swatchRGBs)) return;

        $this->roundedColours = array();
        foreach ($this->swatchRGBs as $colorKey => $colorValue)
        {
            $roundedRGB = rgb::roundHEX($colorKey);
            $this->roundedColours[$roundedRGB] = $roundedRGB;
        }
    }


    private function getSwatchColours()
    {

        if (!isset($this->swatchRGBs)) return;

        $this->swatchColours = array();
        foreach ($this->swatchRGBs as $colorKey => $colorValue)
        {
            $this->swatchColours[$colorKey] = $colorKey;
        }
    }


    public function SwatchColours()
    {
        return $this->swatchColours;
    }

    public function RoundedColours()
    {
        return $this->roundedColours;
    }


    public function swatchRGBs()
    {
        return $this->swatchRGBs;
    }

    public function matchRGBs()
    {
        return $this->matchRGBs;
    }




    private function histogramData()
    {
        return $this->histogram->Histogram();
    }


    public function html($extras = "")
    {

        $content = "";

        foreach ($this->histogramData() as $key => $value)
        {
            $class = html::element_class("swatch_cell");
            $style = html::style('background-color:'.$value->ColorCode());
            $cell = html::div('', $class.$style);
            $content .= $cell;

        }


        return html::div($content, ' class="swatch_box" ');
        
    }

    // store swatch colour numbers in a file in a mainfolder 
    // this is so we can have an index of filenames to swatch colours
    // for one file name store the X number of color values
    public function storeInIndex($restore = FALSE)
    {
        $db = new database();

        if ($restore == TRUE ) $db->deleteKeyValue(config::$dbTableColor, 'filename', $this->galleryImage->imagePath());

        $rgbCount = $db->rowCount(config::$dbTableColor, 'filename', $this->galleryImage->imagePath());
        if ($rgbCount > 0 ) return;

        $this->storeLongIndex($db);
        //$this->storeRoundedIndex($db);

        unset($db);
    }


    // save colours down - ie X rows for each image - one color per row
    private function storeLongIndex($db)
    {

        if (!isset($this->swatchRGBs)) return;
        $db = new database();

        ksort($this->swatchRGBs);

        $dbArray = array();
        foreach ($this->swatchRGBs as $colorKey => $colorValue)
            $dbArray[$colorKey] = $this->galleryImage->imagePath();

        $db->insertColorRGB($dbArray);

    }


}
?>
