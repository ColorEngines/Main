<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of html_class
 *
 * @author Person
 */
class html {
    //put your code here

    public static function header()
    {
        echo '<div id="gallery_header">';
        echo '<div id="gallery_name">'.config::$galleryName.'</div>';
        echo '<div id="gallery_menu_container">'.self::menu().'</div>';
        echo '</div>';
    }

    public static function menu()
    {
        $result = "";

        $wif = config::$webIconFolder."sliding_door".config::$web_folder_sep;

        // 5,http://www.viewnaturalhistory.com/casestudies
        // 4, http://www.viewnaturalhistory.com/locations
        // 3, http://www.viewnaturalhistory.com/books
        // 2, http://www.viewnaturalhistory.com/specialservices
        // 1,http://www.viewnaturalhistory.com/about

        $result .= html::href("http://www.viewnaturalhistory.com/casestudies",     html::img($wif."5.jpg",'alt="case studies"'), 'target="_new" class="gallery_menu_item"');
        $result .= html::href("http://www.viewnaturalhistory.com/locations",       html::img($wif."4.jpg",'alt="locations"'),    'target="_new" class="gallery_menu_item"');
        $result .= html::href("http://www.viewnaturalhistory.com/books",           html::img($wif."3.jpg",'alt="books"'),        'target="_new" class="gallery_menu_item"');
        $result .= html::href("http://www.viewnaturalhistory.com/specialservices", html::img($wif."2.jpg",'alt="services"'),     'target="_new" class="gallery_menu_item"');
        $result .= html::href("http://www.viewnaturalhistory.com/about",           html::img($wif."1.jpg",'alt="about"'),        'target="_new" class="gallery_menu_item"');

        $result .= html::href(config::$webRootPage, 'by folder', 'class="gallery_menu_item"');
        $result .= html::href(config::$webTagIndex, 'index', 'class="gallery_menu_item"');
        $result .= html::href("/index.php" , 'home', 'class="gallery_menu_item"');

        return $result;
    }

    public static function footer()
    {
        echo "<div class=\"gallery_footer\">";
        echo "contact .... home .... about .... designed by ....";
        echo "</div>";
    }



    public static function img($src, $extras = "")
    {
        $result= "";
        $result = '<img border="0" src="'.$src.'" '.$extras.' >';
            
        return $result;
    }

    public static function div($content, $extras = "")
    {
        if (is_array($content)) $content = join(',', $content);

        $content = trim($content,',');

        $result  = '<div '.$extras.' >';
        $result .= $content;
        $result .= "</div>";

        return $result;
    }


    public static function element_class($src)
    {
        $result = ' class="'.$src.'" ';
        return $result;
    }

    public static function element_id($src)
    {
        $result = ' id="'.$src.'" ';
        return $result;
    }

    public static function style($src)
    {
        $result = ' style="'.$src.'" ';
        return $result;
    }


    public static function serverFolder()
    {
        $result = util::toLastSlash($_SERVER['PHP_SELF']);
        return $result;
    }

    public static function href($href, $link , $extras = "")
    {
        $result  = '<a href="'.$href.'" '.$extras.'>';
        $result .= $link ;
        $result .= "</a>";

        return $result;
    }

    public static function b($content , $extras = "")
    {
        $result  = '<b '.$extras.' >';
        $result .= $content ;
        $result .= "</b>";

        return $result;
    }

    public static function span($content , $extras = "")
    {
        $result  = '<span '.$extras.' >';
        $result .= $content ;
        $result .= "</span>";

        return $result;
    }


    public static function table($src , $extras = "")
    {
        $result  = '<div class="table">';
        foreach ($src as $key => $value)
        {
            $result .=  '<div class="row"><div class="label">'.$key.'</div><div class="value">'.$value.'</div></div>';
        }
        $result .= "</div>";
        return $result;
    }

    public static function tableValues($src , $extras = "")
    {
        $result  = '<div class="table">';
        foreach ($src as $value)
        {
            $result .=  '<div class="row"><div class="value">'.$value.'</div></div>';
        }
        $result .= "</div>";
        return $result;
    }


    public static function selfURL() {
	$s = empty($_SERVER["HTTPS"]) ? ''
		: ($_SERVER["HTTPS"] == "on") ? "s"
		: "";
	$protocol = self::strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? ""
		: (":".$_SERVER["SERVER_PORT"]);
	return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
    }

    public static function strleft($s1, $s2)
    {
        return substr($s1, 0, strpos($s1, $s2));
    }

}
?>
