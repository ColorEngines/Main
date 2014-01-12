<?php

?>
<h1 class="left">Data Processor</h1>

<div class="panel" id="lhs">
    <div class="panel" id="configuration">
        <?php include './configuration.php'; ?>
    </div>

    <div class="panel" id="progress">
        <?php include './progress.php'; ?>
    </div>

</div>


<div class="panel" id="result">
        
    <div class='panel' id='actions'>
        <?php
        foreach ($config['processors']['name'] as $key => $value) 
        {
            $buttonName = util::leftStr($value, ',');
            
            echo '<button class="process_button" onclick="process(\''.$key.'\',\''.$value.'\')">'.$buttonName.'</button>';
        }
        ?>
    </div>
    <br class="clear">
    <div class="panel" id="result_content">
        Updated by Ajax Event
    </div>    
    
</div>
