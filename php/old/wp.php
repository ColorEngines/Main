<?php
    include_once 'library.php';

    $gp = gallery::webGalleryPath();
    $galley = new gallery($gp);
     $galley->display(); 
?>
