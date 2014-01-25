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
        
        file::execute("convert '$filepath' -resize {$x}x{$y} '$thumb_filename'");
        
        if (!file_exists($thumb_filename)) return null;
        
        return $thumb_filename;
        
    }
    
    
    public function SimpleTemplate($src,$rowTemplate)
    {
        return join("\n",array_util::FromTemplateSimple($src, $rowTemplate));
    }
    

    public function URL4Image($set,$image)
    {
        
        $sets = array_util::Value($this->INI(), 'imagesets');        
        if (is_null($sets)) { return null; }
    
        $set_metadata = array_util::Value($sets, $set);
        if (is_null($set_metadata)) return null;
        
        return $set_metadata['url'].$image;
        
    }
    
    public function ToolsFolder()
    {
        $app = array_util::Value($this->INI(), 'application');
        if (is_null($app)) { return null; }

        $tools_folder = array_util::Value($app, 'tools_folder');
        if (is_null($tools_folder)) { return null; }
        
        return $tools_folder;
        
    }

    public function TempFolderLocal()
    {
        $tmp = array_util::Value($this->INI(), 'tmp');
        if (is_null($tmp )) { return null; }

        $tmp_folder = array_util::Value($tmp , 'local');
        if (is_null($tmp_folder)) { return null; }
        
        return $tmp_folder;
    }
    
    
    public function TempFolderURL()
    {
        $tmp = array_util::Value($this->INI(), 'tmp');
        if (is_null($tmp )) { return null; }

        $tmp_folder = array_util::Value($tmp , 'url');
        if (is_null($tmp_folder)) { return null; }
        
        return $tmp_folder;
    }
    

    public function ToolList()
    {        
        if (is_null($this->ToolsFolder())) { return null; }        
        return array_util::ReplaceInKey(file::folder_php($this->ToolsFolder(), '/', true), ".php", "");   
    }

    public function URL2Tempfolder($url)
    {
        // check to see if this is in the URL cache
        if (is_null($this->TempFolderLocal())) return null;
        
        $cached = $this->get_from_url_cache($url);
        if (!is_null($cached)) return $cached;
        
        $randomFolder = str_replace("//", "/", file::mkdir_safe($this->TempFolderLocal().file::random_filename("")));
        if (is_null($randomFolder)) return null;
        
        $outputfile = file::wget($url, "{$randomFolder}/".util::rightStr($url, "/", false));
        if (is_null($outputfile)) return null;
        
        $this->update_url_cache($url,$outputfile);
        
        return $outputfile;
        
    }
    
    private function get_from_url_cache($url)
    {
        $url_cache_filename = $this->url_cache_filename();        
        if (is_null($url_cache_filename)) { return null; }
     
        if (!file_exists($url_cache_filename)) { return null; }
        
        file::grepped_text_file($url_cache_filename, "'{$url}'");
        
        $grepped = util::first_element(file::execute("grep '{$url}' '{$url_cache_filename}'"));

        if (count($grepped) == 0) return null;
        
        $cells = str_getcsv($grepped);
        if (count($cells) < 2) return null;
        
        return $cells[1];
        
        
    }
    
    private function update_url_cache($url,$filename)
    {
        
        $url_cache_filename = $this->url_cache_filename();        
        if (is_null($url_cache_filename)) { return null; }
        
        if (!file_exists($url_cache_filename))
            file_put_contents($url_cache_filename,"url,filename\n",FILE_APPEND);
        
        file_put_contents($url_cache_filename,'"'.$url.'","'.$filename.'"'."\n",FILE_APPEND);
        
    }
    
    private function url_cache_filename()
    {
        $tmp = array_util::Value($this->INI(), 'tmp');
        if (is_null($tmp )) { return null; }

        $url_cache_filename = array_util::Value($tmp , 'url_cache_filename');
        if (is_null($url_cache_filename)) { return null; }
        
        return $url_cache_filename;
        
    }
    
    
    public function ImageHistogram($filename,$top_color_count = 200)
    {
        if (is_null($filename)) return null;
        if (!file_exists($filename)) return null;
        
        $result = array();
        
        foreach(file::execute("convert '{$filename}' -define histogram:unique-colors=true -format %c histogram:info:- | grep '#' | grep '(' | grep ')' ") as $line) 
            $result['#'.util::midStr($line, "#", " ",true,false)] = util::leftStr($line, ":",false);

        arsort($result);
        
        $partResult = array();
        
        $count = 0;
        foreach ($result as $key => $value) 
        {
            if ($count < $top_color_count) 
                $partResult[$key] = $value;
            
            $count++;
        }
        
        return $partResult;
        
    }
    
    
    
    
}
