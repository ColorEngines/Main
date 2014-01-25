<?php
/**
 * Description of ColorEngine
 *
 * @author afakes
 */
class ColorEngine extends Object {
    
    
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
        
        $sets = array_util::Value($this->INI(), 'imagesets');        
        if (is_null($sets)) { return null; }
    
        $set_metadata = array_util::Value($sets, $set);
        if (is_null($set_metadata)) return null;
        
        
        //[local] => /home/afakes/code/Backgrounds/ 
        //[url] => /Backgrounds/ 
        //[ext] => jpg )
        
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
    
    
    
}
