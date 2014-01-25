<?php
  $CE = new ColorEngine($ini);
?>
<h1 class="left">Color Engine</h1>
 
<div class="imageselection">
    <?php
        foreach ($CE->ImageSetURLSThumbs('backgrounds') as $name => $url) { 
    ?>
    
    <img onclick="imageaction('<?php echo $name;?>')" class="forselection" alt="<?php echo $name;?>" src="<?php echo $url;?>" />

    <?php }?>
    
  
</div> 

