<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<?php
    include_once 'library.php';
    $mo = new mosaic_class("");
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="gallery_styles.css" />
        <title></title>
    </head>
    <body>
        <?php html::header(); ?>
        <div class="subheader">MOASIC</div>
        <div id="gallery_content">
            <div class="gallery_lhs">
                <div class="mosaic_upper"><?php echo $mo->source_html(); ?></div>
                <div class="mosaic_lower"><?php echo $mo->mosaic_html(); ?></div>
            </div>
            <div class="gallery_rhs"><?php include_once config::$webTagCloudHTML; ?></div>
        </div>
    </body>
</html>
