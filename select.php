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
<?php
$CE = new ColorEngine($ini);
?>
<h1 class="left">Color Engine</h1>

<div class="imageselection">
<?php
$rowTemplate=<<<TEMPLATE
<li class="imageaction"><img onclick="imageaction('backgrounds','{#key#}')" class="forselection" alt="" src="{#value#}" /></li>
TEMPLATE;

    echo $CE->SimpleTemplate($CE->ImageSetURLSThumbs('backgrounds'), $rowTemplate);
?>

</div> 
<?php unset($CE); ?>

</body>
    
</html>
