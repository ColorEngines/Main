<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mosaic_class
 *
 * @author Person
 */
class mosaic_class
{

    private $webpathName;
    private $webpathMosaic;


    public static function fromURL()
    {
        $passed = $_GET['wfn'];
        return $passed;
    }

    public function __construct($wpn = "")
    {
        if ($wpn == "") $wpn = self::fromURL();
        $this->webpathName = $wpn;
        $this->webpathMosaic = $this->process();
    }

    public function process() 
    {

        ini_set('memory_limit', "1024M");

        $tileWidth  = 50;
        $tileHeight = 50;

        $srcFilename = $this->webpathName;

        $path = str_replace(config::$webImagesFolder, config::$realImagesFolder, $srcFilename);
        $path = str_replace(config::$web_folder_sep,config::$fs_folder_sep ,$path);

        $f = new file($path);
        $mosaicFilename = config::$realMosaicFolder.$f->Name()."_mosaic".config::$fs_extension_sep.$f->Extension();

        // create empty image that is width * tileWidth;

        // echo "path = $path";

        $histRaw = histogram::raw($path);
        $histRGB = self::histIndexes2rgbindexs($histRaw);

        // for this  $histRGB - find mathing images for each colour / rgb

        $color2Image = self::findMatchingFilenames($histRGB, $bandwidth = 5, $excludeExtremes = 2);

        $tileImages = array();

        // just get one image for each color
        foreach ($color2Image as $rgb => $filenames)
        {
            if (is_array($filenames) == FALSE) continue;

            $tileRealName = util::first_element($filenames);
            if ($tileRealName == "") continue;

            if (file_exists($tileRealName) == FALSE) continue;

            $tnpn = image_util::ThumbnailPathName($tileRealName);

            //echo "$rgb = $tnpn\n<br>";

            $tileImages[$rgb] = imagecreatefromjpeg( $tnpn );
            
        }

        

        // print_r($color2Image);

        // image_util::ThumbnailPathName($srcWebpath, $width, $height)

        $original = imagecreatefromjpeg($path) or die("Error Opening original ".$path);

        // get dimension of image
        list($srcWidth, $srcHeight, $srcType, $srcAttr) = getimagesize($path);

        
        // Resample the image.
        $toWidth = $srcWidth * $tileWidth;
        $toHeight = $srcHeight * $tileHeight;
        $quality = 100;

        $mosaicImg = imagecreatetruecolor($toWidth, $toHeight) or die("Cant create temp image");

        // here is where we replace each part of  $mosaicImg

        $format = '%02x%02x%02x';


        for ($x = 0; $x < $srcWidth; $x++)
	{
            for ($y = 0; $y < $srcHeight; $y++)
	    {
                $rgb = ImageColorAt($original, $x, $y);   // get the rgb value for current pixel

                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                $RGBhex = sprintf($format,$r,$g,$b);

                if (array_key_exists($RGBhex, $tileImages) == FALSE ) continue;
                $mergeResult = imagecopyresampled(  $mosaicImg, $tileImages[$RGBhex], $x * $tileWidth, $y * $tileHeight, 1, 1, 50,50,99,99);
                
	    }
        }



        // Save the image.
        imagejpeg($mosaicImg, $mosaicFilename, $quality) or die("Cant save image");

        // Clean up.
        imagedestroy($original);
        imagedestroy($mosaicImg);

        if (!file::reallyExists($mosaicFilename)) return FALSE; // check to see if we wrote it ok


        foreach ($tileImages as $key => $value)
        {
            imagedestroy( $value );
        }

        return $mosaicFilename; // - image filename  


    }


    private function findMatchingFilenames($rgbHist, $bandwidth = 3, $excludeExtremes = 10)
    {

        $db = new database();


        // two thoughts create more colors for IN clause
        // or update DB so we can do ranges ?
        // have to have decimal values in DB
        // make ranges - very much easier.

        $allMatches = array();
        foreach ($rgbHist as $singleRGB => $countOf)
        {

            if (array_key_exists($singleRGB, $allMatches)) continue; // we already have this colour

            $middleDecimalRGB[$singleRGB] = FindByColor::toDeciaml($singleRGB);

            $mR = $middleDecimalRGB[$singleRGB]['R'];
            $mG = $middleDecimalRGB[$singleRGB]['G'];
            $mB = $middleDecimalRGB[$singleRGB]['B'];

            // we need to ignore values close to each end of the range 0 - 255
            if ( ($mR < $excludeExtremes) && ($mG < $excludeExtremes) &&  ($mB < $excludeExtremes)  ) continue;
            if ( ($mR > 255-$excludeExtremes ) && ($mG > 255-$excludeExtremes) &&  ($mB < 255-$excludeExtremes)  ) continue;
            if ( ($mR == $mG && $mG == $mB ) ) continue; // all same - it's gray - eberything has gray

            $sql=<<<SQL
SELECT c.`filename`
FROM color c
where  (Rdec >= ($mR-$bandwidth))
and    (Rdec <= ($mR+$bandwidth))
and    (Gdec >= ($mG-$bandwidth))
and    (Gdec <= ($mG+$bandwidth))
and    (Bdec >= ($mB-$bandwidth))
and    (Bdec <= ($mB+$bandwidth))
SQL;
            //echo "sql  = $sql<br>\n";
            //echo "$mR $mG $mB<br>\n";

            $matchedRows = $db->selectKeyValue($sql, 'filename', 'filename',FALSE);
            $allMatches[$singleRGB] = $matchedRows;

        }

        $db->disconnect();
        unset($db);

        return $allMatches;
    }



    public function source_html()
    {
        $img = html::img($this->webpathName);
        echo $img;

    }

    public function mosaic_html()
    {
        $webPath = str_replace(config::$realMosaicFolder,config::$webMosaicFolder,$this->webpathMosaic);

        $img = html::img($webPath,' width="500" height="500"');
        echo $img;


    }


    private function histIndexes2rgbindexs($rawArr)
    {

        // converting less to RGB
        $format = '%02x%02x%02x';

        $result = array();
        foreach ($rawArr as $rgb => $countOf)
        {
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b =  $rgb & 0xFF;

            $RGBhex = sprintf($format,$r,$g,$b);
            $result[$RGBhex] = $countOf;

        }
        return $result;

    }



}
?>