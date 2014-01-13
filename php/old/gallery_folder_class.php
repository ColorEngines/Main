<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of gallery_folder_class
 *
 * @author Person
 */
class gallery_folder
{
    //put your code here

    private $galleryFolder;

    public function __construct($galleryFolder)
    {
        $this->galleryFolder = $galleryFolder;

    }


    private function folderImage($path)
    {
        $firstImagePath = image::forFolder($path);

        if ($firstImagePath == FALSE)  return html::img("green_folder.png");
        
        $firstImagePath = image_util::real2web($firstImagePath);

        return html::img($firstImagePath);

        
    }

    public function html()
    {
        // folder name
        $passedFolder = str_replace(config::$realImagesFolder,"",$this->galleryFolder);
        $simpleFolderName = util::fromLastSlash($passedFolder);
        
        $img = self::folderImage($this->galleryFolder);  // image / icon
        $folderHref = config::$webRootPage."?gp=".urlencode($passedFolder);  // url of the page to goto.
        $linkOnText  = html::href($folderHref, $img. html::div($simpleFolderName));

        $result = $linkOnText;
        return html::div($result, 'class="gallery_category_container"');
    }




}
?>
