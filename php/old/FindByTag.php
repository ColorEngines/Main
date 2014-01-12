<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<?php
    include_once 'library.php';
    $passedTags = urldecode($_GET["tag"]);
    $go = gallery::FromTag($passedTags);
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="gallery_styles.css" />
        <title></title>
    </head>
    <body>
        <?php html::header(); ?>
        <div class="subheader">TAGGED: <?php echo $passedTags; ?></div>
        <div id="gallery_content">
            <div class="gallery_lhs">
                <?php foreach ($go as $key => $value) echo $value->html(); ?>
            </div>
            <div class="gallery_rhs"><?php include_once config::$webTagCloudHTML; ?></div>
        </div>
    </body>
</html>
