<?php

$processor = $config['processors']['default'];

$files = file::folder_with_extension($config['images']['src'],$config['images']['extension'] , "/", true);

foreach ($files as $key => $value) 
{
    $img = '<img src="image.php?i='.urlencode($key).'" />';
    
    echo '<button class="process_button" onclick="process(\''.$key.'\',\''.$value.'\')">'.$img.'</button>';
}

?>
