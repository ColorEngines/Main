<?php  include_once "includes.php";  ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <title><?php echo util::hostname(); ?>::Data Processor</title>

        <?php include_once './include_jquery.php'; ?>

        <script>
            <?php include_once './local.js'; ?>            
            $(function() {
            <?php include_once './jquery_post_document.js'; ?>
            });
        </script>

    </head>

    <body>
       
    </body>
    
</html>
