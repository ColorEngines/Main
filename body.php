<?php
  $CE = new ColorEngine($ini);
?>
<h1 class="left">Color Engine</h1>

<div>
    <?php  $images = $CE->ImageSetFileLocations('backgrounds'); ?>

    <?php foreach ($images as $key => $image) { ?>

    <img class="forselection" src="<?php echo $image['url'];?>" />

    <?php }?>
    
  
</div> 

