<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of image_rgb_class
 *
 * @author Person
 */
class image_rgb {
    //put your code here

    private $imageFilename;

    public function __construct($srcImage)
    {
        $this->init($srcImage);
    }

    private function init($srcImage)
    {
        $this->imageFilename = $srcImage;
        $this->read();
    }

    public function clearDatabase()
    {


    }

    public function read()
    {
        // if this record exists in DB then get it from there first

        if ($this->inDatabase())
        {
            $this->readFromDatabase();
        }
        else
        {
            $this->readFromImage();
            $this->storeInDatabase();
        }


    }

    public function inDatabase()
    {


        return FALSE;
    }


    public function readFromDatabase()
    {

        // if in db return true


    }

    public function storeInDatabase()
    {

        // return true - if written OK

    }

    public function readFromImage()
    {

    }



    public function writeToImage()
    {

    }


}
?>
