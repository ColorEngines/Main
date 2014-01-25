<?php
/**
 * Description of ColorEngine
 *
 * @author afakes
 */
class ColorEngine extends Object {
    
    private static $THUMB_FOLDER = "thumb";
    
    public function __construct($ini = null) 
    {
        parent::__construct();
        $this->INI($ini);
        
    }
    
    public function __destruct() {
        
    }
    
    public function INI() {
        if (func_num_args() == 0)  { return $this->getProperty(); }        
        return $this->setProperty(func_get_arg(0));
    }
    
    public function ImageSets() 
    {
        if (is_null($this->INI())) return null;
        
        $sets = array_util::Value($this->INI(), 'imagesets');
        
        if (is_null($sets)) { return null; }
        
        return array_keys($sets);
    }
    

    public function ImageSetFileLocations($set)
    {
        if (is_null($this->INI())) return null;
    
        $set_metadata = $this->imageSetMetadata($set);
        if (is_null($set_metadata)) return null;
        
        $files = file::folder_with_extension($set_metadata['local'], $set_metadata['ext'],null, true);
        
        $result = array();
        foreach ($files as $filename => $filepath) 
        {
            $row = array();
            $row['filename'] = $filename;
            $row['filepath'] = $filepath;
            $row['url'] = $set_metadata['url'].$filename;
            
            $result[$filename] = $row;
        }
        
        
        return $result;
    }
    
    /**
     * 
     * [local] => /home/afakes/code/Backgrounds/ 
       [url] => /Backgrounds/ 
       [ext] => jpg )
     * 
     * @param type $set
     * @return null
     */
    private function imageSetMetadata($set)
    {
        $sets = array_util::Value($this->INI(), 'imagesets');        
        if (is_null($sets)) { return null; }
    
        $set_metadata = array_util::Value($sets, $set);
        if (is_null($set_metadata)) return null;
        
        return $set_metadata;
        
    }
    
    
    public function ImageSetURLSFullSize($set)
    {
        return matrix::Column($this->ImageSetFileLocations($set), 'url');
    }

    public function ImageSetURLSThumbs($set,$x = 100,$y = 100)
    {
        
        $set_metadata = $this->imageSetMetadata($set);
        if (is_null($set_metadata)) return null;
        
        $files = file::folder_with_extension($set_metadata['local'], $set_metadata['ext'],null, true);
        
        // look for thumb folder in folder 
        // if found then read image with same name from that folder
        $result = array();
        foreach ($files as $filename => $filepath) 
        {
            $thumb_pathname = $this->createThumbnbail($filepath);
            if (is_null($thumb_pathname)) continue;
            $result[$filename] = str_replace($set_metadata['local'], $set_metadata['url'], $thumb_pathname);
        }
        
        
        return $result;
    }
    
    
    private function createThumbnbail($filepath,$x = 100,$y = 100,$rebuild = false)
    {
        if (is_null($filepath)) return null;
        
        if ($filepath == "") return null;
        
        if (!file_exists($filepath)) return null;
        
        if (!is_numeric($x)) $x = 100;
        if (!is_numeric($y)) $y = 100;

        
        $thumb_folder = file::mkdir_safe(dirname($filepath).'/'.self::$THUMB_FOLDER);
        if (is_null($thumb_folder)) return null;
        
        $thumb_filename = $thumb_folder.'/'.basename($filepath);
        
        if ($rebuild) file::reallyDelete ($$thumb_filename);
        
        if (file_exists($thumb_filename)) return $thumb_filename;
        
        file::execute("convert '$filepath' -resize {$x}x{$y} $thumb_filename");
        
        if (!file_exists($thumb_filename)) return null;
        
        return $thumb_filename;
        
    }
    
    
}
