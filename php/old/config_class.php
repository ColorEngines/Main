<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of config_class
 *
 * @author Person
 */
class config {
    //put your code here

    public static $dbHost = "localhost";
    public static $dbUser = "adamfake_webuser";
    public static $dbPassword = "user4web";
    public static $dbDatabase = "adamfake_images";

    public static $dbTableColor = "color";
    public static $dbTableTag = "tag";
    public static $serverAddress = "http://www.ViewNaturalHistory.com/";

    public static $galleryName = "View Natural History - Commercial Images";
    public static $gallerySub = "image gallery";
    public static $homeWebsite = "www.viewnaturalhistory.com";




    public static $realRoot         = '/home6/adamfake/www/';
    public static $webRoot          = "/";

    public static $realAppFolder    = "/home6/adamfake/www/vnh/gallery/";
    public static $realRootPage     = "/home6/adamfake/www/vnh/gallery/index.php";
    public static $realImagesFolder = "/home6/adamfake/www/vnh/images/library/";
    public static $realIconFolder   = "/home6/adamfake/www/vnh/images/icons/";
    public static $realColorIndexWide    = "/home6/adamfake/www/vnh/images/color_index/color_index_wide.csv";
    public static $realColorIndexLong    = "/home6/adamfake/www/vnh/images/color_index/color_index_long.csv";
    public static $realColorIndexSingle  = "/home6/adamfake/www/vnh/images/color_index/color_index_";
    public static $realColorIndexRounded = "/home6/adamfake/www/vnh/images/color_index/color_index_rounded.txt";

    public static $realTagIndexSingle    = "/home6/adamfake/www/vnh/images/tags/tag_index_";
    public static $realTagIndexFolder    = "/home6/adamfake/www/vnh/images/tags/";
    public static $realTagCloudHTML      = "/home6/adamfake/www/vnh/gallery/TagCloud.php";

    public static $realMosaicFolder     = "/home6/adamfake/www/vnh/images/mosaic/";


    public static $webAppFolder     = "/gallery/";
    public static $webRootPage      = "/gallery/index.php";
    public static $webImagesFolder  = "/images/library/";
    public static $webIconFolder    = "/images/icons/";
    public static $webColorIndexWide    = "/images/color_index/color_index_wide.csv";
    public static $webColorIndexLong    = "/images/color_index/color_index_long.csv";
    public static $webColorIndexSingle  = "/images/color_index/color_index_";
    public static $webColorIndexRounded = "/images/color_index/color_index_rounded.txt";
    public static $webFindByColorPage   = "/gallery/find_by_color.php";

    public static $webFindByTagPage     = "/gallery/FindByTag.php";

    public static $webTagIndex          = "/gallery/TagIndex.php";

    public static $webTagCloudHTML      = "TagCloud.php";

    public static $webMosaic            = "/gallery/show_mosaic.php";
    public static $webMosaicFolder      = "/images/mosaic/";


    public static $imageExtensionAllowed = ".jpg";

    public static $maxColourImages=10;

    public static $newline = "<br/>\n";
    public static $space = "&nbsp;";

    public static $web_folder_sep = "/";
    public static $fs_folder_sep = "/";
    public static $fs_extension_sep = ".";

    public static $thumbnailWidth = 300;
    public static $thumbnailHeight = 300;

    public static $thumbsDB = "Thumbs.db";
    
    public static $tinyWidth = 3;
    public static $tinyHeight = 3;

    public static $mediumWidth = 400;
    public static $mediumHeight = 400;


    public static $minimum_tag_count = 1;

}



?>
