<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of image_metadata_class
 *
 * @author Person
 */
class metadata {

    private $tags;    
    private $flatTags;

    private $passed_image;

    public function __construct($srcImage)
    {
        $this->passed_image = $srcImage;
        $this->read();

    }

    public function addTag($srcKey,$srcValue)
    {
        $this->flatTags[$srcKey] = $srcValue;
    }

    public function read()
    {
        // if this record exists in DB then get it from there first
        
        $this->readFromImage();
    }


    public function store($reStore = FALSE)
    {
        $table = 'metadata';

        $exists = file::reallyExists($this->Pathname());

        $db = new database();

        // if we already have a metadata don't create it again
        $metaCount = 0;

        if ($reStore == TRUE)
            $db->deleteKeyValue($table, 'filename', $this->Pathname());
        else
            $metaCount = $db->rowCount($table, 'filename', $this->Pathname());

        if ($metaCount > 0 ) return TRUE;  // we already have data
            

        // $srcArray, $table, $constantValue,$constantColumn, $keyColumn,$valueColumn
        $db->insertConstantKeyValue($this->FlatTags(), $table, $this->Pathname(),'filename' ,'name','value');
        $db->insertConstantKeyValue($this->interestingTags(), 'interesting_tags', $this->Pathname(),'filename' ,'name','value');

        return TRUE;
    }



    public function clear()
    {
        $mfn = self::metadataPathName($this->Pathname());
        return file::reallyDelete($mfn);
    }


    public function Pathname()
    {
        return $this->passed_image->Pathname();
    }

    public function Folder()
    {
        return $this->passed_image->Folder();
    }


    public function Name()
    {
        return $this->passed_image->Name();
    }

    public function readFromImage()
    {
        
        $fn = $this->Pathname();

        $image_type_arr = @getimagesize($fn,$info);
        list($width, $height) = $image_type_arr;

        $result = array();
        $result["image_relative"] = $this->Pathname();
        $result["image_name"] = $this->Name();
        $result["image_width"] = $width;
        $result["image_height"] = $height;

        
        $sections = array();

        try
        {
            $exif = exif_read_data($fn, 0, true);
        
            foreach ($exif as $key => $section) {
                foreach ($section as $name => $val) {
                   $result[$key.'_'.$name] = $val;
                }
            }

            $this->flatTags = $result;

             // get sections
             foreach ($result as $key => $val)
             {
                $tmp = explode('_',$key);
                if (!isset($sections[$tmp[0]])) $sections[$tmp[0]] = array();
                $sections[$tmp[0]][$tmp[1]] = $val;
             }

        } 
        catch (Exception $exc)
        {

        }

        $this->tags =  $sections;
        
    }

    public function writeToImage()
    {

    }
    
    public function Tags()
    {
        return $this->tags;
    }

    public function FlatTags()
    {
        return $this->flatTags;
    }


    public function debug()
    {

        print_r($this->tags);
        
    }


    public function Summary()
    {
        $result = $this->Name();
        $result = str_replace('-',' ',$result);
        $result = str_replace('.',' ',$result);
        $result = str_replace('Copy of','',$result);

        return "".$result ;
    }

    public function Detail($extras = "")
    {
        $result = html::table($this->interestingDataArray(), $extras);
        return $result;
    }




    public function interestingDataArray()
    {

        //print_r($this->flatTags);

        $result = array();

        $niceName = $this->flatTags[FILE_FileName];
        $niceName = util::toLastChar($niceName, '.');
        $niceName = str_replace('-', ' ', $niceName);
        $niceName = str_replace('_', ' ', $niceName);

        $result['name']           = $niceName ;

        $result['width x height'] = $this->flatTags[COMPUTED_Width].' x '.$this->flatTags[COMPUTED_Height];
        $result['model']          = $this->flatTags[IFD0_Model];
        $result['Exposure Time']  = $this->flatTags[EXIF_ExposureTime];
        $result['ISO Speed']      = $this->flatTags[EXIF_ISOSpeedRatings];
        $result['Date + Time']    = $this->flatTags[EXIF_DateTimeDigitized];
        $result['Flash']          = $this->flatTags[EXIF_Flash];
        $result['Focallength']    = $this->flatTags[EXIF_FocalLength];
        $result['comment']        = $this->flatTags[COMPUTED_UserComment];


        return $result;

    }

    public function interestingTags()
    {
        $folder = $this->Folder();

        $folder = str_replace(config::$realImagesFolder, '', $folder);
        $folder = str_replace(config::$fs_folder_sep,config::$web_folder_sep,$folder);

        $tags_string = $folder.config::$web_folder_sep.$niceName;

        $tags = split(config::$web_folder_sep,$tags_string);

        $result = array();
        foreach ($tags as $key => $value)
            if ($value != "") $result[$key] = $value;

        return $result ;

    }


    public function iconHtml()
    {
        $mdiPath = config::$webIconFolder.self::$METADATA_ICON;
        return html::img($mdiPath ,'class="gallery_metedata_icon"');
    }


}
?>
