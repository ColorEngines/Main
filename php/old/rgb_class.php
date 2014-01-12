<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rgb_class
 *
 * @author Person
 */
class rgb
{
    public $r = 0;
    public $g = 0;
    public $b = 0;
    public $rgb = 0;
    public $count = 0;
    public $key = 0;
    public $tag;

    private $dbArray;

    public function __construct($rgb)
    {
	$this->r = ($rgb >> 16) & 0xFF;
	$this->g = ($rgb >> 8) & 0xFF;
	$this->b = $rgb & 0xFF;
	$this->rgb = $rgb;
        $this->count = 1;
        $this->key = $rgb;

        $this->dbArray = array();
        $this->dbArray['parent']    = "";
        $this->dbArray['category']  = "";
        $this->dbArray['rgb']       = $rgb;
        $this->dbArray['rgb_key']   = $rgb;
        $this->dbArray['red']       = $this->r;
        $this->dbArray['green']     = $this->g;
        $this->dbArray['blue']      = $this->b;
        $this->dbArray['rgb_count'] = 1;

    }

    public function increment()
    {
            $this->count = $this->count + 1;
    }


    public function dbData()
    {
            $this->dbArray['rgb_count'] = $this->count;
            return $this->dbArray;
    }

    public function ColorCode()
    {
        $format = '#%02x%02x%02x';
        $bg = sprintf($format,$this->r,$this->g,$this->b );
        return $bg;

    }



    public static function roundHEX($src)
    {
        $ck = trim($src,'#');

        // get R G B as hex
        $hexR = substr($ck,0,2);
        $hexG = substr($ck,2,2);
        $hexB = substr($ck,4,2);

        // convert yto decimal
        $decR = hexdec($hexR);
        $decG = hexdec($hexG);
        $decB = hexdec($hexB);

        // round to nearest 10
        $roundR = round(($decR / 10)) * 10;
        $roundG = round(($decG / 10)) * 10;
        $roundB = round(($decB / 10)) * 10;

        $format = '#%02x%02x%02x';
        $roundedRGB = sprintf($format,$roundR,$roundG,$roundB );

        return $roundedRGB;
    }


}
?>
