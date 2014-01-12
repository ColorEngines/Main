<?php
    include_once 'library.php';

    $gp = gallery::webGalleryPath();
    $galley = new gallery($gp);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Image Gallery with Search by Colour</title>
        <link rel="stylesheet" type="text/css" href="gallery_styles.css" />
    </head>
    <body>
        <?php html::header(); ?>
        <div id="gallery_content">
            <div class="gallery_lhs"><?php $galley->display(); ?></div>
            <div class="gallery_rhs"><?php include_once config::$webTagCloudHTML; ?></div>
        </div>
    </body>
</html>
