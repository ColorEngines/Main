<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of image_historgram_class
 *
 * @author Person
 */
class histogram {
    //put your code here

    private $image;
    private $histogram;

    private $bounds_left;
    private $bounds_right;
    private $bounds_upper;
    private $bounds_lower;

    public function __construct($srcImage, $step = 1)
    {

        $this->image = $srcImage;
        $this->histogram = array();  // key = RGB  value = count of this RGB value
        $this->read($step);
    }

    public function clearDatabase()
    {


    }

    public function Histogram()
    {
        return $this->histogram;
    }

    public function read($step = 1)
    {
        // if this record exists in DB then get it from there first

        if (!file_exists($this->Pathname())) return false;

        $this->readFromImage($this->Pathname(), $step);
    }

    private function readFromImage($pn, $step = 1)
    {

        $im = ImageCreateFromJpeg($pn);
        $imgw = imagesx($im);
	$imgh = imagesy($im);

        $this->bounds_left = 0;
        $this->bounds_right = $imgw;
        $this->bounds_upper = 0;
        $this->bounds_lower = $imgh;

		// process only the inner section of the image
	for ($x = $this->bounds_left; $x <= $this->bounds_right; $x = $x + $step)
	{
            for ($y = $this->bounds_upper; $y <= $this->bounds_lower; $y = $y + $step)
	    {
                $this->addRow($im, $x,$y);
	    }
        }

        unset($im);
    }


    private function addRow($im, $x,$y)
    {

        $rgb = ImageColorAt($im, $x, $y);   // get the rgb value for current pixel

        if (trim($rgb) == "") return; // if we don't have a valid rgb then don't record it

        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        if (array_key_exists($rgb, $this->histogram))
            $this->histogram[$rgb]->increment();
        else
            $this->histogram[$rgb] = new rgb($rgb);

    }

    public function writeToImage()
    {

    }


    public function Pathname()
    {
        return $this->image->Pathname();
    }

    public function Name()
    {
        return $this->image->Name();
    }

    public function Folder()
    {
        return $this->image->Folder();
    }

    public function Extension()
    {
        return $this->image->Extension();
    }



    public static function raw($filename,$step = 1)
    {

        $im = ImageCreateFromJpeg($filename);
        $imgw = imagesx($im);
	$imgh = imagesy($im);

        $bounds_left = 0;
        $bounds_right = $imgw;
        $bounds_upper = 0;
        $bounds_lower = $imgh;

        // process only the inner section of the image
        $hist = array();
	for ($x = $bounds_left; $x <= $bounds_right; $x = $x + $step)
	{
            // echo "$x ";
            for ($y = $bounds_upper; $y <= $bounds_lower; $y = $y + $step)
	    {
                $rgb = ImageColorAt($im, $x, $y);   // get the rgb value for current pixel
                $hist[$rgb]++; // we just need to count it - once we have a basic list we can then strip it down
	    }
        }
        
        unset($im);

        return $hist;

    }



    public static function raw2rgb($filename, $rawArr)
    {

        // converting less to RGB
        $format = '%02x%02x%02x';

        $db = new database();

        $preCount = $db->rowCount('histogram', 'parent', $filename);

        if (!$db->connect())
        {
            echo "Error opening DB for $filename";
            return FALSE;
        }

        $result = array();
        foreach ($rawArr as $rgb => $countOf)
        {
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b =  $rgb & 0xFF;

            $RGBhex = sprintf($format,$r,$g,$b);
            $result[$RGBhex] = $countOf;

            $sql = "INSERT INTO histogram (parent,category,rgb_count,Rdec,Gdec,Bdec) VALUES(\"$filename\",\"$RGBhex\",$countOf,$r,$g,$b)";
            $resultSet = mysql_query($sql);
            
        }

        $db->disconnect();

        return $result;

    }

}
?>
